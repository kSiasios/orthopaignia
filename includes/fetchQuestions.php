<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

$returnData = array();

if (isset($_POST["quizID"])) {
    $quizID = $_POST["quizID"];
    // FETCH QUESTIONS OF A RULE
    $sql = "SELECT * FROM questions WHERE quizID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $quizID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $index = 0;

    $answerData = array();

    while ($row = mysqli_fetch_assoc($resultData)) {
        // FETCH ANSWERS FOREACH QUESTION
        array_push($answerData, $row);

        $answerIndex = 0;
        $sqlFetchWrongAnswers = "SELECT * FROM answers WHERE questionID = ?;";
        $stmtFetchWrongAnswers = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtFetchWrongAnswers, $sqlFetchWrongAnswers)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtFetchWrongAnswers, "i", $row["questionID"]);
        mysqli_stmt_execute($stmtFetchWrongAnswers);

        $resultWrongAnswers = mysqli_stmt_get_result($stmtFetchWrongAnswers);
        while ($rowWrongAnswers = mysqli_fetch_assoc($resultWrongAnswers)) {
            array_push($answerData, $rowWrongAnswers);
            $answerIndex++;
        }

        array_push($returnData, $answerData);

        $index++;
    }
} else {

    $sql = "SELECT * FROM questions;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($resultData)) {
        array_push($returnData, $row);
    }
}
$jsonData = json_encode($returnData);

echo $jsonData;

exit();
