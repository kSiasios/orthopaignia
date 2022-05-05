<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
require_once "functions.php";

if (!isset($_POST['submit']) || !isset($_POST['ruleID'])) {
    echo "error=notEnoughVariables";
    exit();
}

$ruleID = $_POST['ruleID'];

deleteRulesFunction($conn, $ruleID);

echo "error=none";
