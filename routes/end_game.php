<?php
session_start();
$title = "Συγχαρητήρια";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/quiz.css">
    ';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

// require_once "../includes/functions.php";

?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <h2>Συχγαρητήρια!</h2>
        <p>Έσωσες τον Μπρόνκο!</p>
    </div>
    <?php include '../components/footer.php'; ?>