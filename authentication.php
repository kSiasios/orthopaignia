<?php
session_start();
if (isset($_SESSION['logged'])) {
    header("location: $baseURL/");
    exit();
}

$title = "Σύνδεση";
$stylesheets =
    '<link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">';
include './header.php';
?>

<body>
    <div class="page-content">
        <div class="login-form">
            <div class="form-header">
                <p>Ορθοπαίγνια</p>
            </div>
            <div class="form-selector">
                <a href="#" onclick="toggleForm('login')">Σύνδεση</a>
                <a href="#" onclick="toggleForm('register')">Εγγραφή</a>
            </div>
            <div class="form-inputs login">
                <form method="POST">
                    <div class="input-container">
                        <label for="username">
                            <ion-icon name="person"></ion-icon>
                            <!-- <i class="fi fi-ss-user"></i> -->
                        </label>
                        <input type="text" name="username" id="username-input login" placeholder="Όνομα Χρήστη" />
                    </div>
                    <div class="input-container">
                        <label for="password">
                            <ion-icon name="key"></ion-icon>
                            <!-- <i class="fi fi-ss-key"></i> -->
                        </label>
                        <input type="password" name="password" id="password-input login" placeholder="Κωδικός Πρόσβασης" />
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
                    <div class="input-sections">
                        <div class="authentication-data">
                            <div class="section-header">
                                <p class="header">Στοιχεία χρήστη</p>
                            </div>
                            <div class="input-container">
                                <label for="username">
                                    <!-- <i class="fi fi-ss-user"></i> -->
                                    <ion-icon name="person"></ion-icon>
                                </label>
                                <input type="text" name="username" id="username-input register" placeholder="Όνομα Χρήστη" />
                            </div>
                            <div class="input-container">
                                <label for="email">
                                    <ion-icon name="at"></ion-icon>
                                    <!-- <i class="fi fi-ss-at"></i> -->
                                </label>
                                <input type="email" name="email" id="email-input" placeholder="Ηλ. Ταχυδρομείο" />
                            </div>
                            <div class="input-container">
                                <label for="password">
                                    <ion-icon name="key"></ion-icon>
                                    <!-- <i class="fi fi-ss-key"></i> -->
                                </label>
                                <input type="password" name="password" id="password-input register" placeholder="Κωδικός Πρόσβασης" />
                            </div>
                            <div class="input-container">
                                <label for="password">
                                    <ion-icon name="key"></ion-icon>
                                    <!-- <i class="fi fi-ss-key"></i> -->
                                </label>
                                <input type="password" name="repeat-password" id="repeat-password-input" placeholder="Επανάληψη Κωδικού Πρόσβασης" />
                            </div>
                        </div>
                        <div class="personal-data">
                            <div class="section-header">
                                <p class="header">Στοιχεία μαθητή</p>
                            </div>
                            <div class="input-container">
                                <label for="student-name">
                                    <!-- <i class="fi fi-ss-user"></i> -->
                                    <ion-icon name="person"></ion-icon>
                                </label>
                                <input type="text" name="student-name" id="student-name-input register" placeholder="Όνομα Μαθητή" />
                            </div>
                            <div class="input-container">
                                <label for="student-lastname">
                                    <!-- <i class="fi fi-ss-user"></i> -->
                                    <ion-icon name="person"></ion-icon>
                                </label>
                                <input type="text" name="student-lastname" id="student-lastname-input register" placeholder="Επώνυμο Μαθητή" />
                            </div>
                            <div class="input-container">
                                <label for="student-grade">
                                    <ion-icon name="school"></ion-icon>
                                    <!-- <i class="fi fi-sr-graduation-cap"></i> -->
                                </label>
                                <!-- <input type="text" name="student-grade" id="student-grade-input register" placeholder="Τάξη" /> -->
                                <select name="student-grade" id="student-grade">
                                    <option value="none" disabled selected>Επιλέξτε τάξη</option>
                                    <option value="3">Γ' τάξη</option>
                                    <option value="4">Δ' τάξη</option>
                                    <option value="5">Ε' τάξη</option>
                                    <option value="6">ΣΤ' τάξη</option>
                                    <option value="other">Δευτεροβάθμια εκπαίδευση</option>
                                </select>
                            </div>
                            <!-- <div class="input-container">
                                <label for="favourite-color"><i class="fi fi-br-fill"></i></label>
                                <label class="custom-label" for="favourite-color">Αγαπημένο Χρώμα</label>
                                <input type="color" name="favourite-color" id="favourite-color-input register" value="#ffffff" />
                            </div> -->
                        </div>
                    </div>
                    <div class="form-submit">
                        <button value="submit" type="submit" name="submit">
                            Εγγραφή
                        </button>
                    </div>
                </form>

            </div>

            <div class="form-extras">
                <!-- <div class="forgot-password">
                    <p>Ξεχάσατε τον κωδικό σας; <a href="">Πατήστε εδώ</a></p>
                </div> -->
            </div>
        </div>
    </div>
    <!-- <p>Hello, world!</p> -->
    <script>
        let registerForm = document.querySelector(".form-inputs.register");
        let loginForm = document.querySelector(".form-inputs.login");

        registerForm.addEventListener(
            "submit",
            (event) => {
                event.preventDefault();
                submitRegister();
            },
            true
        );

        loginForm.addEventListener(
            "submit",
            (event) => {
                event.preventDefault();
                submitLogin();
            },
            true
        );

        let formExtras = document.querySelector(".form-extras");

        let loginLink = document.querySelectorAll(".form-selector a")[0];
        let registerLink = document.querySelectorAll(".form-selector a")[1];

        registerForm.style.display = "none";
        loginForm.style.display = "flex";

        loginLink.classList.add("active");
        registerLink.classList.remove("active");

        function toggleForm(mode) {
            if (mode == "login") {
                registerForm.style.display = "none";
                loginForm.style.display = "flex";

                formExtras.style.display = "block";

                document.title = `Σύνδεση - ${document.title.split(" - ")[1]}`;

                loginLink.classList.add("active");
                registerLink.classList.remove("active");
            } else {
                registerForm.style.display = "flex";
                loginForm.style.display = "none";

                formExtras.style.display = "none";

                document.title = `Εγγραφή - ${document.title.split(" - ")[1]}`;

                loginLink.classList.remove("active");
                registerLink.classList.add("active");
            }
        }
    </script>
    <script src="js/formHandler.js"></script>
</body>

</html>