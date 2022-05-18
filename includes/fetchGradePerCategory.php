<!-- TO DELETE -->

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

$userID = getUserID($conn, $_POST['user']);

if (!$userID) {
    echo '{"error": "userNotFound"}';
    exit();
}

mysqli_stmt_close($stmtGetUserID);

$returnData = '{"error": "none",';
$sql = "SELECT * FROM gradepercategory WHERE userID = ?;";
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

    $sqlGetCatInfo = "SELECT * FROM category WHERE categoryID = ?;";
    $stmtGetCatInfo = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetCatInfo, $sqlGetCatInfo)) {
        echo ('{"error": "stmtFailed"}');
        exit();
    }
    mysqli_stmt_bind_param($stmtGetCatInfo, "i", $row["categoryID"]);
    mysqli_stmt_execute($stmtGetCatInfo);

    $resultInfoData = mysqli_stmt_get_result($stmtGetCatInfo);
    if ($rowCatInfo = mysqli_fetch_assoc($resultInfoData)) {
        $returnData .= '"' . $index . '":{"name":"' . $rowCatInfo["categoryName"] . '","grade":"' . $row["grade"] . '"},';
    }

    mysqli_stmt_close($stmtGetCatInfo);
}

$returnData = rtrim($returnData, ", ") . "}";

echo $returnData;
