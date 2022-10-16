<?php
session_start();
$title = "Διαχείρηση";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">';

include '../header.php';

if (!isset($_SESSION["isAdmin"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}
?>

<body>
    <script src="<?php echo $baseURL ?>/js/fetchAdminData.js"></script>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="test-ep" style="margin-block: 2em; display: inline-flex; gap: 1em;">
            <button class="blue" onclick="window.location = 'test_endpoints.php';">Test Endpoints</button>
            <?php
            // echo "<button class='purple' onclick='exportSQLData()'>Export SQL Data</button>";
            ?>
        </div>
        <div class="admin-panel-container">
            <div class="user-data-container">
                <h2>Δεδομένα Χρηστών</h2>
                <div id="users-data"></div>
            </div>
            <div class="filter">
                <label for="filter-assets">Εμφάνηση</label>
                <select onchange="filterAssets()" name="filter-assets" id="filter-assets">
                    <option selected value="all">Όλα</option>
                    <!-- <option value="category">Κατηγορίες</option> -->
                    <option value="quiz">Αξιολογήσεις</option>
                    <option value="rule">Κανόνες</option>
                    <option value="question">Ερωτήσεις</option>
                </select>
            </div>
            <!-- <div class="categories">
                <h2>Κατηγορίες</h2>
                <div class="categories-container"></div>
                <button class="blue" onclick="window.location = 'add_category.php';">Προσθήκη κατηγορίας</button>
            </div> -->
            <div class="quizzes">
                <h2>Αξιολογήσεις</h2>
                <div class="quizzes-container"></div>
                <button class="blue" onclick="window.location = 'add_quiz.php';">Προσθήκη αξιολόγησης</button>
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

            <div class="db-functions">
                <h2>Λειτουργίες Βάσης Δεδομένων</h2>
                <div class="db-functions-buttons">
                    <div class="excel-exporter">
                        <h4>Εξαγωγή δεδομένων σε αρχείο Microsoft Excel</h4>
                        <button class="green-inverse" onclick="exportXLSX()">
                            Export to Excel
                            <ion-icon name="download-outline"></ion-icon>
                            <!-- <i class="fi fi-br-download"></i> -->
                        </button>
                    </div>
                    <div class="db-flusher">
                        <h4>Άδειασμα Βάσης Δεδομένων</h4>
                        <button class="red" onclick="flushDatabase()">
                            Άδειασμα Βάσης
                            <ion-icon name="trash"></ion-icon>
                            <!-- <ion-icon name="trash-outline"></ion-icon> -->
                            <!-- <i class="fi fi-bs-trash"></i> -->
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // const catsContainer = (document.querySelector(".categories").querySelector(".categories-container"));
        // const cats = (document.querySelector(".categories"));
        const quizzesContainer = (document.querySelector(".quizzes").querySelector(".quizzes-container"));
        const quizzes = (document.querySelector(".quizzes"));
        const rulesContainer = (document.querySelector(".rules").querySelector(".rules-container"));
        const rules = (document.querySelector(".rules"));
        const quesContainer = (document.querySelector(".questions").querySelector(".questions-container"));
        const ques = (document.querySelector(".questions"));
        const dropdown = document.querySelector("select");

        const usersContainer = document.querySelector("#users-data");

        function filterAssets() {
            switch (dropdown.value) {
                // case "category":
                //     cats.style.display = "block";
                //     rules.style.display = "none";
                //     ques.style.display = "none";
                //     break;
                case "quiz":
                    // cats.style.display = "block";
                    quizzes.style.display = "block";
                    rules.style.display = "none";
                    ques.style.display = "none";
                    break;
                case "rule":
                    // cats.style.display = "none";
                    quizzes.style.display = "none";
                    rules.style.display = "block";
                    ques.style.display = "none";
                    break;
                case "question":
                    // cats.style.display = "none";
                    quizzes.style.display = "none";
                    rules.style.display = "none";
                    ques.style.display = "block";
                    break;
                default:
                    // cats.style.display = "block";
                    quizzes.style.display = "block";
                    rules.style.display = "block";
                    ques.style.display = "block";
                    break;
            }
        }

        function deleteRule(index) {
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
                        location.reload();
                        break;
                    default:
                        break;
                }
            }).catch((error) => {
                console.log(error);
            });
        }

        // function deleteCategory(index) {
        //     console.log(`DELETING CATEGORY ${index}`);
        //     const searchParams = new URLSearchParams();

        //     searchParams.append("submit", "submit");
        //     searchParams.append("categoryID", index);

        //     fetch(`/${baseURL}/includes/deleteCategory.php`, {
        //         method: "POST",
        //         body: searchParams,
        //     }).then((res) => {
        //         return res.text();
        //     }).then((text) => {
        //         const error = text.split("=")[1];
        //         switch (error) {
        //             case "none":
        //                 location.reload();
        //                 break;
        //             default:
        //                 break;
        //         }
        //     }).catch((error) => {
        //         console.log(error);
        //     });
        // }
        function deleteQuiz(index) {
            console.log(`DELETING QUIZ ${index}`);
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            searchParams.append("quizID", index);

            fetch(`/${baseURL}/includes/deleteQuiz.php`, {
                method: "POST",
                body: searchParams,
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = text.split("=")[1];
                switch (error) {
                    case "none":
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

        function exportXLSX() {
            window.location = `/${baseURL}/includes/exportXLSX.php`;
        }

        // fetch(`/${baseURL}/includes/fetchCategories.php`).then((res) => {
        //     return res.text();
        // }).then((text) => {
        //     catsContainer.innerHTML = text;
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });
        fetch(`/${baseURL}/includes/fetchQuizzes.php`).then((res) => {
            return res.json();
        }).then((jsonArray) => {
            jsonArray.forEach(element => {
                // <div class='quiz'>
                //     <p class='quiz-name'>" . $row['quizTitle'] . "</p>
                //     <button class='red' onclick='deleteQuiz(" . $row['quizID'] . ")'>Διαγραφή</button>
                // </div>";
                const div = document.createElement("div");
                div.classList.add("quiz");

                const paragraph = document.createElement("p");
                paragraph.innerText = element.quizTitle;
                paragraph.classList.add("quiz-name");
                div.appendChild(paragraph);

                const delButton = document.createElement("button");
                delButton.classList.add("red");
                delButton.innerText = "Διαγραφή";
                delButton.addEventListener("click", () => {
                    deleteQuiz(element.quizID)
                });
                div.appendChild(delButton);

                quizzesContainer.appendChild(div);

            });
        }).catch((error) => {
            console.error(`${error}`);
        });

        // fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
        //     return res.text();
        // }).then((text) => {
        //     rulesContainer.innerHTML = text;
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });
        fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
            return res.json();
        }).then((jsonArray) => {
            jsonArray.forEach(element => {
                //"<div class='rule'>
                //      <p class='rule-name'>" . $row['ruleName'] . "</p>
                //      <button class='red' onclick='deleteRule(" . $row['ruleID'] . ")'>
                //          Διαγραφή
                //      </button>
                // </div>";

                const div = document.createElement("div");
                div.classList.add("rule");

                const paragraph = document.createElement("p");
                paragraph.innerText = element.ruleName;
                paragraph.classList.add("rule-name");
                div.appendChild(paragraph);

                const delButton = document.createElement("button");
                delButton.classList.add("red");
                delButton.innerText = "Διαγραφή";
                delButton.addEventListener("click", () => {
                    deleteRule(element.ruleID)
                });
                div.appendChild(delButton);

                rulesContainer.appendChild(div);

            });
        }).catch((error) => {
            console.error(`${error}`);
        });

        // fetch(`/${baseURL}/includes/fetchQuestions.php`).then((res) => {
        //     return res.text();
        // }).then((text) => {
        //     quesContainer.innerHTML = text;
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });
        fetch(`/${baseURL}/includes/fetchQuestions.php`).then((res) => {
            return res.json();
        }).then((jsonArray) => {

            jsonArray.forEach(element => {
                // "<div class='question'>
                // <p class='question-text'>" . $row['questionText'] . "</p>
                // <button class='red' onclick='deleteQuestion(" . $row['questionID'] . ")'>Διαγραφή</button>
                // </div>";
                const div = document.createElement("div");
                div.classList.add("question");

                const paragraph = document.createElement("p");
                paragraph.classList.add("question-text");
                paragraph.innerText = element.questionText.split("<")[0];

                const button = document.createElement("button");
                button.classList.add("red");
                button.addEventListener("click", () => {
                    deleteQuestion(element.questionID);
                })
                button.innerText = "Διαγραφή";

                div.appendChild(paragraph);
                div.appendChild(button);

                quesContainer.appendChild(div);
            });
        }).catch((error) => {
            console.error(`${error}`);
        });


        const searchParams = new URLSearchParams();
        searchParams.append("multiple", "true");

        fetch(`/${baseURL}/includes/fetchUserData.php`, {
            method: "POST",
            body: searchParams,
        }).then((res) => {
            // console.log(res.text());
            return res.text();
        }).then((text) => {
            const jsonArray = JSON.parse(text);
            console.log(jsonArray);
            jsonArray.forEach(element => {
                // CREATE LINK FOR THE USER
                const userLink = document.createElement("a");
                userLink.setAttribute("href", `/${baseURL}/routes/user_info.php?user=${element.user.userID}`);
                userLink.innerText = `${element.user.userFirstName} ${element.user.userLastName}`;
                userLink.classList.add("user-link");

                usersContainer.appendChild(userLink);
            });
        }).catch((error) => {
            console.error(`${error}`);
        });

        function exportSQLData() {
            window.location = `/${baseURL}/includes/exportSQLData.php`;
        }
    </script>
    <?php include '../components/footer.php'; ?>