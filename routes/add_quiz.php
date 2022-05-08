<?php
session_start();

if (!(isset($_SESSION['logged']) && isset($_SESSION["isAdmin"]))) {
    // IF USERS IS NOT LOGGED IN OR
    // IS LOGGED IN AND IS NOT ADMIN,
    // EXIT
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

$title = "Προσθήκη Αξιολόγησης";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/add_form.css">
    ';
include '../header.php';
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="form-container">
            <form>
                <div class="form-section">
                    <label for="quiz-text">Όνομα Αξιολόγησης</label>
                    <input type="text" name="quiz-text" id="quiz-text">
                </div>
                <div class="form-buttons">
                    <a class="button green" href="#" onclick="submitForm()">Ολοκλήρωση</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        let quizForm = document.querySelector(".form-container");

        quizForm.addEventListener(
            "submit",
            function(event) {
                event.preventDefault();
                submitForm();
            },
            true
        );

        function submitForm() {
            const formData = new FormData(
                document
                .querySelector(".form-container")
                .getElementsByTagName("form")[0]
            );

            const searchParams = new URLSearchParams();

            for (const pair of formData) {
                if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
                    window.alert("Κάποια πεδία είναι κενά!");
                    return;
                }
                searchParams.append(pair[0], pair[1]);
            }

            searchParams.append("submit", "submit");

            fetch(`/${baseURL}/includes/newQuizHandler.php`, {
                    method: "POST",
                    body: searchParams,
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(text) {
                    let error = text.split("=")[1];

                    switch (error) {
                        case "none":
                            window.location = `/${baseURL}/routes/admin_panel.php`;
                            break;
                        case "stmtFailed":
                            window.alert("Κάτι πήγε στραβά! Προσπαθήστε πάλι αργότερα.");
                            break;
                        case "quizAlreadyExists":
                            window.alert("Υπάρχει ήδη αξιολόγηση με αυτό το όνομα.");
                            break;
                        default:
                            window.alert(`Υπήρξε ένα ασυνήθιστο λάθος: ${error}`);
                            break;
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    </script>
    <?php include '../components/footer.php'; ?>