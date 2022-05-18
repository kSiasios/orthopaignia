<?php

if (isset($_POST['submit'])) {
    // $quizID = intval($_POST["rule-category"], $base = 10);
    $quizID = intval($_POST["rule-quiz"], $base = 10);

    if ($quizID == null) {
        echo "error=quizNotProvided";
        exit();
    }

    require_once "db.info.php";
    require_once "functions.php";

    // CHECK IF RULE ALREADY EXISTS
    $sql = "SELECT * FROM rules WHERE ruleName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $_POST["rule-name"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        echo "error=ruleAlreadyExists";
        mysqli_stmt_close($stmt);
        exit();
    } else {

        // CHECK IF RULE'S QUIZ EXISTS
        mysqli_stmt_close($stmt);
        $sqlCheckQuiz = "SELECT * FROM quizzes WHERE quizID = ?;";
        $stmtCheckQuiz = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtCheckQuiz, $sqlCheckQuiz)) {
            echo ("error=stmtFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmtCheckQuiz, "i", $quizID);
        mysqli_stmt_execute($stmtCheckQuiz);

        $resultDataCheckQuiz = mysqli_stmt_get_result($stmtCheckQuiz);
        if ($row2 = mysqli_fetch_assoc($resultDataCheckQuiz)) {
            mysqli_stmt_close($stmtCheckQuiz);
            $sqlInsert = "INSERT INTO rules(ruleName, ruleText, quizID) VALUES (?, ?, ?);";
            $stmtInsert = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmtInsert, $sqlInsert)) {
                echo ("error=stmtFailed");
                exit();
            }

            mysqli_stmt_bind_param($stmtInsert, "ssi", $_POST["rule-name"], $_POST["rule-text"], $quizID);

            mysqli_stmt_execute($stmtInsert);
            echo "error=none";
            mysqli_stmt_close($stmtInsert);
            exit();
        } else {
            echo "error=quizNotFound";
            exit();
        }
    }
} else {
    echo ("error=accessDenied");
    exit();
}
