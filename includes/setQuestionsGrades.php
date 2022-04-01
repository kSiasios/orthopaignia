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

$results = explode("|", $_POST["results"]);

for ($i = 0; $i < sizeof($results); $i++) {
    $results[$i] = explode("~", $results[$i]);
}
echo "HERE 1<br/>";

$userID = getUserID($conn, $_SESSION["username"]);

if (!$userID) {
    echo '{"error": "userNotFound"}';
    exit();
}

for ($i = 0; $i < sizeof($results); $i++) {
    // FOREACH QUESTION ANSWERED, GET ID, UPDATE ITS GRADE, RELEVANT_GRADE, ATTEMPTS_PER_QUESTION, RELEVANT_ATTEMPTS
    $questionID = getQuestionByText($conn, $results[$i][0]);
    if (!$questionID) {
        echo '{"error": "questionNotFound"}';
        exit();
    }

    $prevGrade;
    $sqlGetPrevGrade = "SELECT * FROM gradeperquestion WHERE questionID = ? AND userID = ?;";
    $stmtGetPrevGrade = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetPrevGrade, $sqlGetPrevGrade)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtGetPrevGrade, "ii", $questionID, $userID);
    mysqli_stmt_execute($stmtGetPrevGrade);

    $resultData = mysqli_stmt_get_result($stmtGetPrevGrade);
    if ($rowPrevGrade = mysqli_fetch_assoc($resultData)) {
        $prevGrade = $rowPrevGrade;
    } else {
        $sqlCreateGrade = "INSERT INTO gradeperquestion(userID, questionID) VALUES (?, ?);";
        $stmtCreateGrade = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtCreateGrade, "ii", $userID, $questionID);
        mysqli_stmt_execute($stmtCreateGrade);
        mysqli_stmt_close($stmtCreateGrade);

        $sqlGetPrevGrade2 = "SELECT * FROM gradeperquestion WHERE questionID = ? AND userID = ?;";
        $stmtGetPrevGrade2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtGetPrevGrade2, $sqlGetPrevGrade2)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtGetPrevGrade2, "ii", $questionID, $userID);
        mysqli_stmt_execute($stmtGetPrevGrade2);

        $resultData2 = mysqli_stmt_get_result($stmtGetPrevGrade2);
        if ($rowPrevGrade = mysqli_fetch_assoc($resultData2)) {
            $prevGrade = $rowPrevGrade;
        } else {
            exit();
        }
        mysqli_stmt_close($stmtGetPrevGrade2);
    }
    mysqli_stmt_close($stmtGetPrevGrade);

    if (!$prevGrade["grade"]) {
        $prevGrade["grade"] = "0.0";
    }
    if (!$prevGrade["attemptsPerQuestion"]) {
        $prevGrade["attemptsPerQuestion"] = "0";
    }
    if (!$prevGrade["relevantAttempts"]) {
        $prevGrade["relevantAttempts"] = "";
    }

    echo "Relevant attempts '" . $prevGrade["relevantAttempts"] . "'";
    $newGrade = ((floatval($prevGrade["grade"]) * intval($prevGrade["attemptsPerQuestion"])) + intval($results[$i][1])) / (intval($prevGrade["attemptsPerQuestion"]) + 1);
    $newRelevantAttempts = explode(",", $prevGrade["relevantAttempts"]);
    $newRelevantGrade = 0;
    for ($j = 1; $j < sizeof($newRelevantAttempts); $j++) {
        $newRelevantGrade += intval($newRelevantAttempts[$j]);
    }
    $newRelevantGrade = ($newRelevantGrade + $results[$i][1]) / sizeof($newRelevantAttempts);

    // echo "sizeof(newRelevantAttempts) = " . sizeof($newRelevantAttempts);

    if (sizeof($newRelevantAttempts) >= $relevantAttemptsNumber) {
        // array_shift($newRelevantAttempts);
        $newRelevantAttempts = array_slice($newRelevantAttempts, 1);
    }
    array_push($newRelevantAttempts, $results[$i][1]);
    echo "New Relevant Attempts: " . $newRelevantAttempts;
    $newRelevantAttempts = implode(",", $newRelevantAttempts);
    // echo "New Relevant Attempts: " . $newRelevantAttempts;
    // $newRelevantAttempts .= ",";
    // $newRelevantAttempts .= $results[$i][1];
    $newAttempts = intval($prevGrade["attemptsPerQuestion"]) + 1;

    // CREATE QUERY TO UPDATE
    $sqlUpdateGrade = "UPDATE gradeperquestion SET grade=?, attemptsPerQuestion=?, relevantGrade=?, relevantAttempts=? WHERE questionID = ?;";
    $stmtUpdateGrade = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUpdateGrade, $sqlUpdateGrade)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtUpdateGrade, "didsi", $newGrade, $newAttempts, $newRelevantGrade, $newRelevantAttempts, $questionID);
    mysqli_stmt_execute($stmtUpdateGrade);

    mysqli_stmt_close($stmtUpdateGrade);
}


echo '{"error":"none"}';
