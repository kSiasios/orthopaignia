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
    // header("location: " . $baseURL . "/");
    exit();
}
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="settings">
            <h3>Ρυθμίσεις</h3>
            <div class="settings-section">
                <form action="">
                    <input type="text" disabled name="username" id="settings-username" placeholder="Όνομα Χρήστη" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username'] ?>">
                    <input type="text" name="firstname" id="settings-firstname" placeholder="Όνομα" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['firstname'] ?>">
                    <input type="text" name="lastname" id="settings-lastname" placeholder="Επώνυμο" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['lastname'] ?>">
                    <a class="button green">Αποθήκευση</a>
                </form>
            </div>
            <div class="change-password">
                <form action="">
                    <input type="password" name="old-password" id="old-password" placeholder="Κωδικός Πρόσβασης">
                    <input type="password" name="new-password" id="new-password" placeholder="Νέος Κωδικός Πρόσβασης">
                    <input type="password" name="rep-new-password" id="rep-new-password" placeholder="Επανάληψη Νέου Κωδικού Πρόσβασης">
                    <a class="button green">Αποθήκευση</a>
                </form>
            </div>
            <div class="delete-section">
                <h4>Διαγραφή Λογαριασμού</h4>
                <button class="red" onclick="deleteAccount()">Διαγραφή</button>
            </div>
        </div>
    </div>
    <script>
        function deleteAccount() {
            fetch(`/${baseURL}/includes/deleteAccount.php`)
                .then((res) => {
                    return res.text();
                })
                .then((text) => {
                    let error = text.split("=")[1];

                    if (error === "none")
                        window.location = `/${baseURL}`;
                    console.log(`Server Response: ${text}`);
                })
                .catch((err) => {
                    console.error(`An error occured: ${err}`);
                });
        }
    </script>
</body>

</html>