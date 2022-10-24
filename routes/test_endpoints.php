<?php

// TO DELETE 

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
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="buttons" style="display: inline-flex; gap: 1em;">
            <button class="blue" onclick="userGrades()">Fetch User Grades</button>
            <!-- <button class="blue" onclick="sessionData()">Session Data</button>
            <button class="blue">Fetch Data 3</button>
            <button class="blue">Fetch Data 4</button>
            <button class="blue">Fetch Data 5</button> -->
        </div>
        <div id="fetched-data">
        </div>
    </div>
    <script>
        // const catsContainer = (document.querySelector(".categories").querySelector(".categories-container"));
        // const cats = (document.querySelector(".categories"));
        // const quizzesContainer = (document.querySelector(".quizzes").querySelector(".quizzes-container"));
        // const quizzes = (document.querySelector(".quizzes"));
        // const rulesContainer = (document.querySelector(".rules").querySelector(".rules-container"));
        // const rules = (document.querySelector(".rules"));
        // const quesContainer = (document.querySelector(".questions").querySelector(".questions-container"));
        // const ques = (document.querySelector(".questions"));
        // const dropdown = document.querySelector("select");

        // const usersContainer = document.querySelector("#users-data");


        function exportXLSX() {
            window.location = `/${baseURL}/includes/exportXLSX.php`;
        }

        function populatePage(data) {
            const dataContainer = document.querySelector("#fetched-data");

            dataContainer.innerHTML = data;
        }

        function fetchGrades() {

            console.log("No function")

            // // FETCH EVALUATIONS INCLUDING USER
            // //      FETCH GRADES FOR EACH EVALUATION

            // const searchParams = new URLSearchParams();
            // searchParams.append("submit", "submit");

            // fetch(`/${baseURL}/includes/`, {
            //     method: "POST",
            //     body: searchParams,
            // }).then((res) => {
            //     // console.log(res.text());
            //     return res.text();
            // }).then((text) => {
            //     const jsonArray = JSON.parse(text);
            //     console.log(jsonArray);
            //     jsonArray.forEach(element => {
            //         // CREATE LINK FOR THE USER
            //         const userLink = document.createElement("a");
            //         userLink.setAttribute("href", `/${baseURL}/routes/user_info.php?user=${element.user.userID}`);
            //         userLink.innerText = `${element.user.userFirstName} ${element.user.userLastName}`;
            //         userLink.classList.add("user-link");

            //         usersContainer.appendChild(userLink);
            //     });
            // }).catch((error) => {
            //     console.error(`${error}`);
            // });
        }

        function userGrades() {
            // const sessionData = [];
            // for (var i = 0; i < localStorage.length; i++) {
            //     console.log(`${localStorage.key(i)}: ${localStorage.getItem(localStorage.key(i))}`);
            // }

            // populatePage(sessionData);

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
                // console.log(jsonArray);
                let userID;
                jsonArray.forEach(element => {
                    // console.log("<?php echo $_SESSION['username'] ?>");
                    if (element.user.userUsername === "<?php echo $_SESSION['username'] ?>") {
                        userID = element.user.userID;

                        // fetch user grades
                        const searchParams2 = new URLSearchParams();
                        searchParams2.append("user", userID);
                        searchParams2.append("submit", "submit");

                        fetch(`/${baseURL}/includes/fetchGrades.php`, {
                            method: "POST",
                            body: searchParams2,
                        }).then((res) => {
                            // console.log(res.text());
                            return res.text();
                        }).then((text) => {
                            const jsonArray = JSON.parse(text);
                            console.log(jsonArray);

                        }).catch((error) => {
                            console.error(`${error}`);
                        });
                    }
                });
            }).catch((error) => {
                console.error(`${error}`);
            });
        }

        // fetch(`/${baseURL}/includes/fetchCategories.php`).then((res) => {
        //     return res.text();
        // }).then((text) => {
        //     catsContainer.innerHTML = text;
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });
        // fetch(`/${baseURL}/includes/fetchQuizzes.php`).then((res) => {
        //     return res.json();
        // }).then((jsonArray) => {
        //     jsonArray.forEach(element => {
        //         // <div class='quiz'>
        //         //     <p class='quiz-name'>" . $row['quizTitle'] . "</p>
        //         //     <button class='red' onclick='deleteQuiz(" . $row['quizID'] . ")'>Διαγραφή</button>
        //         // </div>";
        //         const div = document.createElement("div");
        //         div.classList.add("quiz");

        //         const paragraph = document.createElement("p");
        //         paragraph.innerText = element.quizTitle;
        //         paragraph.classList.add("quiz-name");
        //         div.appendChild(paragraph);

        //         const delButton = document.createElement("button");
        //         delButton.classList.add("red");
        //         delButton.innerText = "Διαγραφή";
        //         delButton.addEventListener("click", () => {
        //             deleteQuiz(element.quizID)
        //         });
        //         div.appendChild(delButton);

        //         quizzesContainer.appendChild(div);

        //     });
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });

        // // fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
        // //     return res.text();
        // // }).then((text) => {
        // //     rulesContainer.innerHTML = text;
        // // }).catch((error) => {
        // //     console.error(`${error}`);
        // // });
        // fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
        //     return res.json();
        // }).then((jsonArray) => {
        //     jsonArray.forEach(element => {
        //         //"<div class='rule'>
        //         //      <p class='rule-name'>" . $row['ruleName'] . "</p>
        //         //      <button class='red' onclick='deleteRule(" . $row['ruleID'] . ")'>
        //         //          Διαγραφή
        //         //      </button>
        //         // </div>";

        //         const div = document.createElement("div");
        //         div.classList.add("rule");

        //         const paragraph = document.createElement("p");
        //         paragraph.innerText = element.ruleName;
        //         paragraph.classList.add("rule-name");
        //         div.appendChild(paragraph);

        //         const delButton = document.createElement("button");
        //         delButton.classList.add("red");
        //         delButton.innerText = "Διαγραφή";
        //         delButton.addEventListener("click", () => {
        //             deleteRule(element.ruleID)
        //         });
        //         div.appendChild(delButton);

        //         rulesContainer.appendChild(div);

        //     });
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });

        // // fetch(`/${baseURL}/includes/fetchQuestions.php`).then((res) => {
        // //     return res.text();
        // // }).then((text) => {
        // //     quesContainer.innerHTML = text;
        // // }).catch((error) => {
        // //     console.error(`${error}`);
        // // });
        // fetch(`/${baseURL}/includes/fetchQuestions.php`).then((res) => {
        //     return res.json();
        // }).then((jsonArray) => {

        //     jsonArray.forEach(element => {
        //         // "<div class='question'>
        //         // <p class='question-text'>" . $row['questionText'] . "</p>
        //         // <button class='red' onclick='deleteQuestion(" . $row['questionID'] . ")'>Διαγραφή</button>
        //         // </div>";
        //         const div = document.createElement("div");
        //         div.classList.add("question");

        //         const paragraph = document.createElement("p");
        //         paragraph.classList.add("question-text");
        //         paragraph.innerText = element.questionText.split("<")[0];

        //         const button = document.createElement("button");
        //         button.classList.add("red");
        //         button.addEventListener("click", () => {
        //             deleteQuestion(element.questionID);
        //         })
        //         button.innerText = "Διαγραφή";

        //         div.appendChild(paragraph);
        //         div.appendChild(button);

        //         quesContainer.appendChild(div);
        //     });
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });

        // // fetch(`/${baseURL}/includes/fetchUsersDataForAdmin.php`).then((res) => {
        // //     return res.text();
        // // }).then((text) => {
        // //     const jsonArray = JSON.parse(text);
        // //     jsonArray.forEach(element => {
        // //         // CREATE LINK FOR THE USER
        // //         const userLink = document.createElement("a");
        // //         userLink.setAttribute("href", `/${baseURL}/routes/user_info.php?user=${element.ID}`);
        // //         userLink.innerText = `${element.firstName} ${element.lastName}`;
        // //         userLink.classList.add("user-link");

        // //         usersContainer.appendChild(userLink);
        // //     });
        // // }).catch((error) => {
        // //     console.error(`${error}`);
        // // });


        // const searchParams = new URLSearchParams();
        // searchParams.append("multiple", "true");

        // fetch(`/${baseURL}/includes/fetchUserData.php`, {
        //     method: "POST",
        //     body: searchParams,
        // }).then((res) => {
        //     // console.log(res.text());
        //     return res.text();
        // }).then((text) => {
        //     const jsonArray = JSON.parse(text);
        //     console.log(jsonArray);
        //     jsonArray.forEach(element => {
        //         // CREATE LINK FOR THE USER
        //         const userLink = document.createElement("a");
        //         userLink.setAttribute("href", `/${baseURL}/routes/user_info.php?user=${element.user.userID}`);
        //         userLink.innerText = `${element.user.userFirstName} ${element.user.userLastName}`;
        //         userLink.classList.add("user-link");

        //         usersContainer.appendChild(userLink);
        //     });
        // }).catch((error) => {
        //     console.error(`${error}`);
        // });
    </script>
    <?php include '../components/footer.php'; ?>