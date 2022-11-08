<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
require_once "functions.php";
if (!isset($_POST['submit']) || !isset($_POST['quizID'])) {
    echo "error=notEnoughVariables";
    exit();
}

$quizID = $_POST['quizID'];

// DELETE CONNECTED RULES

$sqlGetRules = "SELECT * FROM rules WHERE quizID = ?;";
$stmtGetRules = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtGetRules, $sqlGetRules)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtGetRules, "i", $quizID);
mysqli_stmt_execute($stmtGetRules);

$resultData = mysqli_stmt_get_result($stmtGetRules);
while ($row = mysqli_fetch_assoc($resultData)) {
    // FOREACH RULE, DELETE RULE
    deleteRule($conn, $row["ruleID"]);
}

deleteEvaluationsForQuiz($conn, $quizID);
$sqlGetQuestions = "SELECT * FROM questions WHERE quizID = ?;";
$stmtGetQuestions = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtGetQuestions, $sqlGetQuestions)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtGetQuestions, "i", $quizID);
mysqli_stmt_execute($stmtGetQuestions);

$resultData = mysqli_stmt_get_result($stmtGetQuestions);
while ($row = mysqli_fetch_assoc($resultData)) {
    // FOREACH EVALUATION, DELETE CONNECTED GARDES
    deleteQuestion($conn, $row["questionID"]);
}

mysqli_stmt_close($stmtGetQuestions);
$sqlDeleteQuiz = "DELETE FROM quizzes WHERE quizID = ?;";
$stmtDeleteQuiz = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteQuiz, $sqlDeleteQuiz)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteQuiz, "i", $quizID);
mysqli_stmt_execute($stmtDeleteQuiz);
mysqli_stmt_close($stmtDeleteQuiz);

echo "error=none";
