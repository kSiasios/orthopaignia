<?php

session_start();

if (!isset($_SESSION['logged'])) {
    header("location: " . $baseURL);
    exit();
}

if (isset($_POST['submit'])) {
    require_once "db.info.php";
    require_once "functions.php";

    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];

    $userID = getUserID($conn, $_SESSION["username"]);
    // CHECK IF USERS HAS TYPED THE CORRECT PREVIOUS PASSWORD
    $sql = "SELECT * FROM users WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $userID);

    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        if (password_verify($oldPassword, $row["userPassword"])) {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateSQL = "UPDATE users SET userPassword = ? WHERE userID = ?;";

            $stmtUpdate = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmtUpdate, $updateSQL)) {
                echo ("error=stmtFailed");
                exit();
            }

            mysqli_stmt_bind_param($stmtUpdate, "si", $hash, $row["userID"]);

            mysqli_stmt_execute($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);

            echo ("error=none");
        } else {
            echo ("error=wrongPassword");
            exit();
        }
    } else {
        echo ("error=userNotFound");
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    echo ("error=accessDenied");
    exit();
}