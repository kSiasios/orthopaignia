<?php
session_start();
$title = "Βαθμολογία";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/grades.css">';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: " . $baseURL . "/");
    exit();
}

// FETCH GRADES PER CAT AND PER RULE AND DISPLAY THEM
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h2>Η βαθμολογία μου</h2>
        </div>
    </div>
    <script>
        const pageContainer = document.querySelector(".page-content");
        const searchParams = new URLSearchParams();
        searchParams.append("user", `
            <?php
            if (isset($_SESSION["userID"])) {
                echo $_SESSION["userID"];
            } else {
                if (isset($_SESSION["username"])) {
                    echo $_SESSION["username"];
                } else if (isset($_SESSION["email"])) {
                    echo $_SESSION["email"];
                } else {
                    echo "-1";
                }
            }
            ?>`.replace(/\s/g, ""));
        searchParams.append("submit", "submit");
        fetch(`/${baseURL}/includes/fetchGradePerCategory.php`, {
            method: "POST",
            body: searchParams
        }).then((res) => {
            return res.text();
        }).then((text) => {
            // console.log(text);
            const responseJSON = JSON.parse(text);
            // console.log(responseJSON);
            const error = responseJSON.error;
            switch (error) {
                case "unauthorized":
                    window.alert("Unauthorized");
                    break;
                default:
                    break;
            }
            let categoryGradesContainer = document.createElement("div");
            categoryGradesContainer.classList.add("category-grades");
            let sectionHeader = document.createElement("h3");
            sectionHeader.innerText = "Κατηγορίες";
            categoryGradesContainer.appendChild(sectionHeader);
            for (let index = 0; index < Object.keys(responseJSON).length; index++) {
                const element = responseJSON[index];
                if (Object.keys(responseJSON)[index] !== "error") {
                    // console.log(`${element.name}: ${element.grade}`);
                    let categoryName = document.createElement("p");
                    categoryName.innerText = element.name;

                    let categoryProgress = document.createElement("div");
                    categoryProgress.classList.add("category-progress-bar");
                    let categoryGrade = document.createElement("p");
                    categoryProgress.appendChild(categoryGrade);
                    categoryProgress.setAttribute("data-grade", element.grade);
                    categoryProgress.style.setProperty("--conic-gradient-percentage", `${element.grade * 10}%`);
                    categoryGrade.innerText = element.grade;

                    let category = document.createElement("div");
                    category.classList.add("category-grade");
                    category.appendChild(categoryName);
                    // category.appendChild(categoryGrade);
                    category.appendChild(categoryProgress);
                    categoryGradesContainer.appendChild(category);
                }
            }
            pageContainer.appendChild(categoryGradesContainer);
            // console.log(error);
        }).catch((err) => {
            console.log(err);
        })

        fetch(`/${baseURL}/includes/fetchGradePerQuestion.php`, {
            method: "POST",
            body: searchParams
        }).then((res) => {
            return res.text();
        }).then((text) => {
            // console.log(text);
            const responseJSON = JSON.parse(text);
            // console.log(responseJSON);
            const error = responseJSON.error;
            switch (error) {
                case "unauthorized":
                    window.alert("Unauthorized");
                    break;
                default:
                    break;
            }
            let questionGradesContainer = document.createElement("div");
            questionGradesContainer.classList.add("question-grades");
            let sectionHeader = document.createElement("h3");
            sectionHeader.innerText = "Ερωτήσεις";
            questionGradesContainer.appendChild(sectionHeader);
            for (let index = 0; index < Object.keys(responseJSON).length; index++) {
                const element = responseJSON[index];
                if (Object.keys(responseJSON)[index] !== "error") {
                    // console.log(`${element.name}: ${element.grade}`);
                    let questionName = document.createElement("p");
                    questionName.innerText = element.name;
                    // let questionGrade = document.createElement("p");
                    // questionGrade.innerText = element.grade;

                    let questionProgress = document.createElement("div");
                    questionProgress.classList.add("question-progress-bar");
                    let questionGrade = document.createElement("p");
                    questionProgress.appendChild(questionGrade);
                    questionProgress.setAttribute("data-grade", element.grade);
                    questionProgress.style.setProperty("--conic-gradient-percentage", `${element.grade * 10}%`);
                    questionGrade.innerText = element.grade;

                    let question = document.createElement("div");
                    question.classList.add("question-grade");
                    question.appendChild(questionName);
                    // question.appendChild(questionGrade);
                    question.appendChild(questionProgress);
                    questionGradesContainer.appendChild(question);
                }
            }
            pageContainer.appendChild(questionGradesContainer);
            // console.log(error);
        }).catch((err) => {
            console.log(err);
        })
    </script>
    <?php include '../components/footer.php'; ?>