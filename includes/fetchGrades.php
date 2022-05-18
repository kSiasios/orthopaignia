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


if (!isset($_POST["evaluationID"])) {
    echo '{"error": "userNotFound"}';
    exit();
}

$evaluationID = $_POST["evaluationID"];

$returnData = array();
$sql = "SELECT * FROM grades WHERE evaluationID = ?;";
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
    // FOREACH GRADE, RETURN ITS INFO
    array_push($returnData, $row);
}

array_push($returnData, '{"error": "none"}');
$jsonData = json_encode($returnData);

echo $jsonData;
