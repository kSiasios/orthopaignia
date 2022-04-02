<?php

session_start();

if (!isset($_SESSION['logged'])) {
    echo '{"error": "unauthorized"}';
    exit();
}

require_once "db.info.php";
require_once "functions.php";

if (!isset($_POST['submit']) || !isset($_POST['user'])) {
    echo '{"error": "notEnoughVariables"}';
    exit();
}

// $userCredentials = $_POST['user'];
$userID = getUserID($conn, $_POST['user']);
if (!$userID) {
    echo '{"error": "userNotFound"}';
    exit();
}

$returnData = '{"error": "none",';
$sql = "SELECT * FROM gradeperrule WHERE userID = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$index = 0;

while ($row = mysqli_fetch_assoc($resultData)) {
    // FOREACH GRADE, FETCH ITS INFO
    $sqlGetRuleInfo = "SELECT * FROM rule WHERE ruleID = ?;";
    $stmtGetRuleInfo = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetRuleInfo, $sqlGetRuleInfo)) {
        echo ('{"error": "stmtFailed"}');
        exit();
    }
    mysqli_stmt_bind_param($stmtGetRuleInfo, "i", $row["ruleID"]);
    mysqli_stmt_execute($stmtGetRuleInfo);

    $resultInfoData = mysqli_stmt_get_result($stmtGetRuleInfo);
    if ($rowRuleInfo = mysqli_fetch_assoc($resultInfoData)) {
        $returnData .= '"' . $index . '":{"name":"' . $rowRuleInfo["ruleName"] . '","grade":"' . $row["grade"] . '"},';
    }

    mysqli_stmt_close($stmtGetRuleInfo);
    $index++;
}

$returnData = rtrim($returnData, ", ") . "}";

echo $returnData;
