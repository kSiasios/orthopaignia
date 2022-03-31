<?php
session_start();
$title = "Λογαριασμός";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/quiz.css">
    ';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: " . $baseURL . "/");
    exit();
}
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">

    </div>
    <?php include '../components/footer.php'; ?>