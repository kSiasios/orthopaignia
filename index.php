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
        <div class="dialog fade-in">
            <p id="story-lines">
            </p>
            <div class="dialog-buttons">
                <div class="next-btns">
                    <button class="inverse" id="skip-dialog" onclick="skipDialog()">Παράβλεψη <i class="fi fi-rr-angle-double-right"></i></button>
                    <button class="blue" id="next-dialog" onclick="changeStoryLine(1)">Επόμενο <i class="fi fi-rr-angle-right"></i></button>
                </div>
                <button class="blue" id="prev-dialog" onclick="changeStoryLine(-1)"><i class="fi fi-rr-angle-left"></i>
                    Προηγούμενο</button>
            </div>
            <div class="answer-buttons">
                <button class="green" id="yes-dialog" onclick="answerDialog('y')">Ναι <i class="fi fi-rr-thumbs-up"></i></button>
                <button class="red" id="no-dialog" onclick="answerDialog('n')">Όχι <i class="fi fi-rr-thumbs-down"></i></button>
            </div>
            <div class="play-buttons">
                <!-- <button class="inverse" id="prev-dialog" onclick="">Προπονήσου <i class="fi fi-ss-book-alt"></i></button> -->
                <button class="green" id="prev-dialog" onclick="window.location = `/${baseURL}/routes/quiz.php`">Παίξε <i class="fi fi-sr-play"></i></button>
            </div>
        </div>
        <div class="story-images">
            <div class="bronco fade-in">
                <img src="<?php echo $baseURL ?>/svg/Bronco Sad.svg" alt="" srcset="">
            </div>
            <div class="xenia fade-in">
                <img src="<?php echo $baseURL ?>/svg/Xenia Greet.svg" alt="" srcset="">
            </div>

        </div>
        <!-- <img src="/svg/Xenia Greet.svg" alt="" srcset=""> -->
    </div>
    <script>
        let storyLines;
        let storyLineIndex = 0;

        let prevDialogBtn = document.querySelector("#prev-dialog");
        prevDialogBtn.style.display = "none";

        let nextDialogBtn = document.querySelector("#next-dialog");

        let dialogText = document.querySelector("#story-lines");

        let dialogBtns = document.querySelector(".dialog-buttons");
        let answerBtns = document.querySelector(".answer-buttons");
        let playButton = document.querySelector(".play-buttons");
        playButton.remove();

        let canPressButton = true;
        let stopFunc = false;

        const xeniaImg = document.querySelector(".xenia").querySelector("img");
        const broncoImg = document.querySelector(".bronco").querySelector("img");

        fetch(`/${baseURL}/story/story_lines.json`)
            .then((response) => {
                return response.json();
            })
            .then((json) => {
                storyLines = json;
                changeStoryLine(0);
            });

        function recursiveObjectPrint(obj) {
            let storyLinesElement = document.querySelector(".story-lines");
            for (var key in obj) {
                var value = obj[key];
                if (!!value && value.constructor === Object) {
                    recursiveObjectPrint(value);
                } else {
                    typeWriter(value, storyLinesElement);
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
                    if (element != null) {
                        element.innerHTML = str.slice(0, i);
                    }
                }
            }
        }

        function changeStoryLine(index, answer) {
            if (canPressButton) {
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
                if (typeof storyLines[storyLineIndex + 1] === "object") {
                    // WE HAVE DIALOG DEPENDING ON THE ANSWER (Y OR N)
                    // MAKE Y OR N BUTTONS VISIBLE, HIDE OTHER BUTTONS
                    answerBtns.style.display = "flex";
                    dialogBtns.style.display = "none";
                    typeWriter(
                        storyLines[storyLineIndex].replaceAll(
                            "<name>",
                            "<?php echo $_SESSION['firstname'] ?>"
                        ),
                        dialogText
                    );
                    responsiveVoice.speak(`${storyLines[storyLineIndex].replaceAll(
                                        "<name>",
                                        "<?php echo $_SESSION['firstname'] ?>"
                                    )}`, "Greek Female");
                } else {
                    answerBtns.style.display = "none";
                    dialogBtns.style.display = "flex";
                    if (answer === true) {
                        if (!storyLines[storyLineIndex + 1]) {
                            // ENABLE PLAY BUTTON ON THE LAST DIALOG
                            answerBtns.parentElement.appendChild(playButton);
                            // DISABLE ALL OTHER BUTTONS
                            answerBtns.remove();
                            dialogBtns.remove();
                        } else {
                            playButton.remove();
                        }
                        typeWriter(
                            storyLines[storyLineIndex][0].replaceAll(
                                "<name>",
                                "<?php echo $_SESSION['firstname'] ?>"
                            ),
                            dialogText
                        );
                        responsiveVoice.speak(`${storyLines[storyLineIndex][0].replaceAll(
                                        "<name>",
                                        "<?php echo $_SESSION['firstname'] ?>"
                                    )}`, "Greek Female");
                    } else if (answer === false) {
                        nextDialogBtn.parentElement.style.display = "none";
                        dialogBtns.style.flexDirection = "row";
                        typeWriter(
                            storyLines[storyLineIndex][1].replaceAll(
                                "<name>",
                                "<?php echo $_SESSION['firstname'] ?>"
                            ),
                            dialogText
                        );
                        responsiveVoice.speak(`${storyLines[storyLineIndex][1].replaceAll(
                                            "<name>",
                                            "<?php echo $_SESSION['firstname'] ?>"
                                        )}`, "Greek Female");

                    } else {
                        typeWriter(
                            storyLines[storyLineIndex].replaceAll(
                                "<name>",
                                "<?php echo $_SESSION['firstname'] ?>"
                            ),
                            dialogText
                        );
                        responsiveVoice.speak(`${storyLines[storyLineIndex].replaceAll(
                                        "<name>",
                                        "<?php echo $_SESSION['firstname'] ?>"
                                    )}`, "Greek Female");
                    }

                }
            }

            if (storyLines[storyLineIndex + 1]) {
                playButton.remove();
            }

            switch (storyLineIndex) {
                case 0:
                    xeniaImg.src = `/${baseURL}/svg/Xenia Greet.svg`;
                    break;
                case 1:
                    xeniaImg.src = `/${baseURL}/svg/Xenia Troubled.svg`;
                    break;
                case 2:
                    xeniaImg.src = `/${baseURL}/svg/Xenia Sad.svg`;
                    break;
                case 3:
                    xeniaImg.src = `/${baseURL}/svg/Xenia Troubled.svg`;
                    break;
                case 4:
                    if (answer) {
                        xeniaImg.src = `/${baseURL}/svg/Xenia Happy.svg`;
                    } else {
                        xeniaImg.src = `/${baseURL}/svg/Xenia Sad.svg`;
                    }
                    break;
                default:
                    xeniaImg.src = `/${baseURL}/svg/Xenia Greet.svg`;
                    break;
            }
        }

        function answerDialog(ans) {
            let answer;
            switch (ans) {
                case 'y':
                    console.log("Answered YES");
                    answer = true;
                    break;
                case 'n':
                    console.log("Answered NO");
                    answer = false;
                    break;
                default:
                    console.error("Invalid answer!");
                    break;
            }
            changeStoryLine(1, answer);
        }

        function skipDialog() {
            if (canPressButton) {
                answerBtns.parentElement.appendChild(playButton);
                // DISABLE ALL OTHER BUTTONS
                answerBtns.remove();
                dialogBtns.remove();

                xeniaImg.src = `/${baseURL}/svg/Xenia Happy.svg`;

                typeWriter(
                    storyLines[Object.keys(storyLines).length - 1][0].replaceAll(
                        "<name>",
                        "<?php echo $_SESSION['firstname'] ?>"
                    ),
                    dialogText
                );
                responsiveVoice.speak(`${storyLines[Object.keys(storyLines).length - 1][0].replaceAll(
                                        "<name>",
                                        "<?php echo $_SESSION['firstname'] ?>"
                                    )}`, "Greek Female");
            }
        }
    </script>
    <script src="js/formHandler.js"></script>

    <?php include 'components/footer.php'; ?>