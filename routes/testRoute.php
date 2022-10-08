<!-- TO DELETE -->

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
    exit();
}

require_once "../includes/functions.php";

?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="question">
            <div class="question-text">Ποιά είναι η σωστή κατάληξη;</div>
            <div class="answer">
                <div class="answer-container">
                    ο στρατιώτ
                    <div class="empty">
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
        <div style="background: #eee; padding: 1em; border-radius: 1em; display: flex; flex-direction: column; gap: 1em;">
            <button class="blue">Επόμενο</button>
            <button class="green">Επιβεβαίωση</button>
            <!-- <button class="yellow"></button> -->
            <button class="red">Ακύρωση</button>
            <button>Απλό Κουμπί</button>
            <button class="inverse">Αντίστροφο Κουμπί</button>
        </div>
        <!-- <div class="confetti-container">
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
            <span class="confetti"></span>
            <span class="confetti"></span>
        </div> -->
    </div>
    <script src="<?php echo $baseURL ?>/js/dragDropGame.js"></script>
    <?php include '../components/footer.php'; ?>