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
        </div>
    </div>
    <script>
    let forQuiz = localStorage.getItem("quizProgress");

    if (forQuiz != null) {

        console.log(`forQuiz: ${forQuiz}`)
    } else {
        console.log(`forQuiz is null: ${forQuiz}`)
    }
    const questionResults = [];
    let questions;
    let currentQuestionIndex = 0;
    let score = 0;
    let counter = 0;
    let totalCounter = 0;

    let studyTime = 0;
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('studyTime')) {
        studyTime = parseFloat(urlParams.get('studyTime'));
    }
    setInterval(() => {
        counter++;
    }, 100);
    const quizContainer = document.querySelector(".quiz-container");
    fetchQuestions(forQuiz);

    function fetchQuestions(forQuiz) {
        const searchParams = new URLSearchParams();
        searchParams.append("submit", "submit");
        if (forQuiz !== "" && forQuiz !== null) {
            searchParams.append("quizID", forQuiz);
            fetchNeededData(searchParams);
        } else {
            console.log("No forQuiz... Fetching quiz")

            fetchQuizzes().then((res) => {

                const jsonArray = res;
                console.log(jsonArray);

                forQuiz = jsonArray[0].quizID;

                console.log("For quiz is: " + forQuiz);

                localStorage.setItem("quizProgress", forQuiz);

                searchParams.append("quizID", forQuiz);
                fetchNeededData(searchParams);
            })
        }

        function fetchNeededData(searchParams) {
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
    }

    async function populateQuiz(questionIndex) {
        // Call drag-drop function to initialize element references
        initializeElements();

        const questionsJSON = JSON.parse(questions);
        delete questionsJSON["error"];

        if (!Object.keys(questionsJSON)[questionIndex]) {

            await setQuestionGrades(questionResults);

            // If score is equal to the amount of questions fetched,
            // The player have answered all the questions correctly
            // Move on to the next quiz

            console.log(
                `Now check if the player has answered correctly. To do so score (${score}) must be equal to ${Object.keys(questionsJSON).length}`
            )

            if (score == Object.keys(questionsJSON).length) {

                const searchParams = new URLSearchParams();
                searchParams.append("submit", "submit");
                searchParams.append("quizID", localStorage.getItem("quizProgress"));
                fetch(`/${baseURL}/includes/completeGame.php`, {
                        method: "POST",
                        body: searchParams
                    }).then((res) => {
                        return res.text();
                    }).then((text) => {
                        const jsonResponse = JSON.parse(text);
                        if (jsonResponse.error == "none") {
                            if (jsonResponse.answer === "true") {
                                console.log("Was last quiz");

                                // REDIRECT TO END GAME
                                window.location = `/${baseURL}/routes/end_game.php`;
                                return;
                            } else {
                                console.log("Was NOT last quiz");
                                // INCREASE QUIZ PROGRESS

                                fetchQuizzes().then((res) => {

                                    const jsonArray = res;
                                    console.log(jsonArray);

                                    for (let index = 0; index < jsonArray.length; index++) {
                                        const element = jsonArray[index];
                                        console.log(
                                            `Is ${element.quizID} equal to ${localStorage.getItem("quizProgress")}?`
                                        );
                                        if (element.quizID == localStorage.getItem("quizProgress")) {
                                            console.log(`Yes, at index ${index}`);
                                        } else {
                                            console.log("No");
                                        }
                                        if (element.quizID == localStorage.getItem("quizProgress")) {
                                            localStorage.setItem("quizProgress", jsonArray[index + 1]
                                                .quizID);
                                            // RELOAD PAGE
                                            location.reload(true);
                                            return;
                                        }
                                    }
                                })
                            }
                        } else {
                            console.log("Unexpected error!");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                    })

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
        if (element["type"] == "drag-drop") {
            const confirmButton = document.createElement("button");
            confirmButton.classList.add("green");
            confirmButton.innerText = "Επόμενο";
            confirmButton.style.marginBlockEnd = "1em";
            confirmButton.style.marginInlineStart = "1em";
            confirmButton.addEventListener("click", () => {
                if (document.querySelector(".empty").innerHTML == "") {

                    sweetAlertWarning({
                        title: "Προσοχή!",
                        text: "Δεν έχει απαντήσει στην ερώτηση!",
                        confirmText: "Εντάξει",
                    });
                    return;
                }

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
        if (questionsJSON[questionIndex].type === "drag-drop") {
            const answerTemp = document.createElement("div");
            answerTemp.innerHTML = questionsJSON[questionIndex]["answer-0"];

            rightAnswer = answerTemp.innerText;
        }

        result.push(questionsJSON[questionIndex]["text"]);

        if (rightAnswer === answerText) {
            score++;

            result.push(1);
        } else {
            result.push(0);
        }

        questionResults.push(result);


        currentQuestionIndex++;

        populateQuiz(currentQuestionIndex);
        totalCounter += counter;
        counter = 0;
    }

    function setQuestionGrades(results) {
        return new Promise((resolve, reject) => {
            const searchParams = new URLSearchParams();
            searchParams.append("submit", "submit");
            searchParams.append("studyTime", studyTime);
            searchParams.append("results", JSON.stringify(questionResults));
            searchParams.append("totalTime", totalCounter);
            searchParams.append("quizID", localStorage.getItem("quizProgress"));

            resolve(fetch(`/${baseURL}/includes/setGrades.php`, {
                method: "POST",
                body: searchParams
            }).then((res) => {
                return res.text();
            }).then((text) => {
                const error = JSON.parse(text).error;
                switch (error) {
                    case "none":
                        console.log("Hooray! Grades set successfully!");
                        break;
                    default:
                        console.log("No-ray!");
                        break;
                }

            }).catch((error) => {
                console.log(error);
            }))
        });

    }

    function completeQuiz() {

        fetchQuizzes().then((res) => {

            const jsonResponse = res;
            console.log(jsonArray);
            console.log(`Gonna check if any of the above IDs match ${localStorage.getItem("quizProgress")}`)

            jsonResponse.forEach((element, index) => {
                if (parseInt(element.quizID) === parseInt(localStorage.getItem("quizProgress"))) {
                    console.log(`Found Quiz in the list at index: ${index}`);

                    if (jsonResponse[jsonResponse.length - 1] == element) {
                        console.log("Last Item");
                    }
                    if (jsonResponse.length > 1) {
                        localStorage.setItem("quizProgress", jsonResponse[index + 1].quizID);
                        location.reload();
                    }
                    return;
                }
            });

            console.log("Not matched!!!");
        })
    }

    function fetchQuizzes() {

        console.log("INSIDE fetchQuizzes()")

        return new Promise((resolve, reject) => {

            resolve(fetch(`/${baseURL}/includes/fetchQuizzes.php`)
                .then((res) => {
                    return res.text();
                }).then((text) => {
                    const error = JSON.parse(text).error;
                    return JSON.parse(text);
                }).catch((error) => {
                    console.log(error);
                }))
        });
    }
    </script>
    <script src="<?php echo $baseURL ?>/js/dragDropGame.js"></script>
    <?php include '../components/footer.php'; ?>