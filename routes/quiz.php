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
        const forQuiz = localStorage.getItem("quizProgress");

        console.log(`forQuiz: ${forQuiz}`)

        const questionResults = [];
        let questions;
        let currentQuestionIndex = 0;
        let score = 0;
        let counter = 0;
        // console.log(counter);
        setInterval(() => {
            counter++;
        }, 100);
        const quizContainer = document.querySelector(".quiz-container");
        fetchQuestions(forQuiz);

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
                questions = replaceSpecialCharacters(text);
                populateQuiz(currentQuestionIndex);
            }).catch((err) => {
                console.error(err);
            });
        }

        function populateQuiz(questionIndex) {
            // Call drag-drop function to initialize element references
            initializeElements();

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
                    return;
                }
                // Else, redirect to the corresponding rules
                window.location = `/${baseURL}/routes/rules.php?index=${localStorage.getItem("quizProgress")}`;
                return;
            }

            quizContainer.innerHTML = "";


            let wantedKey = Object.keys(questionsJSON)[questionIndex];

            const element = questionsJSON[wantedKey];

            const quizQuestion = document.createElement("div");
            quizQuestion.classList.add("quiz-question");
            const quizQuestionText = document.createElement("p");
            quizQuestionText.innerHTML = element.text;
            quizQuestion.appendChild(quizQuestionText);

            quizQuestionText.addEventListener("click", (event) => {
                responsiveVoice.speak(`${event.target.innerText}`, "Greek Female");
            });
            quizQuestionText.style.cursor = "help";

            quizContainer.appendChild(quizQuestion);

            const quizAnswers = document.createElement("div");
            quizAnswers.classList.add("quiz-answers");

            const answersArray = [];


            let index = 0;
            for (const elementKey in element) {
                if (Object.hasOwnProperty.call(element, elementKey)) {
                    const childElement = element[elementKey];
                    if (elementKey !== "text" && elementKey !== "type") {
                        const answer = [];
                        answer.push(elementKey, childElement);
                        answersArray.push(answer);
                    }
                }
            }

            const shuffledAnswers = answersArray.sort((a, b) => 0.5 - Math.random());

            shuffledAnswers.forEach((elem) => {
                const answerContainer = document.createElement("div")
                answerContainer.classList.add("answer-container");
                let answer;

                if (element["type"] == "drag-drop") {
                    answer = new DOMParser().parseFromString(elem[1], "text/html").body.firstElementChild;
                    quizAnswers.appendChild(answer);
                    index++;
                }
                if (element["type"] == "multiple-choice") {
                    answer = document.createElement("a");
                    answer.setAttribute("href", "#");
                    answer.setAttribute("id", index);
                    answer.addEventListener("click", (e) => {
                        answerQuestion(wantedKey, elem[1]);
                    })
                    answer.innerHTML = elem[1];
                    answerContainer.appendChild(answer);
                    quizAnswers.appendChild(answerContainer);

                    index++;
                }

            });


            quizContainer.appendChild(quizAnswers);

            // if the question is drag drop, add button to confirm answer
            if (element["type"] == "drag-drop") {
                const confirmButton = document.createElement("button");
                confirmButton.classList.add("green");
                confirmButton.innerText = "LOLOLOLOLO";
                confirmButton.addEventListener("click", () => {
                    if (document.querySelector(".empty").innerHTML == "") {
                        // console.log(`Current Answer: No answer`);
                        sweetAlertWarning({
                            title: "Προσοχή!",
                            text: "Δεν έχει απαντήσει στην ερώτηση!",
                            confirmText: "Εντάξει",
                        });
                        return;
                    }
                    // console.log(`Current Answer: ${currentAnswer.innerText}`);
                    answerQuestion(wantedKey, currentAnswer.innerText);
                });

                quizContainer.appendChild(confirmButton);
            }
        }

        function answerQuestion(questionIndex, answerText) {
            const questionsJSON = JSON.parse(questions);

            const result = [];
            let rightAnswer;

            rightAnswer = questionsJSON[questionIndex]["answer-0"];

            // console.log(questionsJSON[questionIndex])
            if (questionsJSON[questionIndex].type === "drag-drop") {
                const answerTemp = document.createElement("div");
                answerTemp.innerHTML = questionsJSON[questionIndex]["answer-0"];
                // rightAnswer = questionsJSON[questionIndex]["answer-0"];
                rightAnswer = answerTemp.innerText;
                // document.body.appendChild(answerTemp);
                // console.log(answerTemp.innerText);
            }

            result.push(questionsJSON[questionIndex]["text"]);
            // console.log(`Comparison: ${rightAnswer} === ${answerText}`);
            if (rightAnswer === answerText) {
                score++;

                result.push(1);
            } else {
                result.push(0);
            }

            questionResults.push(result);

            // console.log("Result: \n");
            // console.log(questionResults);

            currentQuestionIndex++;
            // console.log(counter / 10);
            populateQuiz(currentQuestionIndex);
            counter = 0;
        }

        function setQuestionGrades(results) {
            const searchParams = new URLSearchParams();

            searchParams.append("submit", "submit");
            // searchParams.append("results", results.map((elem) => {
            //     return elem.join("~")
            // }).join("|"));
            searchParams.append("results", JSON.stringify(questionResults));

            // console.log(JSON.stringify(questionResults));

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
    <script src="<?php echo $baseURL ?>/js/dragDropGame.js"></script>
    <?php include '../components/footer.php'; ?>