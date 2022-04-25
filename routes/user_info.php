<?php
session_start();
$title = "Δεδομένα Χρήστη";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/admin_panel.css">';

include '../header.php';

if (!isset($_SESSION["isAdmin"])) {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: $baseURL/");
    exit();
}

if (!isset($_GET['user']) || $_GET['user'] == "") {
    echo "<script>window.location = '" . str_replace("\n", "", $baseURL) . "'</script>";
    // header("location: $baseURL/");
    exit();
}

?>

<body>
    <!-- <script src="<?php echo $baseURL ?>/js/fetchAdminData.js"></script> -->
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
            document.querySelector(".page-content").innerHTML = text;
            // console.log(text);
        }).catch((error) => {
            console.error(`${error}`);
        });
    </script>
    <?php include '../components/footer.php'; ?>