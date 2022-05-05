<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";


if (!isset($_POST['submit']) || !isset($_POST['questionID'])) {
    echo "error=notEnoughVariables";
    exit();
}

$questionID = $_POST['questionID'];

$sqlDeleteAnswers = "DELETE FROM answers WHERE questionID = ?;";
$stmtDeleteAnswers = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteAnswers, $sqlDeleteAnswers)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $questionID);
mysqli_stmt_execute($stmtDeleteAnswers);
mysqli_stmt_close($stmtDeleteAnswers);

$sqlDeleteGrades = "DELETE FROM gradeperquestion WHERE questionID = ?;";
$stmtDeleteGrades = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteGrades, $sqlDeleteGrades)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteGrades, "i", $questionID);
mysqli_stmt_execute($stmtDeleteGrades);
mysqli_stmt_close($stmtDeleteGrades);

$sqlDeleteQuestion = "DELETE FROM questions WHERE questionID = ?;";
$stmtDeleteQuestion = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteQuestion, $sqlDeleteQuestion)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteQuestion, "i", $questionID);
mysqli_stmt_execute($stmtDeleteQuestion);
mysqli_stmt_close($stmtDeleteQuestion);

echo "error=none";
