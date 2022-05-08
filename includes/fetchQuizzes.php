<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

$sql = "SELECT * FROM quizzes;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$returnData = array();
while ($row = mysqli_fetch_assoc($resultData)) {
    // $returnTxt = $returnTxt . "<div class='quiz'><p class='quiz-name'>" . $row['quizTitle'] . "</p><button class='red' onclick='deleteQuiz(" . $row['quizID'] . ")'>Διαγραφή</button></div>";
    array_push($returnData, $row);
    // $returnData
}

$jsonData = json_encode($returnData);

// echo $returnTxt;
echo $jsonData;
exit();
