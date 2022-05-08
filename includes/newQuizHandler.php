<?php

if (isset($_POST['submit'])) {
    $quizText = $_POST["quiz-text"];

    require_once "db.info.php";
    require_once "functions.php";

    // CHECK IF QUIZ ALREADY EXISTS

    $sql = "SELECT * FROM quizzes WHERE quizTitle = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $quizText);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        echo "error=quizAlreadyExists";
        mysqli_stmt_close($stmt);
        exit();
    } else {
        mysqli_stmt_close($stmt);

        $sql = "INSERT INTO quizzes(quizTitle) VALUES (?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=stmtFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $quizText);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    echo "error=none";
} else {
    echo ("error=accessDenied");
    exit();
}
