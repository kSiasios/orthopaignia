<?php
session_start();
$title = "Λογαριασμός";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/account.css">
    ';
include '../header.php';

if (!isset($_SESSION["logged"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    exit();
}
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="settings">
            <h3>Ρυθμίσεις</h3>
            <div class="settings-section">
                <h4>Προσωπικά Στοιχεία</h4>
                <form id="change-info-form">
                    <input type="text" disabled name="username" id="settings-username" placeholder="Όνομα Χρήστη" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username'] ?>">
                    <input type="text" name="firstname" id="settings-firstname" placeholder="Όνομα" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['firstname'] ?>">
                    <input type="text" name="lastname" id="settings-lastname" placeholder="Επώνυμο" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['lastname'] ?>">
                    <button class="button green" type="submit" form="change-info-form">Αποθήκευση</button>
                </form>
            </div>
            <div class="change-password">
                <h4>Αλλαγή Κωδικού Πρόσβασης</h4>
                <form id="change-password-form">
                    <input type="password" name="old-password" id="old-password" placeholder="Κωδικός Πρόσβασης">
                    <input type="password" name="new-password" id="new-password" placeholder="Νέος Κωδικός Πρόσβασης">
                    <input type="password" name="rep-new-password" id="rep-new-password" placeholder="Επανάληψη Νέου Κωδικού Πρόσβασης">
                    <button class="button green" type="submit" form="change-password-form">Αποθήκευση</button>
                </form>
            </div>
            <div class="delete-section">
                <h4>Διαγραφή Λογαριασμού</h4>
                <button class="red" onclick="deleteAccount()">Διαγραφή</button>
            </div>
        </div>
    </div>
    <script>
        let userInfoForm = document.querySelector(".settings-section").getElementsByTagName("form")[0];
        let userPasswordForm = document.querySelector(".change-password").getElementsByTagName("form")[0];


        userInfoForm.addEventListener(
            "submit",
            function(event) {
                event.preventDefault();
                updateUserInfo();
            },
            true
        );

        userPasswordForm.addEventListener("submit",
            function(event) {
                event.preventDefault();
                updateUserPassword();
            },
            true);

        function deleteAccount() {
            Swal.fire({
                    title: 'Σίγουρα;',
                    text: 'Η διαδικασία είναι μη αναστρέψιμη!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ναι, σίγουρα',
                    cancelButtonText: "Ακύρωση"
                })
                .then(() => {
                    const searchParams = new URLSearchParams();
                    searchParams.append("submit", "submit");

                    fetch(`/${baseURL}/includes/deleteAccount.php`, {
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
                                    location.reload();
                                    break;
                                case "notLoggedOrNoUsername":
                                    sweetAlertError(
                                        text = "Δεν έχετε πρόσβαση σε αυτή τη σελίδα!",
                                        redirect = `/${baseURL}`
                                    );
                                    break;
                                case "evaluationsDeletionFailed":
                                    sweetAlertError();
                                    break;
                                case "administratorsDeletionFailed":
                                    location.reload();
                                    break;
                                case "userDeletionFailed":
                                    location.reload();
                                    break;
                                default:
                                    console.log(`Not Updated: ${error}`);
                                    break;
                            }
                        })
                        .catch((error) => {
                            console.log(`${error}`);
                        });
                });
        }

        function updateUserInfo() {
            const userInfoData = new FormData(userInfoForm);

            const searchParams = new URLSearchParams();

            for (const pair of userInfoData) {
                searchParams.append(pair[0], pair[1]);
            }

            searchParams.append("submit", "submit");

            fetch(`/${baseURL}/includes/updateUserInfo.php`, {
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
                            location.reload();
                            break;
                        default:
                            console.log("Not Updated");
                            break;
                    }
                })
                .catch((error) => {
                    console.log(`${error}`);
                });
        }

        function updateUserPassword() {
            console.log("updating user password");
            const userPasswordData = new FormData(userPasswordForm);
            const searchParams = new URLSearchParams();

            for (const pair of userPasswordData) {
                if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
                    window.alert("Κάποια πεδία είναι κενά!");
                    return;
                }
                searchParams.append(pair[0], pair[1]);
            }

            searchParams.append("submit", "submit");

            if (searchParams.get("new-password") !== searchParams.get("rep-new-password")) {
                window.alert("Οι κωδικοί δεν είναι ίδιοι! Βεβαιωθείται ότι επαναλαμβάνετε τον νέο σας κωδικό σωστά.");
                return;
            }

            fetch(`/${baseURL}/includes/updateUserPassword.php`, {
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
                            break;
                        case "userNotFound":
                            window.alert("Δεν βρέθηκε ο χρήστης!");
                            break;
                        case "wrongPassword":
                            window.alert("Ο παλιός κωδικός που δώσατε είναι λάθος!");
                            break;
                        default:
                            console.log("Not Updated");
                            break;
                    }
                })
                .catch((error) => {
                    console.log(`${error}`);
                });
        }
    </script>
    <?php include '../components/footer.php'; ?>