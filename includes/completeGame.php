<?php

session_start();

if (!isset($_SESSION['logged'])) {
    header("location: " . $baseURL);
    exit();
}

require_once "db.info.php";

if (!isset($_POST["quizID"])) {
    echo '{"error": "noIDProvided"}';
}

$quizToCheck = $_POST["quizID"];

$sql = "SELECT * FROM quizzes ORDER BY quizID DESC;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($resultData)) {
    if ($row["quizID"] == $quizToCheck) {
        echo '{"error": "none", "answer": "true"}';
        exit();
    }
}
echo '{"error": "none", "answer": "false"}';
exit();
