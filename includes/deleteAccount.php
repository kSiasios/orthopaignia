<?php
session_start();

require_once "db.info.php";
require_once "functions.php";

if (isset($_SESSION['logged']) && isset($_SESSION['username'])) {
    // CHECK IF USER EXISTS
    if ($row = uidExists($conn, $_SESSION["username"], $_SESSION["username"])) {
        // USER EXISTS => DELETE USER
        // // DELETE GRADES
        $sql = "DELETE FROM gradepercategory WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=gradepercategoryDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        $sql = "DELETE FROM gradeperquestion WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=gradeperquestionDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        // // DELETE FROM ADMINISTRATORS
        $sql = "DELETE FROM administrators WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=administratorsDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        // // FINALLY DELETE FROM USERS
        $sql = "DELETE FROM users WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=administratorsDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        session_unset();
        session_destroy();

        echo ("error=none");
        exit();
    }
} else {
    echo ("error=notLoggedOrNoUsername");
}
