<?php
session_start();

if (!isset($_SESSION["isAdmin"])) {
    header("location: $baseURL/");
    exit();
}
$title = "Διαχείρηση";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">';
include '../header.php'; ?>

<body>
    <script src="<?php echo $baseURL ?>/js/fetchAdminData.js"></script>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="admin-panel-container">
            <div class="filter">
                <label for="filter-assets">Εμφάνηση</label>
                <select onchange="filterAssets()" name="filter-assets" id="filter-assets">
                    <option selected value="all">Όλα</option>
                    <option value="category">Κατηγορίες</option>
                    <option value="rule">Κανόνες</option>
                    <option value="question">Ερωτήσεις</option>
                </select>
            </div>
            <div class="categories">
                <h2>Κατηγορίες</h2>
                <button class="blue" onclick="window.location = 'add_category.php';">Προσθήκη κατηγορίας</button>
            </div>
            <div class="rules">
                <h2>Κανόνες</h2>
                <button class="red" onclick="window.location = 'add_rule.php';">Προσθήκη κανόνα</button>
            </div>
            <div class="questions">
                <h2>Ερωτήσεις</h2>
                <button class="green" onclick="window.location = 'add_question.php';">Προσθήκη ερώτησης</button>
            </div>
        </div>
    </div>
    <script>
        const cats = (document.getElementsByClassName("categories")[0]);
        const rules = (document.getElementsByClassName("rules")[0]);
        const ques = (document.getElementsByClassName("questions")[0]);
        const dropdown = document.querySelector("select");

        function filterAssets() {
            console.log(dropdown.value);
            switch (dropdown.value) {
                case "category":
                    cats.style.display = "block";
                    rules.style.display = "none";
                    ques.style.display = "none";
                    break;
                case "rule":
                    cats.style.display = "none";
                    rules.style.display = "block";
                    ques.style.display = "none";
                    break;
                case "question":
                    cats.style.display = "none";
                    rules.style.display = "none";
                    ques.style.display = "block";
                    break;
                default:
                    cats.style.display = "block";
                    rules.style.display = "block";
                    ques.style.display = "block";
                    break;
            }
        }
    </script>
</body>

</html>