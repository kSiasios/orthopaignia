<?php

session_start();

if (!isset($_SESSION['logged']) || !isset($_SESSION['isAdmin'])) {
    // header("location: " . $baseURL);
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";

// if (!isset($_POST['submit'])) {
//     // header("location: " . $baseURL);
//     echo "error=notEnoughVariables";
//     exit();
// }

// echo $_GET['henlo'];
// echo $_GET['henlo'];
echo $_POST['submit'] . $_POST['user'];
