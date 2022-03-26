<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION['isAdmin']))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    // header("location: $baseURL/");
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
    <!-- <script src="/sostografia/js/fetchAdminData.js"></script> -->
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="form-container">
            <form action="">
                <div class="form-section">
                    <label for="question-text">Ερώτηση</label>
                    <input type="text" name="question-text" id="question-text">
                </div>
                <div class="form-section">

                    <label for="right-answer">Σωστή Απάντηση</label>
                    <input type="text" name="right-answer-text" id="right-answer">
                </div>
                <div class="form-section">
                    <label for="wrong-answer-1">Λάθος Απάντηση</label>
                    <input type="text" name="wrong-answer-1-text" class="wrong-answer" key="1">
                </div>
                <!-- <textarea name="question-text" id="question-text" cols="30" rows="10"></textarea> -->
                <div class="form-buttons">
                    <a class="button" onclick="addAnswerInput()">Προσθήκη Απάντησης</a>
                    <a class="button green" onclick="submitForm()">Ολοκλήρωση</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        let form = document.querySelector("form");
        let formButtons = document.querySelector(".form-buttons");
        // let submitFormBtn = document.querySelector(".form-buttons").lastElementChild;

        function addAnswerInput() {
            let inputElement = document.createElement("input");
            let labelElement = document.createElement("label");

            let wrongAnswersCount = document.querySelectorAll("#wrong-answer").length;

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
            console.log("Submitting form");
            let questionText = document.querySelector("#question-text").value;
            let rightAnswer = document.querySelector("#right-answer").value;
            let wrongAnswers = document.querySelectorAll(".wrong-answer");
            for (const ans of wrongAnswers) {
                if (ans.value === "") {
                    ans.style.background = "red";
                }
                console.log(`wrong answer: ${ans.value}`);
            }
            console.log(questionText, rightAnswer);
        }
    </script>
</body>

</html>