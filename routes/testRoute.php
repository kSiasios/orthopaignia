<?php
session_start();
$title = "Quiz";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/test.css">
    ';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: " . $baseURL . "/");
    exit();
}

require_once "../includes/functions.php";

$message = rand(0, 10);
$encryptedMessage = encrypt($message, $alphabet, $letters);
$decryptedMessage = decrypt($encryptedMessage, $alphabet, $letters);
// $encryptedMessage = encrypt($_SESSION["username"], $alphabet, $letters);
// $decryptedMessage = decrypt($encryptedMessage, $alphabet, $letters);
echo "<script>console.log('Default Message: $message')</script>";
echo "<script>console.log('Encrypted Message: $encryptedMessage')</script>";
echo "<script>console.log('Decrypted Message: $decryptedMessage')</script>";

?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="question">
            <div class="question-text">Ποιά είναι η σωστή κατάληξη;</div>
            <div class="answer">
                <div class="answer-container">
                    ο στρατιώτ
                    <div class="empty hovered">
                    </div>
                </div>
            </div>
        </div>
        <div class="potential-answers">
            <div class="special-text-1 potential-answer" draggable="true">εις</div>
            <div class="special-text-2 potential-answer" draggable="true">οις</div>
            <div class="special-text-3 potential-answer" draggable="true">ης</div>
            <div class="special-text-4 potential-answer" draggable="true">ις</div>
            <div class="special-text-5 potential-answer" draggable="true">υς</div>
        </div>
    </div>
    <script src="<?php echo $baseURL ?>/js/dragDropGame.js"></script>
    <?php include '../components/footer.php'; ?>