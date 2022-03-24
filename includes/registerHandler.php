<?php

if (isset($_POST['submit'])) {
    // echo ("Username: " . $_POST["username"]);
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    require_once "db.info.php";
    require_once "functions.php";

    if (emptyInputRegister($username, $email, $password) !== false) {
        // THERE ARE EMPTY INPUTS
        header("location: ../?error=emptyInput");
        exit();
    }

    if (invalidUID($username) !== false) {
        // THERE ARE EMPTY INPUTS
        header("location: ../?error=invalidUsername");
        exit();
    }

    if (invalidEmail($email) !== false) {
        // THERE ARE EMPTY INPUTS
        header("location: ../?error=invalidEmail");
        exit();
    }

    if (uidExists($conn, $username, $email) !== false) {
        // THERE ARE EMPTY INPUTS
        header("location: ../?error=invalidEmail");
        exit();
    }

    createUser($conn, $email, $username, $password);
} else {
    header("location: ../");
    exit();
}