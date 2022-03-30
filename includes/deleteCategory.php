<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    // header("location: " . $baseURL);
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
require_once "functions.php";


if (!isset($_POST['submit']) || !isset($_POST['categoryID'])) {
    // header("location: " . $baseURL);
    echo "error=notEnoughVariables";
    exit();
}

$categoryID = $_POST['categoryID'];

// DELETE CONNECTED RULES
// FOREACH RULE, DELETE CONNECTED QUESTIONS AND GRADE

$sqlGetRules = "SELECT * FROM rule WHERE rule.categoryID = ?;";
$stmtGetRules = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtGetRules, $sqlGetRules)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtGetRules, "i", $categoryID);
mysqli_stmt_execute($stmtGetRules);

$resultData = mysqli_stmt_get_result($stmtGetRules);
while ($row = mysqli_fetch_assoc($resultData)) {
    // FOREACH RULE, DELETE RULE
    deleteRulesFunction($conn, $row["ruleID"]);
}

$sqlDeleteGrades = "DELETE FROM gradepercategory WHERE categoryID = ?;";
$stmtDeleteGrades = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteGrades, $sqlDeleteGrades)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteGrades, "i", $categoryID);
mysqli_stmt_execute($stmtDeleteGrades);
mysqli_stmt_close($stmtDeleteGrades);

$sqlDeleteCategory = "DELETE FROM category WHERE categoryID = ?;";
$stmtDeleteCategory = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteCategory, $sqlDeleteCategory)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteCategory, "i", $categoryID);
mysqli_stmt_execute($stmtDeleteCategory);
mysqli_stmt_close($stmtDeleteCategory);

echo "error=none";
