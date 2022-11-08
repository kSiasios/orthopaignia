<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

if (isset($_POST["quizIndex"])) {
    $sql = "SELECT * FROM rules WHERE quizID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=administratorsDeletionFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_POST["quizIndex"]);
} else {
    $sql = "SELECT * FROM rules;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$returnData = array();
while ($row = mysqli_fetch_assoc($resultData)) {
    array_push($returnData, $row);
}

$jsonData = json_encode($returnData);

echo $jsonData;
exit();