<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}
require_once "db.info.php";
require_once "functions.php";

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

$filename = "orthopaignia_data_" . date('d-m-Y') . ".xls";

$fields = array("ID", "Όνομα", "Επώνυμο", "Email", "Όνομα Χρήστη", "Εκπαίδευση");

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
    // FETCH DATA FOR EACH INDIVIDUAL
    $lineData = array(
        $row["userID"],
        decrypt($row["userFirstName"]),
        decrypt($row["userLastName"]),
        decrypt($row["userEmail"]),
        decrypt($row["userUsername"]),
        convertEducationToReadable($row["userEducation"])
    );
    array_walk($lineData, 'filterData');
    $excelData .= implode("\t", array_values($lineData)) . "\n";
}

mysqli_stmt_close($stmt);


// HEADERS FOR DOWNLOADING
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

echo $excelData;

exit;
