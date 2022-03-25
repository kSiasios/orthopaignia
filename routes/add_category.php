<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION["isAdmin"]))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    header("location: $baseURL/");
    exit();
}

$title = "Προσθήκη Κατηγορίας";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/add_form.css">
    ';
include '../header.php';
?>

<body>
    <!-- <script src="/sostografia/js/fetchAdminData.js"></script> -->
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="form-container">
            <form action="">
                <div class="form-section">
                    <label for="question-text">Όνομα Κατηγορίας</label>
                    <input type="text" name="question-text" id="question-text">
                </div>
                <div class="form-buttons">
                    <!-- <a class="button" onclick="addAnswerInput()">Προσθήκη Απάντησης</a> -->
                    <a class="button green" onclick="submitForm()">Ολοκλήρωση</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>