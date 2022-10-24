<?php
session_start();
$title = "Κανόνες";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/rules.css">';

include '../header.php';
if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="rule-container">
            <div class="rule-header">
                <p></p>
                <!-- <p>1ος Κανόνας</p> -->
            </div>
            <div class="rule-body"></div>
            <!-- <div class="rule">
                <div class="rule-section">
                    <p class="rule-text">
                        Τα αρσενικά που τελειώνουν σε -ης, γράγονται με ήτα. (η)
                    </p>
                    <div class="rule-example">
                        <p class="rule-example-header">
                            Παράδειγμα
                        </p>
                        <p class="example">
                            ο στρατιώτης<br />
                            ο σπουδαστής
                        </p>
                    </div>
                </div>
            </div>
            <div class="rule">
                <div class="rule-section">
                    <p class="rule-text">
                        Τα θηλυκά που τελειώνουν σε -η, γράγονται με ήτα. (η)
                    </p>
                    <div class="rule-example">
                        <p class="rule-example-header">
                            Παράδειγμα
                        </p>
                        <p class="example">
                            η βρύση<br />
                            η γιορτή
                        </p>
                    </div>
                </div>
            </div> -->
            <div class="buttons-container">
                <div class="prev-button">
                    <button class="blue" onclick="updateRule(-1)">
                        <ion-icon name="caret-back"></ion-icon>
                        <!-- <i class="fi fi-rr-angle-left"></i>  -->
                        Προηγούμενο
                    </button>
                </div>
                <div class="next-buttons">
                    <!-- <button class="inverse">
                        Παράβλεψη
                        <ion-icon name="play-skip-forward"></ion-icon>
                    </button> -->
                    <button class="blue" onclick="updateRule(1)">
                        Επόμενο
                        <ion-icon name="chevron-forward"></ion-icon>
                        <!-- <i class="fi fi-rr-angle-right"></i> -->
                    </button>
                </div>
                <button class="blue" id="play-button" onclick="redirectTo(`/${baseURL}/routes/quiz.php${totalCounter !== 0 ? '?studyTime=' + totalCounter : ''}`)">
                    Παίξε
                    <!-- <i class="fi fi-sr-play"></i> -->
                    <ion-icon name="caret-forward"></ion-icon>
                </button>
            </div>
        </div>
    </div>
    <script>
        const ruleBody = document.querySelector(".rule-body");
        const buttons = document.querySelector(".buttons-container");
        let rulesObj;
        let ruleIndex = 0;

        let totalCounter = 0;

        setInterval(() => {
            totalCounter++;
        }, 100);

        const searchParams = new URLSearchParams();
        searchParams.append("submit", "submit");
        searchParams.append("quizIndex", "<?php echo $_GET['index'] ?>");

        fetch(`/${baseURL}/includes/fetchRules.php`, {
            method: "POST",
            body: searchParams,
        }).then((res) => {
            return res.json();
        }).then((jsonArray) => {
            rulesObj = jsonArray;
            updateRule(ruleIndex);
        }).catch((error) => {
            console.log(error);
        });

        function updateRule(index) {
            ruleIndex += index;
            const element = rulesObj[ruleIndex];
            const prevBtn = buttons.querySelector(".prev-button");
            const nextBtns = buttons.querySelector(".next-buttons");
            const playBtn = buttons.querySelector("#play-button");
            if (ruleIndex <= 0) {
                ruleIndex = 0;
                prevBtn.style.display = "none";
                buttons.style.justifyContent = "flex-end";
            } else {
                prevBtn.style.display = "block";
                buttons.style.justifyContent = "space-between";
            }
            if (ruleIndex >= Object.keys(rulesObj).length - 1) {
                ruleIndex = Object.keys(rulesObj).length - 1;
                nextBtns.style.display = "none";
                playBtn.style.display = "flex";
            } else {
                playBtn.style.display = "none";
                nextBtns.style.display = "flex";
            }
            buttons.remove();
            ruleBody.innerHTML = element.ruleText;
            let ruleHeader = document.querySelector(".rule-header").querySelector("p");
            ruleHeader.innerText = element.ruleName;

            ruleBody.parentElement.appendChild(buttons);
        }

        function redirectTo(url) {
            // Update user's study time

            // const searchParams = new URLSearchParams();

            // searchParams.append("submit", "submit");
            // // searchParams.append("results", results.map((elem) => {
            // //     return elem.join("~")
            // // }).join("|"));
            // // searchParams.append("results", JSON.stringify(questionResults));
            // searchParams.append("totalTime", totalCounter);
            // searchParams.append("quizID", localStorage.getItem("quizProgress"));

            // // console.log(JSON.stringify(questionResults));

            // fetch(`/${baseURL}/includes/setGrades.php`, {
            //     method: "POST",
            //     body: searchParams
            // }).then((res) => {
            //     return res.text();
            // }).then((text) => {
            //     const error = JSON.parse(text).error;
            //     switch (error) {
            //         case "none":
            //             console.log("Hooray! Grades set successfully!");
            //             break;
            //         default:
            //             console.log("No-ray!");
            //             break;
            //     }
            //     // console.log(text);
            window.location = url;
            // }).catch((error) => {
            //     console.log(error);
            // })

        }
    </script>
    <?php include '../components/footer.php'; ?>