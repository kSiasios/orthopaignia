<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}
require_once "db.info.php";

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

$filename = "data_" . date('d-m-Y') . ".xls";

$fields = array("ID", "First Name", "Last Name", "Email", "Username");

$excelData = implode("\t", array_values($fields)) . "\n";

// FETCH USERDATA FROM DATABASE
$sql = "SELECT * FROM users ORDER BY userID ASC;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($resultData)) {
    $lineData = array($row["userID"], $row["userFirstName"], $row["userLastName"], $row["userEmail"], $row["userUsername"]);
    array_walk($lineData, 'filterData');
    $excelData .= implode("\t", array_values($lineData)) . "\n";
}

mysqli_stmt_close($stmt);


// HEADERS FOR DOWNLOADING
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

echo $excelData;

exit;
