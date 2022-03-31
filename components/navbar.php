<?php
if (!file("variables.env")) {
    $baseURL = file("../variables.env")[0];
} else {
    $baseURL = file("variables.env")[0];
}
// echo "<script>console.log('Base URL:" . str_replace("\n", "", $baseURL) . "')</script>";
// echo $baseURL;

?>

<script src="<?php echo $baseURL ?>/js/global.js"></script>
<nav>
    <!-- <a href="/sostografia/"><i class="fi fi-rr-arrow-small-left"></i></a> -->
    <ul class="closed">
        <li class="nav-link"><a href="<?php echo $baseURL ?>/">ΑΡΧΙΚΗ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/category.php">ΚΑΝΟΝΕΣ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/grades.php">ΒΑΘΜΟΛΟΓΙΑ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/account.php">ΛΟΓΑΡΙΑΣΜΟΣ</a></li>
        <?php
        if (isset($_SESSION["isAdmin"])) {
            echo '<li class="nav-link"><a href="' . $baseURL . '/routes/admin_panel.php">ΔΙΑΧΕΙΡΗΣΗ</a></li>';
        }
        if (isset($_SESSION["logged"])) {
            echo '<li class="nav-link"><a href="#" onclick="logoutHandler()">ΑΠΟΣΥΝΔΕΣΗ</a></li>';
        }
        //  else {
        //     echo '<li class="nav-link"><a href="/sostografia/">ΣΥΝΔΕΣΗ</a></li>';
        // }
        ?>
    </ul>
    <a href="#" class="hamburger closed"><i class="fi fi-rr-menu-burger"></i></a>
</nav>


<script>
    // console.log(window.location.pathname);

    let links = document.querySelectorAll("nav ul li");

    switch (window.location.pathname) {
        case `${baseURL}/`:
            // console.log("HOME PAGE");
            links[0].classList.add("active");
            break;
        case `${baseURL}/index.php`:
            // console.log("HOME PAGE");
            links[0].classList.add("active");
            break;
        case `${baseURL}/routes/category.php`:
            // console.log("HOME PAGE");
            links[1].classList.add("active");
            break;
        case `${baseURL}/routes/account.php`:
            // console.log("HOME PAGE");
            links[3].classList.add("active");
            break;
        default:
            break;
    }

    const hamburgerBtn = document.querySelector(".hamburger");
    const navUl = document.querySelector("nav").querySelector("ul");
    const closeIcon = '<i class="fi fi-rr-cross"></i>';
    const openIcon = '<i class="fi fi-rr-menu-burger"></i>';
    // console.log(hamburgerBtn);
    hamburgerBtn.addEventListener("click", (e) => {
        hamburger();
    });

    function hamburger() {
        if (hamburgerBtn.classList.contains("closed")) {
            hamburgerBtn.classList.add("open");
            hamburgerBtn.classList.remove("closed");
            hamburgerBtn.innerHTML = closeIcon;
            navUl.classList.add("open");
            navUl.classList.remove("closed");
        } else {
            hamburgerBtn.classList.remove("open");
            hamburgerBtn.classList.add("closed");
            hamburgerBtn.innerHTML = openIcon;
            navUl.classList.add("closed");
            navUl.classList.remove("open");
        }
    }
</script>