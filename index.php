<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header("location: authentication.php");
}

$title = "Αρχική";
$stylesheets =
    '<link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">';
include './header.php';
?>

<body>
    <?php include 'components/navbar.php'; ?>
    <div class="page-content">
        <!-- <p>Γειά σου  -->
        <?php
        // echo "<strong>" . $_SESSION['username'] . "</strong>"
        ?>
        <!-- </p> -->
        <!-- <div class="login-form">
            <div class="form-header">
                <p>Σωστογραφία</p>
            </div>
            <div class="form-selector">
                <a href="#" onclick="toggleForm('login')">Σύνδεση</a>
                <a href="#" onclick="toggleForm('register')">Εγγραφή</a>
            </div>
            <div class="form-inputs login">
                <form method="POST">
                    <div class="input-container">
                        <label for="username"><i class="fi fi-ss-user"></i></label>
                        <input type="text" name="username" id="username-input login" placeholder="Όνομα Χρήστη" />
                    </div>
                    <div class="input-container">
                        <label for="password"><i class="fi fi-ss-key"></i></label>
                        <input type="password" name="password" id="password-input login"
                            placeholder="Κωδικός Πρόσβασης" />
                    </div>
                    <div class="form-submit">
                        <button value="submit" type="submit" name="submit">
                            Σύνδεση
                        </button>
                    </div>
                </form>
            </div>
            <div class="form-inputs register">
                <form method="POST">
                    <div class="input-container">
                        <label for="username"><i class="fi fi-ss-user"></i></label>
                        <input type="text" name="username" id="username-input register" placeholder="Όνομα Χρήστη" />
                    </div>
                    <div class="input-container">
                        <label for="email"><i class="fi fi-ss-at"></i></label>
                        <input type="email" name="email" id="email-input" placeholder="Ηλ. Ταχυδρομείο" />
                    </div>
                    <div class="input-container">
                        <label for="password"><i class="fi fi-ss-key"></i></label>
                        <input type="password" name="password" id="password-input register"
                            placeholder="Κωδικός Πρόσβασης" />
                    </div>
                    <div class="input-container">
                        <label for="password"><i class="fi fi-ss-key"></i></label>
                        <input type="password" name="repeat-password" id="repeat-password-input"
                            placeholder="Επανάληψη Κωδικού Πρόσβασης" />
                    </div>
                    <div class="form-submit">
                        <button value="submit" type="submit" name="submit">
                            <p>Εγγραφή</p>
                        </button>
                    </div>
                </form>

            </div>

            <div class="form-extras">
                <div class="forgot-password">
                    <p>Ξεχάσατε τον κωδικό σας; <a href="">Πατήστε εδώ</a></p>
                </div>
            </div>
        </div> -->
        <div class="dialog fade-in">
            <p id="story-lines">
            </p>
            <div class="dialog-buttons">
                <button class="blue" id="next-dialog" onclick="changeStoryLine(1)">Επόμενο <i class="fi fi-rr-angle-right"></i></button>
                <button class="blue" id="prev-dialog" onclick="changeStoryLine(-1)"><i class="fi fi-rr-angle-left"></i>
                    Προηγούμενο</button>
            </div>
            <div class="answer-buttons">
                <button class="green" id="next-dialog" onclick="answerDialog('y')">Ναι</button>
                <button class="red" id="prev-dialog" onclick="answerDialog('n')">Όχι</button>
            </div>
        </div>
    </div>
    <!-- <p>Hello, world!</p> -->
    <script>
        let storyLines;
        let storyLineIndex = 0;

        let prevDialogBtn = document.querySelector("#prev-dialog");
        prevDialogBtn.style.display = "none";

        let nextDialogBtn = document.querySelector("#next-dialog");

        let dialogText = document.querySelector("#story-lines");

        let dialogBtns = document.querySelector(".dialog-buttons");
        let answerBtns = document.querySelector(".answer-buttons");

        let canPressButton = true;
        let stopFunc = false;

        fetch(`/${baseURL}/story/story_lines.json`)
            .then((response) => {
                return response.json();
            })
            .then((json) => {
                storyLines = json;
                // console.log(storyLines);
                // for (var key in storyLines) {
                //     var value = storyLines[key];
                //     if ((!!value) && (value.constructor === Object))
                //         console.log("BINGO")
                //     else
                //         console.log(value);
                // }
                // recursiveObjectPrint(storyLines);
                // console.log(`StoryLine at index 2: ${storyLines[2]}`);
                changeStoryLine(0);
            });

        function recursiveObjectPrint(obj) {
            let storyLinesElement = document.querySelector(".story-lines");
            for (var key in obj) {
                var value = obj[key];
                if (!!value && value.constructor === Object) {
                    recursiveObjectPrint(value);
                } else {
                    // console.log(value);
                    typeWriter(value, storyLinesElement);
                    // .then(console.log("----------------------- DONE -----------------------"));
                }
            }
        }

        function typeWriter(str, element) {
            canPressButton = false;
            let i = 0;
            const interval = setInterval(typeWriterIteration, 25);

            function typeWriterIteration() {
                if (i >= str.length) {
                    clearInterval(interval);
                    canPressButton = true;
                } else {
                    i++;
                    // console.log(`String: '${str.slice(0, i)}'`);
                    if (element != null) {
                        element.innerHTML = str.slice(0, i);
                    }
                }
            }
        }

        function changeStoryLine(index) {
            if (canPressButton) {
                // console.log(`StoryLineIndex: ${storyLineIndex}\n`);
                // console.log(`Index received: ${index}`);
                // console.log(`StoryLine Length: ${Object.keys(storyLines).length}`);
                if (
                    storyLineIndex + index < 0 ||
                    storyLineIndex + index > Object.keys(storyLines).length - 1
                ) {
                    console.error("StoryLineIndex is out of bounds!");
                    return;
                } else {
                    storyLineIndex += index;
                }
                if (storyLineIndex > 0) {
                    prevDialogBtn.style.display = "flex";
                } else {
                    prevDialogBtn.style.display = "none";
                }

                if (storyLineIndex < Object.keys(storyLines).length - 1) {
                    nextDialogBtn.style.display = "flex";
                    nextDialogBtn.parentElement.style.flexDirection = "row-reverse";
                } else {
                    nextDialogBtn.style.display = "none";
                    nextDialogBtn.parentElement.style.flexDirection = "row";
                }
                if (typeof storyLines[storyLineIndex] === "object") {
                    // WE HAVE DIALOG DEPENDING ON THE ANSWER (Y OR N)
                    // MAKE Y OR N BUTTONS VISIBLE, HIDE OTHER BUTTONS
                    answerBtns.style.display = "flex";
                    dialogBtns.style.display = "none";
                    typeWriter(
                        storyLines[storyLineIndex][0].replaceAll(
                            "<name>",
                            "<?php echo $_SESSION['username'] ?>"
                        ),
                        dialogText
                    );
                } else {
                    answerBtns.style.display = "none";
                    dialogBtns.style.display = "flex";
                    typeWriter(
                        storyLines[storyLineIndex].replaceAll(
                            "<name>",
                            "<?php echo $_SESSION['username'] ?>"
                        ),
                        dialogText
                    );
                }
            }
        }

        function answerDialog(ans) {
            switch (ans) {
                case 'y':
                    console.log("Answered YES");
                    break;
                case 'n':
                    console.log("Answered NO");
                    break;
                default:
                    console.error("Invalid answer!");
                    break;
            }
        }
    </script>
    <script src="js/formHandler.js"></script>
</body>

</html>