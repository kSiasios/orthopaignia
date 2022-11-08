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
            </div>
            <div class="rule-body"></div>
            <div class="buttons-container">
                <div class="prev-button">
                    <button class="blue" onclick="updateRule(-1)">
                        <ion-icon name="caret-back"></ion-icon>
                        Προηγούμενο
                    </button>
                </div>
                <div class="next-buttons">
                    <button class="blue" onclick="updateRule(1)">
                        Επόμενο
                        <ion-icon name="chevron-forward"></ion-icon>
                    </button>
                </div>
                <button class="blue" id="play-button"
                    onclick="redirectTo(`/${baseURL}/routes/quiz.php${totalCounter !== 0 ? '?studyTime=' + totalCounter : ''}`)">
                    Παίξε
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
        window.location = url;
    }
    </script>
    <?php include '../components/footer.php'; ?>