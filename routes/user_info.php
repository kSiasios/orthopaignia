<?php
session_start();
$title = "Δεδομένα Χρήστη";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/user_info.css">';

include '../header.php';

if (!isset($_SESSION["isAdmin"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

if (!isset($_GET['user']) || $_GET['user'] == "") {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}

?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="user-info">
            <div class="user-name-email">
                <p class="user-name"></p> - <p class="user-email"></p>
            </div>
            <p class="user-education"></p>
        </div>
        <div class="evaluations">
            <ul class="evaluations-list">
            </ul>
        </div>
    </div>
    <script>
    const searchParams = new URLSearchParams();
    const url = new URL(window.location);
    const params = new URLSearchParams(url.search);

    if (!params.get("user")) {
        console.log("Param not found!");
        window.location = `/${baseURL}`;
    }

    searchParams.append("submit", "submit");
    searchParams.append("user", params.get("user"));

    fetch(`/${baseURL}/includes/fetchUserData.php`, {
        method: "POST",
        body: searchParams
    }).then((res) => {
        return res.text();
    }).then((text) => {
        if (text.includes("error")) {
            const error = text.split("=")[1];
            switch (error) {
                case "none":
                    window.location = `/${baseURL}`;
                    break;
                case "noUsersFound":
                    sweetAlertError({
                        text: "Δεν βρέθηκε ο συγκεκριμένος χρήστης.",
                        redirect: `/${baseURL}/routes/admin_panel.php`
                    })
                    break;
                default:
                    break;
            }
        } else {
            const jsonResponse = JSON.parse(text);

            console.log(jsonResponse);
            document.querySelector("p.user-name").innerText =
                `${jsonResponse.user.userFirstName} ${jsonResponse.user.userLastName}`;
            document.querySelector("p.user-email").innerText = `${jsonResponse.user.userEmail}`;
            document.querySelector("p.user-education").innerText =
                `${convertEducationToReadable(jsonResponse.user.userEducation)}`;

            const evalList = document.querySelector("ul.evaluations-list");

            jsonResponse.user.evaluations.forEach(evaluation => {
                const evalLI = document.createElement("li");
                evalLI.classList.add("evaluation");

                const evalHeader = document.createElement("h3")
                evalHeader.classList.add("evaluation-title");
                evalHeader.innerText = `Αξιολόγηση #${evaluation.evaluation.evaluationID}`;
                evalLI.appendChild(evalHeader);

                const evalGradesUL = document.createElement("ul");
                evalGradesUL.classList.add("evaluation-grades");


                evaluation.grades.forEach((grade, index) => {
                    const gradeElem = document.createElement("li");
                    gradeElem.classList.add("evaluation-item");
                    const gradeInfo = document.createElement("div");
                    gradeInfo.classList.add("grade-info");
                    gradeElem.appendChild(gradeInfo);
                    const gradeHeader = document.createElement("h4");
                    gradeHeader.innerText = `Βαθμός ${index}`;

                    gradeInfo.appendChild(gradeHeader);

                    const gradeDate = document.createElement("p");
                    const successRatio = document.createElement("p");
                    const completionTime = document.createElement("p");
                    const studyTime = document.createElement("p");
                    gradeDate.classList.add("grade-date");
                    successRatio.classList.add("success-ratio");
                    completionTime.classList.add("completion-time");
                    studyTime.classList.add("study-time");
                    gradeDate.innerText = "Ημερομηνία: ";
                    successRatio.innerText = "Ποσοστό Επιτυχίας: ";
                    completionTime.innerText = "Χρόνος Ολοκλήρωσης: ";
                    studyTime.innerText = "Χρόνος Μελέτης: ";

                    const valSpan0 = document.createElement("span");
                    valSpan0.classList.add("value");
                    valSpan0.innerText = `${grade.gradeDate}`;
                    const valSpan1 = document.createElement("span");
                    valSpan1.classList.add("value");
                    valSpan1.innerText = `${grade.successRatio * 100}%`;
                    const valSpan2 = document.createElement("span");
                    valSpan2.classList.add("value");
                    valSpan2.innerText = `${grade.answerTime}s`;
                    const valSpan3 = document.createElement("span");
                    valSpan3.classList.add("value");
                    valSpan3.innerText = `${grade.studyTime}s`;
                    gradeDate.appendChild(valSpan0);
                    successRatio.appendChild(valSpan1);
                    completionTime.appendChild(valSpan2);
                    studyTime.appendChild(valSpan3);

                    gradeInfo.appendChild(gradeDate);
                    gradeInfo.appendChild(successRatio);
                    gradeInfo.appendChild(completionTime);
                    gradeInfo.appendChild(studyTime);

                    evalGradesUL.appendChild(gradeElem);
                })

                evalLI.appendChild(evalGradesUL);

                evalList.appendChild(evalLI);
            });

        }
    }).catch((error) => {
        console.error(`${error}`);
    });
    </script>
    <?php include '../components/footer.php'; ?>