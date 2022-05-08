<?php
session_start();
$title = "Κανόνες";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/category.css">';

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
                    <button class="blue" onclick="updateRule(-1)"><i class="fi fi-rr-angle-left"></i> Προηγούμενο</button>
                </div>
                <div class="next-buttons">
                    <button class="inverse">Παράβλεψη <i class="fi fi-rr-angle-double-right"></i></button>
                    <button class="blue" onclick="updateRule(1)">Επόμενο <i class="fi fi-rr-angle-right"></i></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ruleBody = document.querySelector(".rule-body");
        const buttons = document.querySelector(".buttons-container");
        let rulesObj;
        let ruleIndex = 0;

        // fetch(`/${baseURL}/includes/fetchRulesHTML.php`).then((res) => {
        //     return res.text();
        // }).then((text) => {
        //     const jsonObj = JSON.parse(text);
        //     rulesObj = jsonObj;
        //     updateRule(ruleIndex);
        // }).catch((error) => {
        //     console.log(error);
        // });
        fetch(`/${baseURL}/includes/fetchRules.php`).then((res) => {
            return res.json();
        }).then((jsonArray) => {
            // const jsonObj = JSON.parse(text);
            // rulesObj = jsonObj;
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
            } else {
                nextBtns.style.display = "flex";
            }
            buttons.remove();
            ruleBody.innerHTML = element.ruleText;
            let ruleHeader = document.querySelector(".rule-header").querySelector("p");
            ruleHeader.innerText = element.ruleName;

            ruleBody.parentElement.appendChild(buttons);
        }
    </script>
    <?php include '../components/footer.php'; ?>