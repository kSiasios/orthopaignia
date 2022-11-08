<?php

session_start();

if (!isset($_SESSION['logged']) || !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
require_once "functions.php";

if (isset($_POST["multiple"])) {
    if ($_POST["multiple"] == "true") {
        // FETCH DATA FOR ALL THE USERS
        $sql = "SELECT * FROM users";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=noUsersFound");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        $responseData = array();

        while ($row = mysqli_fetch_assoc($resultData)) {
            array_push($responseData, json_decode(fetchUserData($conn, $row["userID"])));
        }

        echo json_encode($responseData);
        exit();
    }
}

echo fetchUserData($conn, $_POST["user"]);
exit();