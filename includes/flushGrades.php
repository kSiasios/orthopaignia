<?php

session_start();

if (!isset($_SESSION['logged']) || !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
if (!isset($_POST['submit'])) {
    echo "error=notEnoughVariables";
    exit();
}
$sql = "DELETE FROM grades;";

$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
