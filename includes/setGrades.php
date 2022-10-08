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

// debugEcho(true);

$relevantAttemptsNumber = 5;

$results = json_decode($_POST["results"]);

$quizID = $_POST["quizID"];

if (isset($_POST["studyTime"])) {
    $studyTime = floatval($_POST["studyTime"]) / 10;
} else {
    $studyTime = 0;
}

// echo "QUIZ_ID: " . $quizID . "\n";
// debugEcho(true);

$userID = getUserID($conn, $_SESSION["username"]);
// debugEcho();

if (!$userID) {
    echo '{"error": "userNotFound"}';
    exit();
}
// debugEcho();

$rightAnswersCount = 0;
// count right answers
for ($i = 0; $i < sizeof($results); $i++) {
    // FOREACH QUESTION ANSWERED, GET ID, UPDATE ITS GRADE, RELEVANT_GRADE, ATTEMPTS_PER_QUESTION, RELEVANT_ATTEMPTS

    if ($results[$i][1] == 1) {
        // echo "RRIGHT ANSWER";
        $rightAnswersCount++;
    }

    // $questionInfo = getQuestionByText($conn, $results[$i][0]);
    // if (!$questionInfo) {
    //     echo '{"error": "questionNotFound"}';
    //     exit();
    // }

    // $questionID = $questionInfo["questionID"];
    // $ruleID = $questionInfo["ruleID"];


    // TO DO

    // Check if there already is an evaluation for this quiz and user

    // If there are NO evaluations for the user and quiz
    //     Create evaluation for user and quiz
    //     Create grade for this evaluation
    //     Update first attempt for this evaluation

    // $sqlGetEvaluations = "SELECT * FROM evaluations WHERE userID = ? AND quizID = ?;";
    // $stmtGetEvaluations = mysqli_stmt_init($conn);
    // if (!mysqli_stmt_prepare($stmtGetEvaluations, $sqlGetEvaluations)) {
    //     echo '{"error": "stmtFailed"}';
    //     exit();
    // }

    // mysqli_stmt_bind_param($stmtGetEvaluations, "ii", $userID, $quizID);
    // mysqli_stmt_execute($stmtGetEvaluations);


    // $resultData = mysqli_stmt_get_result($stmtGetEvaluations);
    // $evaluationID;
    // if ($rowEval = mysqli_fetch_assoc($resultData)) {
    //     // We have an evaluation
    //     // we only have to update the latest update
    //     $evaluationID = $rowEval["evaluationID"];

    //     echo "EVALUATION_ID: " . $evaluationID . "\n";
    // } else {
    //     // Create evaluation and grade
    //     // Get the id of the evaluation 
    // }










    // $prevGrade;
    // $sqlGetPrevGrade = "SELECT * FROM gradeperquestion WHERE questionID = ? AND userID = ?;";
    // $stmtGetPrevGrade = mysqli_stmt_init($conn);
    // if (!mysqli_stmt_prepare($stmtGetPrevGrade, $sqlGetPrevGrade)) {
    //     echo '{"error": "stmtFailed"}';
    //     exit();
    // }

    // mysqli_stmt_bind_param($stmtGetPrevGrade, "ii", $questionID, $userID);
    // mysqli_stmt_execute($stmtGetPrevGrade);


    // $resultData = mysqli_stmt_get_result($stmtGetPrevGrade);
    // if ($rowPrevGrade = mysqli_fetch_assoc($resultData)) {
    //     $prevGrade = $rowPrevGrade;
    // } else {
    //     $sqlCreateGrade = "INSERT INTO gradeperquestion(userID, questionID) VALUES (?, ?);";
    //     $stmtCreateGrade = mysqli_stmt_init($conn);
    //     if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
    //         echo '{"error": "stmtFailed"}';
    //         exit();
    //     }

    //     mysqli_stmt_bind_param($stmtCreateGrade, "ii", $userID, $questionID);
    //     mysqli_stmt_execute($stmtCreateGrade);
    //     mysqli_stmt_close($stmtCreateGrade);

    //     $sqlGetPrevGrade2 = "SELECT * FROM gradeperquestion WHERE questionID = ? AND userID = ?;";
    //     $stmtGetPrevGrade2 = mysqli_stmt_init($conn);
    //     if (!mysqli_stmt_prepare($stmtGetPrevGrade2, $sqlGetPrevGrade2)) {
    //         echo '{"error": "stmtFailed"}';
    //         exit();
    //     }

    //     mysqli_stmt_bind_param($stmtGetPrevGrade2, "ii", $questionID, $userID);
    //     mysqli_stmt_execute($stmtGetPrevGrade2);

    //     $resultData2 = mysqli_stmt_get_result($stmtGetPrevGrade2);
    //     if ($rowPrevGrade = mysqli_fetch_assoc($resultData2)) {
    //         $prevGrade = $rowPrevGrade;
    //     } else {
    //         exit();
    //     }
    //     mysqli_stmt_close($stmtGetPrevGrade2);
    // }
    // mysqli_stmt_close($stmtGetPrevGrade);


    // if (!$prevGrade["grade"]) {
    //     $prevGrade["grade"] = "0.0";
    // }
    // if (!$prevGrade["attemptsPerQuestion"]) {
    //     $prevGrade["attemptsPerQuestion"] = "0";
    // }
    // if (!$prevGrade["relevantAttempts"]) {
    //     $prevGrade["relevantAttempts"] = "";
    // }


    // $newGrade = ((floatval($prevGrade["grade"]) * intval($prevGrade["attemptsPerQuestion"])) + intval($results[$i][1])) / (intval($prevGrade["attemptsPerQuestion"]) + 1);
    // $newRelevantAttempts = explode(",", $prevGrade["relevantAttempts"]);
    // $newRelevantGrade = 0;
    // for ($j = 1; $j < sizeof($newRelevantAttempts); $j++) {
    //     $newRelevantGrade += intval($newRelevantAttempts[$j]);
    // }
    // $newRelevantGrade = ($newRelevantGrade + $results[$i][1]) / sizeof($newRelevantAttempts);




    // if (sizeof($newRelevantAttempts) >= $relevantAttemptsNumber) {
    //     $newRelevantAttempts = array_slice($newRelevantAttempts, 1);
    // }
    // array_push($newRelevantAttempts, $results[$i][1]);

    // $newRelevantAttempts = implode(",", $newRelevantAttempts);
    // $newAttempts = intval($prevGrade["attemptsPerQuestion"]) + 1;




    // // CREATE QUERY TO UPDATE
    // $sqlUpdateGrade = "UPDATE gradeperquestion SET grade = ?, attemptsPerQuestion = ?, relevantGrade = ?, relevantAttempts = ? WHERE questionID = ?;";
    // $stmtUpdateGrade = mysqli_stmt_init($conn);
    // if (!mysqli_stmt_prepare($stmtUpdateGrade, $sqlUpdateGrade)) {
    //     echo '{"error": "stmtFailed"}';
    //     exit();
    // }

    // mysqli_stmt_bind_param($stmtUpdateGrade, "didsi", $newGrade, $newAttempts, $newRelevantGrade, $newRelevantAttempts, $questionID);
    // mysqli_stmt_execute($stmtUpdateGrade);

    // mysqli_stmt_close($stmtUpdateGrade);




    // // UPDATE RULE GRADE?
    // updateGradePerRule($conn, $ruleID, $userID);
}

// debugEcho();


$evalExists = false;
$evaluationID = "";

if ($eval = getEvaluations($conn, $userID, $quizID)) {
    // We have an evaluation
    $evalExists = true;
    // we only have to update the latest update
    $evaluationID = $eval["evaluationID"];

    // echo "EVALUATION_ID (Found): " . $evaluationID . "\n";
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
        // echo "EVALUATION_ID (Created): " . $evaluationID . "\n";
    } else {
        echo '{"error": "failedToCreateEval"}';
        exit();
    }
}

// debugEcho();


// Create grade 

// $rightAnswersCount = 0;
$successRatio = $rightAnswersCount / sizeof($results);

$currentDate = date("D M j G:i:s T Y");

// $avgTimePerQuestion = (floatval($_POST["totalTime"]) / sizeof($results)) / 10;
$totalTime = floatval($_POST["totalTime"]) / 10;

// echo "STATS:\n"
//     . "\tSUCCESS RATIO: " . ($successRatio * 100) . "%\n"
//     . "\tDATE: $currentDate\n"
//     . "\tAVG TIME PER QUESTION: " . ($avgTimePerQuestion / 10) . " seconds\n";
// debugEcho();


$sqlCreateGrade = "INSERT INTO grades(answerTime, successRatio, evaluationID, gradeDate, studyTime) VALUES (?, ?, ?, ?, ?);";
$stmtCreateGrade = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
    echo '{"error": "stmtFailed"}';
    exit();
}

// debugEcho();

// echo "INSERT INTO grades(timePerQuestion, successRatio, evaluationID, gradeDate) VALUES ($avgTimePerQuestion, $successRatio, $evaluationID, $currentDate);";
// mysqli_stmt_bind_param($stmtCreateGrade, "ddis", $avgTimePerQuestion, $successRatio, $evaluationID, $currentDate);
mysqli_stmt_bind_param($stmtCreateGrade, "ddisd", $totalTime, $successRatio, $evaluationID, $currentDate, $studyTime);
// echo "HERE";

mysqli_stmt_execute($stmtCreateGrade);
mysqli_stmt_close($stmtCreateGrade);

// debugEcho();

$gradeID = "";

$sqlGetGrade = "SELECT * FROM grades WHERE evaluationID = ? AND gradeDate = ?;";
$stmtGetGrade = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtGetGrade, $sqlGetGrade)) {
    echo '{"error": "stmtFailed"}';
    exit();
}
// debugEcho();

mysqli_stmt_bind_param($stmtGetGrade, "is", $evaluationID, $currentDate);
// debugEcho();

mysqli_stmt_execute($stmtGetGrade);
// debugEcho();

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
// debugEcho();

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
// debugEcho();

echo '{"error":"none"}';
