<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION['isAdmin']))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    header("location: /sostografia/");
    exit();
}

$title = "Προσθήκη Κανόνα";
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
                    <label for="question-text">Όνομα Κανόνα</label>
                    <input type="text" name="question-text" id="question-text">
                </div>
                <!-- DROPDOWN TO SELECT CORRESPONDING CATEGORY -->
                <!-- DYNAMIC SECTIONS THAT HAVE A TEXT SECTION EXPLAINING THE RULE AND AN EXAMPLE SECTION 
                FOR POTENTIAL EXAMPLES -->
                <div class="form-section">
                    <h3>Τμήμα Κανόνα</h3>
                    <label for="rule-section-1-text">Κείμενο Τμήματος</label>
                    <input type="text" name="rule-section-1-text" class="rule-section-text" key="1">
                    <label for="rule-section-1-example">Παράδειγμα Τμήματος</label>
                    <input type="text" name="rule-section-1-example" class="rule-section-example" key="1">
                </div>
                <div class="form-buttons">
                    <a class="button" onclick="addSectionInput()">Προσθήκη Τμήματος</a>
                    <a class="button green" onclick="submitForm()">Ολοκλήρωση</a>
                </div>
            </form>
        </div>
        <div class="format-helper">
            <table>
                <tr>
                    <td>!!κείμενο!! <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-1">κείμενο</td>
                </tr>
                <tr>
                    <td>**κείμενο** <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-2">κείμενο</td>
                </tr>
                <tr>
                    <td>$κείμενο$ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-3">κείμενο</td>
                </tr>
                <tr>
                    <td>#κείμενο# <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-4">κείμενο</td>
                </tr>
                <tr>
                    <td>%κείμενο% <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-5">κείμενο</td>
                </tr>
                <tr>
                    <td>^κείμενο^ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-6">κείμενο</td>
                </tr>
                <tr>
                    <td>&κείμενο& <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-7">κείμενο</td>
                </tr>
                <tr>
                    <td>@κείμενο@ <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-8">κείμενο</td>
                </tr>
                <tr>
                    <td>?κείμενο? <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-9">κείμενο</td>
                </tr>
                <tr>
                    <td>[κείμενο] <i class="fi fi-rr-arrow-right"></i></td>
                    <td class="special-text-10 test">κείμενο</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>