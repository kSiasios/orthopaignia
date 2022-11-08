<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION['isAdmin']))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

$title = "Προσθήκη Ερώτησης";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/add_form.css">
    ';
include '../header.php';
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="form-wrapper">

            <div class="form-container">
                <label for="choose-question-type">Τύπος Ερώτησης</label>
                <select name="choose-question-type" id="choose-question-type" onchange="changeQuestionType()">
                    <option value="multiple-choice">Multiple Choice</option>
                    <option value="drag-drop" selected>Drag n' Drop</option>
                </select>
                <form action="" id="form-multiple-choice">
                    <div class="form-section">
                        <label for="question-text">Ερώτηση</label>
                        <input type="text" name="question-text" id="question-text">
                    </div>
                    <div class="form-section">
                        <label for="question-quiz">Αντίστοιχη Αξιολόγηση</label>
                        <select name="question-quiz" id="question-quiz">
                            <option value="" selected disabled>Επιλέξτε Αξιολόγηση</option>
                        </select>
                    </div>
                    <div class="form-section">
                        <label for="right-answer">Σωστή Απάντηση</label>
                        <input type="text" name="right-answer-text" id="right-answer">
                    </div>
                    <div class="form-section">
                        <label for="wrong-answer-1">Λάθος Απάντηση</label>
                        <input type="text" name="wrong-answer-1-text" class="wrong-answer" key="1">
                    </div>
                    <div class="form-buttons">
                        <a class="button" href="#" onclick="addAnswerInput()">Προσθήκη Απάντησης</a>
                        <a class="button green" href="#" onclick="submitForm()">Ολοκλήρωση</a>
                    </div>
                </form>
                <form action="" id="form-drag-drop" style="display: none;">
                    <div class="form-section">
                        <label for="question-text">Ερώτηση</label>
                        <input type="text" name="question-text" id="question-text">
                    </div>
                    <div class="form-section">
                        <label for="question-answer-text">Δεδομένο Σκέλος Απάντησης</label>
                        <input type="text" name="question-answer-text" id="question-answer-text">
                    </div>
                    <div class="form-section">
                        <label for="question-quiz">Αντίστοιχη Αξιολόγηση</label>
                        <select name="question-quiz" id="question-quiz">
                            <option value="" selected disabled>Επιλέξτε Αξιολόγηση</option>
                        </select>
                    </div>
                    <div class="form-section">
                        <label for="right-answer">Σωστή Απάντηση</label>
                        <input type="text" name="right-answer-text" id="right-answer">
                    </div>
                    <div class="form-section">
                        <label for="wrong-answer-1">Λάθος Απάντηση</label>
                        <input type="text" name="wrong-answer-1-text" class="wrong-answer" key="1">
                    </div>
                    <div class="form-buttons">
                        <a class="button" href="#" onclick="addAnswerInput()">Προσθήκη Απάντησης</a>
                        <a class="button green" href="#" onclick="submitForm()">Ολοκλήρωση</a>
                    </div>
                </form>
            </div>
            <div class="format-helper">
                <table>
                    <tr>
                        <td style="letter-spacing: 0.3em">___ <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="empty"></td>
                    </tr>
                    <tr>
                        <td>!!κείμενο/!! <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-1">κείμενο</td>
                    </tr>
                    <tr>
                        <td>**κείμενο/** <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-2">κείμενο</td>
                    </tr>
                    <tr>
                        <td>$κείμενο/$ <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-3">κείμενο</td>
                    </tr>
                    <tr>
                        <td>#κείμενο/# <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-4">κείμενο</td>
                    </tr>
                    <tr>
                        <td>%κείμενο/% <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-5">κείμενο</td>
                    </tr>
                    <tr>
                        <td>^κείμενο/^ <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-6">κείμενο</td>
                    </tr>
                    <tr>
                        <td>&κείμενο/& <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-7">κείμενο</td>
                    </tr>
                    <tr>
                        <td>@κείμενο/@ <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-8">κείμενο</td>
                    </tr>
                    <tr>
                        <td>?κείμενο/? <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-9">κείμενο</td>
                    </tr>
                    <tr>
                        <td>[κείμενο/] <ion-icon name='arrow-forward'></ion-icon>
                        </td>
                        <td class="special-text-10 test">κείμενο</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script src="<?php echo $baseURL ?>/js/textFormat.js"></script>
    <script>
    let formButtons;
    const multipleChoiceForm = document.querySelector("#form-multiple-choice");
    const dragDropForm = document.querySelector("#form-drag-drop");

    let selectQuiz;

    changeQuestionType();

    function addAnswerInput(questionType) {
        let form;
        if (questionType == "multiple-choice") {
            form = multipleChoiceForm;
            formButtons = multipleChoiceForm.querySelector(".form-buttons");
        } else {
            form = dragDropForm;
            formButtons = dragDropForm.querySelector(".form-buttons");
        }
        let inputElement = document.createElement("input");
        let labelElement = document.createElement("label");

        let wrongAnswersCount = form.querySelectorAll(".wrong-answer").length;

        inputElement.setAttribute("type", "text");
        inputElement.classList.add("wrong-answer");
        inputElement.setAttribute("key", wrongAnswersCount + 1);
        inputElement.setAttribute("name", `wrong-answer-${wrongAnswersCount + 1}-text`);

        labelElement.setAttribute("for", `wrong-answer-${wrongAnswersCount + 1}-text`);
        labelElement.innerText = `Λάθος Απάντηση`;

        formButtons.remove();

        let formSection = document.createElement("div");
        formSection.classList.add("form-section");
        formSection.setAttribute("id", `wrong-answer-${wrongAnswersCount + 1}-section`);
        formSection.appendChild(labelElement);

        let answerControls = document.createElement("div");
        answerControls.classList.add("answer-controls");

        let deleteAnswerButton = document.createElement("a");
        deleteAnswerButton.classList.add("button", "red");
        deleteAnswerButton.innerText = "Διαγραφή";
        deleteAnswerButton.addEventListener("click", () => {
            deleteAnswerInput(wrongAnswersCount + 1);
        });

        answerControls.appendChild(inputElement);
        answerControls.appendChild(deleteAnswerButton);

        formSection.appendChild(answerControls);

        form.appendChild(formSection);
        form.appendChild(formButtons);
    }

    function deleteAnswerInput(idToDelete) {
        let elementToDelete = document.querySelector(`#wrong-answer-${idToDelete}-section`);
        elementToDelete.remove();
    }

    function submitForm() {
        const questionType = document.querySelector("#choose-question-type").value;

        let questionText;
        let rightAnswer;
        let wrongAnswers;
        let quizID;
        const searchParams = new URLSearchParams();

        if (questionType === "multiple-choice") {
            questionText = multipleChoiceForm.querySelector(".form-section #question-text").value;
            rightAnswer = multipleChoiceForm.querySelector(".form-section #right-answer").value;
            wrongAnswers = multipleChoiceForm.querySelectorAll(".form-section .wrong-answer");
            quizID = multipleChoiceForm.querySelector(".form-section #question-quiz").value;
        } else if (questionType === "drag-drop") {
            questionText = `${dragDropForm.querySelector(".form-section #question-text").value}`;
            rightAnswer =
                `<div draggable='true'>${filterText(dragDropForm.querySelector(".form-section #right-answer").value)}</div>`;
            wrongAnswers = dragDropForm.querySelectorAll(".form-section .wrong-answer");
            quizID = dragDropForm.querySelector(".form-section #question-quiz").value;
            let givenAnswerSection = dragDropForm.querySelector(".form-section #question-answer-text").value;
            questionText = `${questionText} \n ${filterText(givenAnswerSection)}`;
        }

        if (questionText === "" ||
            rightAnswer === "" ||
            quizID === ""
        ) {
            sweetAlertError({
                text: "Κάποια πεδία είναι κενά!",
            });
            return;
        }
        searchParams.append("question-type", questionType);
        searchParams.append("question", questionText);
        searchParams.append("right-answer", rightAnswer);
        for (const [i, ans] of wrongAnswers.entries()) {
            if (ans.value === "") {
                sweetAlertError({
                    text: "Κάποια πεδία είναι κενά!",
                });
                return;
            }
            if (questionType === "multiple-choice") {
                searchParams.append(`wrong-answer-${i}`, ans.value);
            } else {
                searchParams.append(`wrong-answer-${i}`, `<div draggable='true'>${filterText(ans.value)}</div>`);
            }
        }
        searchParams.append("quizID", quizID);
        searchParams.append("submit", "submit");

        fetch(`/${baseURL}/includes/newQuestionHandler.php`, {
                method: "POST",
                body: searchParams
            }).then(function(response) {
                return response.text();
            })
            .then(function(text) {
                let error = text.split("=")[1];
                switch (error) {
                    case "none":
                        location.reload();
                        break;
                    case "noWrongAnswers":
                        sweetAlertError({
                            text: "Δεν έχετε δώσει λάθος απαντήσεις!",
                        });
                        break;
                    case "rightAnswerNotCreatedOrNoID":
                        sweetAlertError({
                            text: "Υπήρξε πρόβλημα στη δημιουργία της σωστής απάντησης!",
                        });
                        break;
                    case "emptyInputs":
                        sweetAlertError({
                            text: "Κάποια πεδία είναι κενά!",
                        });
                        break;
                    case "questionNotCreatedOrNoID":
                        sweetAlertError({
                            text: "Υπήρξε πρόβλημα στη δημιουργία της ερώτησης!",
                        });
                        break;
                    case "accessDenied":
                        sweetAlertError({
                            text: "Δεν έχετε πρόσβαση σε αυτή τη λειτουργία!",
                        });
                        break;
                    default:
                        break;
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    function changeQuestionType() {
        const dropdownValue = document.querySelector("#choose-question-type").value;
        console.log(`Question type: ${dropdownValue}`);
        switch (dropdownValue) {
            case "multiple-choice":
                multipleChoiceForm.style.display = "flex";
                dragDropForm.style.display = "none";
                selectQuiz = multipleChoiceForm.querySelector("select#question-quiz");
                break;
            default:
                multipleChoiceForm.style.display = "none";
                dragDropForm.style.display = "flex";
                selectQuiz = dragDropForm.querySelector("select#question-quiz");
                break;
        }
    }

    fetch(`/${baseURL}/includes/fetchQuizzes.php`).then((res) => {
        return res.json();
    }).then((jsonArray) => {
        jsonArray.forEach(element => {
            const option = document.createElement("option");
            option.classList.add("quiz");
            option.innerText = element.quizTitle
            option.setAttribute("value", element.quizID)

            selectQuiz.appendChild(option);
        });
    }).catch((error) => {
        console.error(`${error}`);
    });
    </script>
    <script src="<?php echo $baseURL ?>/js/testingFile.js"></script>
    <?php include '../components/footer.php'; ?>