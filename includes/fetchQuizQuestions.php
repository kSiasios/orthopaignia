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

$returnData = '{"error":"none"';

echo ($_POST["testArray"]);

// if (isset($_POST["ruleID"])) {
//     $ruleID = $_POST["ruleID"];
//     // FETCH QUESTIONS OF A RULE
//     $sql = "SELECT * FROM questions WHERE ruleID = ?;";
//     $stmt = mysqli_stmt_init($conn);
//     if (!mysqli_stmt_prepare($stmt, $sql)) {
//         echo '{"error": "stmtFailed"}';
//         exit();
//     }

//     mysqli_stmt_bind_param($stmt, "i", $ruleID);
//     mysqli_stmt_execute($stmt);

//     $resultData = mysqli_stmt_get_result($stmt);
//     $index = 0;
//     while ($row = mysqli_fetch_assoc($resultData)) {
//         // FETCH ANSWERS FOREACH QUESTION
//         $returnData .= ',"question-' . $index . '":{"text":"' . $row["questionText"] . '"';
//         $answerIndex = 0;
//         $sqlFetchWrongAnswers = "SELECT * FROM answers WHERE questionID = ?;";
//         $stmtFetchWrongAnswers = mysqli_stmt_init($conn);
//         if (!mysqli_stmt_prepare($stmtFetchWrongAnswers, $sqlFetchWrongAnswers)) {
//             echo '{"error": "stmtFailed"}';
//             exit();
//         }

//         mysqli_stmt_bind_param($stmtFetchWrongAnswers, "i", $row["questionID"]);
//         mysqli_stmt_execute($stmtFetchWrongAnswers);

//         $resultWrongAnswers = mysqli_stmt_get_result($stmtFetchWrongAnswers);
//         while ($rowWrongAnswers = mysqli_fetch_assoc($resultWrongAnswers)) {
//             $returnData .= ',"answer-' . $answerIndex . '":"' . $rowWrongAnswers["answerText"] . '"';
//             $answerIndex++;
//         }

//         $returnData .= '}';
//         $index++;
//     }

//     $returnData .= '}';
// } else {
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
    while ($row = mysqli_fetch_assoc($resultData)) {
        // FETCH ANSWERS FOREACH QUESTION
        $returnData .= ',"question-' . $index . '":{"text":"' . $row["questionText"] . '"';
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
            $returnData .= ',"answer-' . $answerIndex . '":"' . $rowWrongAnswers["answerText"] . '"';
            $answerIndex++;
        }

        $returnData .= '}';
        $index++;
    }

    $returnData .= '}';
} else {
    // FETCH ALL QUESTIONS
    $sql = "SELECT * FROM questions;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $index = 0;

    while ($row = mysqli_fetch_assoc($resultData)) {
        $returnData .= ', "question-' . $index . '":{"text":"' . $row["questionText"] . '"';
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
            $returnData .= ',"answer-' . $answerIndex . '":"' . $rowWrongAnswers["answerText"] . '"';
            $answerIndex++;
        }

        $returnData .= '}';
        $index++;
    }

    $returnData .= '}';
}

echo $returnData;
