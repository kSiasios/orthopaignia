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
// FOREACH RULE, DELETE CONNECTED QUESTIONS AND GRADE

$sqlGetRules = "SELECT * FROM rule WHERE rule.quizID = ?;";
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
    deleteRulesFunction($conn, $row["ruleID"]);
}

// DELETE CONNECTED EVALUATIONS
//      DELETE CONNECTED GRADES

// DELETE CONNECTED QUESTIONS
//      DELETE CONNECTED ANSWERS

// DELETE CONNECTED RULES

$sqlDeleteGrades = "DELETE FROM gradepercategory WHERE categoryID = ?;";
$stmtDeleteGrades = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteGrades, $sqlDeleteGrades)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteGrades, "i", $categoryID);
mysqli_stmt_execute($stmtDeleteGrades);
mysqli_stmt_close($stmtDeleteGrades);

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
