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
        <div class="form-wrapper">

            <div class="form-container">
                <form action="">
                    <div class="form-section">
                        <label for="question-text">Ερώτηση</label>
                        <input type="text" name="question-text" id="question-text">
                    </div>
                    <div class="form-section">
                        <label for="question-rule">Αντίστοιχος Κανόνας</label>
                        <!-- <input type="text" name="question-text" id="question-text"> -->
                        <select name="question-rule" id="question-rule">
                            <option value="" selected disabled>Επιλέξτε Κανόνα</option>
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
                    <!-- <textarea name="question-text" id="question-text" cols="30" rows="10"></textarea> -->
                    <div class="form-buttons">
                        <a class="button" href="#" onclick="addAnswerInput()">Προσθήκη Απάντησης</a>
                        <a class="button green" href="#" onclick="submitForm()">Ολοκλήρωση</a>
                    </div>
                </form>
            </div>
            <div class="format-helper">
                <table>
                    <tr>
                        <td>!!κείμενο/!! <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-1">κείμενο</td>
                    </tr>
                    <tr>
                        <td>**κείμενο/** <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-2">κείμενο</td>
                    </tr>
                    <tr>
                        <td>$κείμενο/$ <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-3">κείμενο</td>
                    </tr>
                    <tr>
                        <td>#κείμενο/# <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-4">κείμενο</td>
                    </tr>
                    <tr>
                        <td>%κείμενο/% <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-5">κείμενο</td>
                    </tr>
                    <tr>
                        <td>^κείμενο/^ <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-6">κείμενο</td>
                    </tr>
                    <tr>
                        <td>&κείμενο/& <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-7">κείμενο</td>
                    </tr>
                    <tr>
                        <td>@κείμενο/@ <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-8">κείμενο</td>
                    </tr>
                    <tr>
                        <td>?κείμενο/? <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-9">κείμενο</td>
                    </tr>
                    <tr>
                        <td>[κείμενο/] <i class="fi fi-rr-arrow-right"></i></td>
                        <td class="special-text-10 test">κείμενο</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script src="<?php echo $baseURL ?>/js/textFormat.js"></script>
    <script>
        let form = document.querySelector("form");
        let formButtons = document.querySelector(".form-buttons");
        // let submitFormBtn = document.querySelector(".form-buttons").lastElementChild;
        const selectRule = document.querySelector("#question-rule");

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
            // console.log("Submitting form");
            const questionText = document.querySelector("#question-text").value;
            const rightAnswer = document.querySelector("#right-answer").value;
            const wrongAnswers = document.querySelectorAll(".wrong-answer");
            const ruleID = selectRule.value;

            if (questionText === "" || rightAnswer === "" || ruleID === "") {
                window.alert("Κάποια πεδία είναι κενά!");
                return;
            }
            // console.log(questionText, rightAnswer);
            const searchParams = new URLSearchParams();
            searchParams.append("question", questionText);
            searchParams.append("right-answer", rightAnswer);
            for (const [i, ans] of wrongAnswers.entries()) {
                if (ans.value === "") {
                    window.alert("Κάποια πεδία είναι κενά!");
                    return;
                }
                searchParams.append(`wrong-answer-${i}`, ans.value);
            }

            searchParams.append("rule", ruleID);
            searchParams.append("submit", "submit");

            fetch(`/${baseURL}/includes/newQuestionHandler.php`, {
                    method: "POST",
                    body: searchParams
                }).then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    let error = text.split("=")[1];
                    // console.log(`Error code: ${error}`);
                    switch (error) {
                        case "none":
                            window.location = `/${baseURL}/routes/admin_panel.php`;
                            break;
                            //     case "userDoesNotExist":
                            //         window.alert("Δεν υπάρχει χρήστης με αυτό το όνομα / email.");
                            //         break;
                            //     case "wrongPassword":
                            //         window.alert("Ο κωδικός που δώσατε είναι λάθος!");
                            //         break;
                        default:
                            break;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        fetch(`/${baseURL}/includes/fetchRulesOptions.php`)
            .then((res) => {
                return res.text();
            })
            .then((text) => {
                selectRule.innerHTML += text;
                // console.log(text);
            }).catch((error) => {
                console.error(`${error}`);
            });
    </script>
</body>

</html>