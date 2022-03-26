<?php
session_start();
$title = "Κανόνες";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/category.css">';

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
        <div class="rule-container">
            <div class="rule-header">
                <p>1ος Κανόνας</p>
            </div>
            <div class="rule">
                <div class="rule-section">
                    <p class="rule-text">
                        Τα αρσενικά που τελειώνουν σε -ης, γράγονται με ήτα. (η)
                    </p>
                    <div class="rule-example">
                        <p class="rule-example-header">
                            Παράδειγμα
                        </p>
                        <p class="example">
                            ο στρατιώτης<br />
                            ο σπουδαστής
                        </p>
                    </div>
                </div>
            </div>
            <div class="rule">
                <div class="rule-section">
                    <p class="rule-text">
                        Τα θηλυκά που τελειώνουν σε -η, γράγονται με ήτα. (η)
                    </p>
                    <div class="rule-example">
                        <p class="rule-example-header">
                            Παράδειγμα
                        </p>
                        <p class="example">
                            η βρύση<br />
                            η γιορτή
                        </p>
                    </div>
                </div>
            </div>
            <div class="buttons-container">
                <div class="prev-button">
                    <button class="blue"><i class="fi fi-rr-angle-left"></i> Προηγούμενο</button>
                </div>
                <div class="next-buttons">
                    <button class="inverse">Παράβλεψη <i class="fi fi-rr-angle-double-right"></i></button>
                    <button class="blue">Επόμενο <i class="fi fi-rr-angle-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>