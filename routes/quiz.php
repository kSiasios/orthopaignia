<?php
session_start();
$title = "Quiz";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/quiz.css">
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
        <div class="quiz-container">
            <!-- <div class="quiz-question">
                <p>Ερώτηση</p>
            </div>
            <div class="quiz-answers">
                <div class="answer-container">
                    <a href="#" id="0">Απάντηση 1</a>
                </div>
                <div class="answer-container">
                    <a href="#" id="1">Απάντηση 1</a>
                </div>
                <div class="answer-container">
                    <a href="#" id="2">Απάντηση 1</a>
                </div>
                <div class="answer-container">
                    <a href="#" id="3">Απάντηση 1</a>
                </div>
            </div> -->
        </div>
    </div>
    <script>
        const ofRule = <?php if (isset($_GET["ruleID"])) {
                            echo "'" . $_GET["ruleID"] . "';\n";
                        } else {
                            echo "'';\n";
                        }
                        ?>;

        if (!localStorage.getItem("quizProgress")) {
            localStorage.setItem("quizProgress", 0);
        }

        const forQuiz = localStorage.getItem("quizProgress");

        const questionResults = [];
        let questions;
        let currentQuestionIndex = 0;
        let score = 0;
        let counter = 0;
        console.log(counter);
        setInterval(() => {
            counter++;
        }, 100);
        const quizContainer = document.querySelector(".quiz-container");
        // fetchQuestions(ofRule);
        fetchQuestions(forQuiz);

        // function fetchQuestions(ofRule) {
        //     const searchParams = new URLSearchParams();
        //     searchParams.append("submit", "submit");
        //     if (ofRule !== "") {
        //         searchParams.append("ruleID", ofRule);
        //     }

        //     fetch(`/${baseURL}/includes/fetchQuizQuestions.php`, {
        //         method: "POST",
        //         body: searchParams
        //     }).then((res) => {
        //         return res.text();
        //     }).then((text) => {
        //         questions = text;
        //         populateQuiz(currentQuestionIndex);

        //     }).catch((err) => {
        //         console.error(err);
        //     });
        // }
        function fetchQuestions(forQuiz) {
            const searchParams = new URLSearchParams();
            searchParams.append("submit", "submit");
            if (forQuiz !== "") {
                searchParams.append("quizID", forQuiz);
            }

            fetch(`/${baseURL}/includes/fetchQuizQuestions.php`, {
                method: "POST",
                body: searchParams
            }).then((res) => {
                return res.text();
            }).then((text) => {
                questions = text;
                populateQuiz(currentQuestionIndex);

            }).catch((err) => {
                console.error(err);
            });
        }

        function populateQuiz(questionIndex) {
            const questionsJSON = JSON.parse(questions);
            delete questionsJSON["error"];

            if (!Object.keys(questionsJSON)[questionIndex]) {
                quizContainer.innerHTML = "";
                let scoreElement = document.createElement("h2");
                scoreElement.innerText = `Το σκορ σου: ${score}`;
                quizContainer.appendChild(scoreElement);
                setQuestionGrades(questionResults);

                // If score is equal to the amount of questions fetched,
                // The player have answered all the questions correctly
                // Move on to the next quiz
                if (score == Object.keys(questionsJSON).length) {
                    sessionStorage.setItem("quizIndex", parseInt(sessionStorage.getItem("quizIndex")) + 1);
                }
                // Else, redirect to the corresponding rules

                return;
            }

            quizContainer.innerHTML = "";


            let wantedKey = Object.keys(questionsJSON)[questionIndex];

            const element = questionsJSON[wantedKey];

            const quizQuestion = document.createElement("div");
            quizQuestion.classList.add("quiz-question");
            const quizQuestionText = document.createElement("p");
            quizQuestionText.innerText = element.text;
            quizQuestion.appendChild(quizQuestionText);

            quizContainer.appendChild(quizQuestion);

            const quizAnswers = document.createElement("div");
            quizAnswers.classList.add("quiz-answers");

            const questionsArray = [];


            let index = 0;
            for (const elementKey in element) {
                if (Object.hasOwnProperty.call(element, elementKey)) {
                    const childElement = element[elementKey];
                    if (elementKey !== "text") {
                        const question = [];
                        question.push(elementKey, childElement);
                        questionsArray.push(question);
                    }
                }
            }

            const shuffledQuestions = questionsArray.sort((a, b) => 0.5 - Math.random());

            shuffledQuestions.forEach((elem) => {
                const answerContainer = document.createElement("div")
                answerContainer.classList.add("answer-container");
                const answer = document.createElement("a");
                answer.setAttribute("href", "#");
                answer.setAttribute("id", index);
                answer.addEventListener("click", (e) => {
                    answerQuestion(wantedKey, elem[1]);
                })
                answer.innerText = elem[1];
                answerContainer.appendChild(answer);
                quizAnswers.appendChild(answerContainer);

                index++;
            });

            quizContainer.appendChild(quizAnswers);
        }

        function answerQuestion(questionIndex, answerText) {
            const questionsJSON = JSON.parse(questions);

            const result = [];
            let rightAnswer = questionsJSON[questionIndex]["answer-0"];
            result.push(questionsJSON[questionIndex]["text"]);
            if (rightAnswer === answerText) {
                score++;

                result.push(1);
            } else {
                result.push(0);
            }

            questionResults.push(result);
            currentQuestionIndex++;
            console.log(counter / 10);
            populateQuiz(currentQuestionIndex);
            counter = 0;
        }

        function setQuestionGrades(results) {
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            searchParams.append("results", results.map((elem) => {
                return elem.join("~")
            }).join("|"));

            fetch(`/${baseURL}/includes/setQuestionsGrades.php`, {
                method: "POST",
                body: searchParams
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = JSON.parse(text).error;
                switch (error) {
                    case "none":
                        console.log("Hooray!");
                        break;
                    default:
                        console.log("No-ray!");
                        break;
                }
                console.log(text);
            }).catch((error) => {
                console.log(error);
            })
        }
    </script>
    <?php include '../components/footer.php'; ?>