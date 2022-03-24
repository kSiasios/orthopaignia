<?php

require_once "db.info.php";
require_once "functions.php";

if (isset($_POST['submit'])) {
    // echo ($_POST["username"]);
    // CHECK IF USER EXISTS
    if ($row = uidExists($conn, $_POST["username"], $_POST["username"])) {
        // USER EXISTS => CHECK PASSWORD
        if (password_verify($_POST["password"], $row["userPassword"])) {
            session_start();
            $_SESSION['logged'] = time();

            // CHECK IF USER IS ADMIN
            $sql = "SELECT * FROM administrators WHERE (SELECT userID FROM users WHERE userUsername = ? OR userEmail = ?) = administrators.userID;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: ../?error=adminFetchFailed");
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['username']);
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($resultData)) {
                $_SESSION['isAdmin'] = true;
            }

            // GET USER'S USERNAME (MIGHT HAVE LOGGED IN USING EMAIL)
            // CHECK IF USER IS ADMIN
            $sql = "SELECT userUsername, userFirstName FROM users WHERE userUsername = ? OR userEmail = ?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: ../?error=adminFetchFailed");
                exit();
            }
            mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['username']);
            mysqli_stmt_execute($stmt);

            $resultData = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($resultData)) {
                // if ($row["userFirstName"] === "")
                //     $_SESSION['username'] = $row['userUsername'];
                // else
                //     $_SESSION['username'] = $row['userFirstName'];

                $_SESSION['username'] = $row['userUsername'];
            }

            print_r($_SESSION);
        } else {
            header("location: ../?error=wrongPassword");
            exit();
        }
    }
} else {
    header("location: ../");
}