<?php
session_start();
$title = "Συγχαρητήρια";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/quiz.css">
    <link rel="stylesheet" href="../css/end_game.css">
    ';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content"
        style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <h2 style="text-align: center;">Συχγαρητήρια!</h2>
        <p>Ο Μπρόνκο είναι χαρούμενος!</p>
        <div class="bronco fade-in">
            <img src="<?php echo $baseURL ?>/svg/Bronco Happy.svg" alt="" srcset="">
        </div>
        <div class="confetti-container">
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
            <span class="confetti"></span>
        </div>
    </div>
    <?php include '../components/footer.php'; ?>