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

$returnData = '{"error": "none",';
$sql = "SELECT * FROM gradeperquestion WHERE userID = ?;";
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
    $sqlGetQueInfo = "SELECT * FROM questions WHERE questionID = ?;";
    $stmtGetQueInfo = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetQueInfo, $sqlGetQueInfo)) {
        echo ('{"error": "stmtFailed"}');
        exit();
    }
    mysqli_stmt_bind_param($stmtGetQueInfo, "i", $row["questionID"]);
    mysqli_stmt_execute($stmtGetQueInfo);

    $resultInfoData = mysqli_stmt_get_result($stmtGetQueInfo);
    if ($rowQueInfo = mysqli_fetch_assoc($resultInfoData)) {
        $returnData .= '"' . $index . '":{"name":"' . $rowQueInfo["questionText"] . '","grade":"' . $row["grade"] . '"},';
    }

    mysqli_stmt_close($stmtGetQueInfo);
    $index++;
}

$returnData = rtrim($returnData, ", ") . "}";

echo $returnData;
