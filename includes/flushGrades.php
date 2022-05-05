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


$sql = "DELETE FROM gradepercategory;";
$sql2 = "DELETE FROM gradeperquestion;";
$sql3 = "DELETE FROM gradeperrule;";

$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$stmt2 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}
mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);

$stmt3 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    echo ('{"error": "stmtFailed"}');
    exit();
}
mysqli_stmt_execute($stmt3);
mysqli_stmt_close($stmt3);
