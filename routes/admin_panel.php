<?php
session_start();
$title = "Διαχείρηση";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">';

include '../header.php';

if (!isset($_SESSION["isAdmin"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: $baseURL/");
    exit();
}
?>

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
                <div class="categories-container"></div>
                <button class="blue" onclick="window.location = 'add_category.php';">Προσθήκη κατηγορίας</button>
            </div>
            <div class="rules">
                <h2>Κανόνες</h2>
                <div class="rules-container"></div>
                <button class="blue" onclick="window.location = 'add_rule.php';">Προσθήκη κανόνα</button>
            </div>
            <div class="questions">
                <h2>Ερωτήσεις</h2>
                <div class="questions-container"></div>
                <button class="blue" onclick="window.location = 'add_question.php';">Προσθήκη ερώτησης</button>
            </div>

            <div class="flush-db">
                <h2>Λειτουργίες Βάσης Δεδομένων</h2>
                <button class="red" onclick="flushDatabase()">Άδειασμα Βάσης</button>
            </div>
        </div>
    </div>
    <script>
        const catsContainer = (document.querySelector(".categories").querySelector(".categories-container"));
        const cats = (document.querySelector(".categories"));
        const rulesContainer = (document.querySelector(".rules").querySelector(".rules-container"));
        const rules = (document.querySelector(".rules"));
        const quesContainer = (document.querySelector(".questions").querySelector(".questions-container"));
        const ques = (document.querySelector(".questions"));
        const dropdown = document.querySelector("select");

        function filterAssets() {
            // console.log(dropdown.value);
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

        function deleteRule(index) {
            // console.log(`DELETING RULE ${index}`);
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            searchParams.append("ruleID", index);

            fetch(`/${baseURL}/includes/deleteRule.php`, {
                method: "POST",
                body: searchParams,
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = text.split("=")[1];
                switch (error) {
                    case "none":
                        // console.log("Hooray!");
                        location.reload();
                        break;
                    default:
                        // console.log("Error!?");
                        break;
                }
            }).catch((error) => {
                console.log(error);
            });
        }

        function deleteCategory(index) {
            console.log(`DELETING CATEGORY ${index}`);
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            searchParams.append("categoryID", index);

            fetch(`/${baseURL}/includes/deleteCategory.php`, {
                method: "POST",
                body: searchParams,
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = text.split("=")[1];
                switch (error) {
                    case "none":
                        // console.log("Hooray!");
                        location.reload();
                        break;
                    default:
                        break;
                }
            }).catch((error) => {
                console.log(error);
            });
        }

        function deleteQuestion(index) {
            // console.log(`DELETING QUESTION ${index}`);
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            searchParams.append("questionID", index);

            fetch(`/${baseURL}/includes/deleteQuestion.php`, {
                method: "POST",
                body: searchParams,
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = text.split("=")[1];
                switch (error) {
                    case "none":
                        // console.log("Hooray!");
                        location.reload();
                        break;
                    default:
                        break;
                }
            }).catch((error) => {
                console.log(error);
            });
        }

        function flushDatabase() {
            console.log('FLUSHED');
            const searchParams = new URLSearchParams();
            searchParams.append("submit", "submit");
            fetch(`/${baseURL}/includes/flushGrades.php`, {
                method: "POST",
                body: searchParams
            }).then((res) => {
                location.reload();
            }).catch((error) => {
                console.error(`${error}`);
            });
        }

        fetch(`/${baseURL}/includes/fetchCategories.php`).then((res) => {
            return res.text();
        }).then((text) => {
            catsContainer.innerHTML = text;
            // console.log(text);
        }).catch((error) => {
            console.error(`${error}`);
        });

        fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
            return res.text();
        }).then((text) => {
            rulesContainer.innerHTML = text;
            // console.log(text);
        }).catch((error) => {
            console.error(`${error}`);
        });

        fetch(`/${baseURL}/includes/fetchQuestions.php`).then((res) => {
            return res.text();
        }).then((text) => {
            quesContainer.innerHTML = text;
            // console.log(text);
        }).catch((error) => {
            console.error(`${error}`);
        });
    </script>
    <?php include '../components/footer.php'; ?>