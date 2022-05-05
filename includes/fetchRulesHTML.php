<?php

session_start();

if (!isset($_SESSION['logged'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

$sql = "SELECT * FROM rule;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$returnTxt .= "{";
$index = 0;
while ($row = mysqli_fetch_assoc($resultData)) {
    $returnTxt .= "\"" . $index . "\": {\"ruleName\": \"" . $row['ruleName'] . "\", \"ruleID\": \"" . $row['ruleID'] . "\", \"ruleText\": \"" . str_replace("\"", "'", $row['ruleText']) . "\"}, ";
    $index++;
}

$returnTxt = rtrim($returnTxt, ", ") . "}";

echo $returnTxt;
exit();
