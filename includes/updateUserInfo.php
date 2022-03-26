<?php

session_start();

if (!isset($_SESSION['logged'])) {
    header("location: " . $baseURL);
    exit();
}

if (isset($_POST['submit'])) {
    require_once "db.info.php";

    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];

    $sql = "UPDATE users SET userFirstName = ?, userLastName = ? WHERE userUsername = ?";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $firstName, $lastName, $_SESSION["username"]);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION["firstname"] = $firstName;
    $_SESSION["lastname"] = $lastName;

    echo ("error=none");
} else {
    echo ("error=accessDenied");
    exit();
}
