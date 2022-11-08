<?php

session_start();

if (!isset($_SESSION["logged"])) {
    echo '{"error": "notLoggedIn"}';
    exit();
}

if (!isset($_POST["submit"])) {
    echo '{"error": "unauthorized"}';
    exit();
}
require_once "db.info.php";
require_once "functions.php";

$relevantAttemptsNumber = 5;

$results = json_decode($_POST["results"]);

$quizID = $_POST["quizID"];

if (isset($_POST["studyTime"])) {
    $studyTime = floatval($_POST["studyTime"]) / 10;
} else {
    $studyTime = 0;
}

$userID = getUserID($conn, $_SESSION["username"]);

if (!$userID) {
    echo '{"error": "userNotFound"}';
    exit();
}

$rightAnswersCount = 0;

for ($i = 0; $i < sizeof($results); $i++) {
    // FOREACH QUESTION ANSWERED, GET ID, UPDATE ITS GRADE, RELEVANT_GRADE, ATTEMPTS_PER_QUESTION, RELEVANT_ATTEMPTS
    if ($results[$i][1] == 1) {
        $rightAnswersCount++;
    }
}
$evalExists = false;
$evaluationID = "";

if ($eval = getEvaluations($conn, $userID, $quizID)) {
    // We have an evaluation
    $evalExists = true;

    $evaluationID = $eval["evaluationID"];
} else {
    $evalExists = false;
    // Create evaluation
    $sqlCreateEval = "INSERT INTO evaluations(userID, quizID) VALUES (?, ?);";
    $stmtCreateEval = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtCreateEval, $sqlCreateEval)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }
    mysqli_stmt_bind_param($stmtCreateEval, "ii", $userID, $quizID);
    mysqli_stmt_execute($stmtCreateEval);
    mysqli_stmt_close($stmtCreateEval);

    // Get the id of the evaluation
    if ($createdEval = getEvaluations($conn, $userID, $quizID)) {
        $evaluationID = $createdEval["evaluationID"];
    } else {
        echo '{"error": "failedToCreateEval"}';
        exit();
    }
}

// Create grade 
$successRatio = $rightAnswersCount / sizeof($results);
$currentDate = date("D M j G:i:s T Y");
$totalTime = floatval($_POST["totalTime"]) / 10;

$sqlCreateGrade = "INSERT INTO grades(answerTime, successRatio, evaluationID, gradeDate, studyTime) VALUES (?, ?, ?, ?, ?);";
$stmtCreateGrade = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
    echo '{"error": "stmtFailed"}';
    exit();
}

mysqli_stmt_bind_param($stmtCreateGrade, "ddisd", $totalTime, $successRatio, $evaluationID, $currentDate, $studyTime);

mysqli_stmt_execute($stmtCreateGrade);
mysqli_stmt_close($stmtCreateGrade);

$gradeID = "";

$sqlGetGrade = "SELECT * FROM grades WHERE evaluationID = ? AND gradeDate = ?;";
$stmtGetGrade = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtGetGrade, $sqlGetGrade)) {
    echo '{"error": "stmtFailed"}';
    exit();
}

mysqli_stmt_bind_param($stmtGetGrade, "is", $evaluationID, $currentDate);
mysqli_stmt_execute($stmtGetGrade);

$resultGetGrade = mysqli_stmt_get_result($stmtGetGrade);
if ($rowGrade = mysqli_fetch_assoc($resultGetGrade)) {
    $gradeID = $rowGrade["gradeID"];
} else {
    echo '{"error": "failedToCreateGrade"';
    exit();
}

mysqli_stmt_close($stmtGetGrade);

if (!$evalExists) {
    // Update firstAttempt too
    $sqlUpdateFirstAttempt = "UPDATE evaluations SET firstAttempt = ? WHERE evaluationID = ?;";
    $stmtUpdateFirstAttempt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUpdateFirstAttempt, $sqlUpdateFirstAttempt)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtUpdateFirstAttempt, "ii", $gradeID, $evaluationID);
    mysqli_stmt_execute($stmtUpdateFirstAttempt);
    mysqli_stmt_close($stmtUpdateFirstAttempt);
}

// Update latestAttempt

$sqlUpdateLatestAttempt = "UPDATE evaluations SET latestAttempt = ? WHERE evaluationID = ?;";
$stmtUpdateLatestAttempt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtUpdateLatestAttempt, $sqlUpdateLatestAttempt)) {
    echo '{"error": "stmtFailed"}';
    exit();
}

mysqli_stmt_bind_param($stmtUpdateLatestAttempt, "ii", $gradeID, $evaluationID);
mysqli_stmt_execute($stmtUpdateLatestAttempt);
mysqli_stmt_close($stmtUpdateLatestAttempt);

echo '{"error":"none"}';
