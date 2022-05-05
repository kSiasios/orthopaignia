<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

$sql = "SELECT * FROM questions;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($resultData)) {
    $returnTxt = $returnTxt . "<div class='question'><p class='question-text'>" . $row['questionText'] . "</p><button class='red' onclick='deleteQuestion(" . $row['questionID'] . ")'>Διαγραφή</button></div>";
}

echo $returnTxt;
exit();
