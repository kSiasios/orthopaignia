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
        echo ("error=emptyInput");
        exit();
    }

    if (invalidUID($username) !== false) {
        // THERE ARE EMPTY INPUTS
        echo ("error=invalidUsername");
        exit();
    }

    if (invalidEmail($email) !== false) {
        // THERE ARE EMPTY INPUTS
        echo ("error=invalidEmail");
        exit();
    }

    if (uidExists($conn, $username, $email) !== false) {
        // THERE ARE EMPTY INPUTS
        echo ("error=userExists");
        exit();
    }

    // echo '<script>console.log("CREATING USER");</script>';
    createUser($conn, $email, $username, $password);

    echo "error=none";

    // if ($row = uidExists($conn, $username, $username)) {
    //     // USER EXISTS => CHECK PASSWORD
    //     // logUserIn();
    //     logUserIn($conn, $password, $row["userPassword"]);
    // }
} else {
    echo ("error=accessDenied");
    exit();
}
