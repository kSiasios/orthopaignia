<?php
session_start();
$title = "Δεδομένα Χρήστη";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">';

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
                // console.log(`${jsonResponse.userFirstName} ${jsonResponse.userLastName} - ${jsonResponse.userEmail}<br/>${convertEducationToReadable(jsonResponse.userEducation)}`);
                console.log(jsonResponse);
                document.querySelector(".page-content").innerHTML = `${jsonResponse.user.userFirstName} ${jsonResponse.user.userLastName} - ${jsonResponse.user.userEmail}`;
                document.querySelector(".page-content").innerHTML += `<br/>${convertEducationToReadable(jsonResponse.user.userEducation)}`;

                jsonResponse.user.evaluations.forEach(evaluation => {
                    document.querySelector(".page-content").innerHTML += `<br/>- EvaluationID: ${evaluation.evaluation.evaluationID}`;
                    evaluation.grades.forEach((grade, index) => {
                        // console.log(grade);
                        document.querySelector(".page-content").innerHTML += `<br/>--- Grade ${index}:`;
                        document.querySelector(".page-content").innerHTML += `<br/>------- Date: ${grade.gradeDate}`;
                        document.querySelector(".page-content").innerHTML += `<br/>------- Success Ratio: ${grade.successRatio * 100}%`;
                        document.querySelector(".page-content").innerHTML += `<br/>------- Average Time Per Question: ${grade.timePerQuestion}s`;
                    })
                });
                // document.querySelector(".page-content").innerHTML += `<br/>${jsonResponse.user.userEducation}`;
            }
        }).catch((error) => {
            console.error(`${error}`);
        });
    </script>
    <?php include '../components/footer.php'; ?>