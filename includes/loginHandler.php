<?php

require_once "db.info.php";
require_once "functions.php";

if (isset($_POST['submit'])) {
    // echo ($_POST["username"]);
    // CHECK IF USER EXISTS
    if ($row = uidExists($conn, $_POST["username"], $_POST["username"])) {
        // USER EXISTS => CHECK PASSWORD
        // logUserIn();
        logUserIn($conn, $_POST["password"], $row["userPassword"]);
        echo "error=none";
    } else {
        echo "error=userDoesNotExist";
    }
} else {
    header("location: ../");
}
