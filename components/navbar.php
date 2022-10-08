<?php
if (!file("variables.env")) {
    $baseURL = file("../variables.env")[0];
} else {
    $baseURL = file("variables.env")[0];
}

?>

<script src="<?php echo $baseURL ?>/js/global.js"></script>
<nav>
    <!-- <a href="/sostografia/"><i class="fi fi-rr-arrow-small-left"></i></a> -->
    <ul class="closed">
        <li class="nav-link"><a href="<?php echo $baseURL ?>/">ΑΡΧΙΚΗ</a></li>
        <?php
        if (isset($_SESSION["isAdmin"])) {
            echo
            // '<li class="nav-link"><a href="' . $baseURL . '/routes/grades.php">ΒΑΘΜΟΛΟΓΙΑ</a></li>
            '<li class="nav-link"><a href="' . $baseURL . '/routes/admin_panel.php">ΔΙΑΧΕΙΡΙΣΗ</a></li>';
            // <li class="nav-link"><a href="' . $baseURL . '/routes/category.php">ΚΑΝΟΝΕΣ</a></li>
        } ?>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/account.php">ΛΟΓΑΡΙΑΣΜΟΣ</a></li>
        <?php
        if (isset($_SESSION["logged"])) {
            echo '<li class="nav-link"><a href="#" onclick="logoutHandler()">ΑΠΟΣΥΝΔΕΣΗ</a></li>';
        }
        ?>
    </ul>
    <a href="#" class="hamburger closed">
        <!-- <i class="fi fi-rr-menu-burger"></i> -->
        <ion-icon name="menu"></ion-icon>
    </a>
</nav>


<script>
    let links = document.querySelectorAll("nav ul li");

    switch (window.location.pathname) {
        case `${baseURL}/`:
            links[0].classList.add("active");
            break;
        case `${baseURL}/index.php`:
            links[0].classList.add("active");
            break;
        case `${baseURL}/routes/category.php`:
            links[1].classList.add("active");
            break;
        case `${baseURL}/routes/account.php`:
            links[3].classList.add("active");
            break;
        default:
            break;
    }

    const hamburgerBtn = document.querySelector(".hamburger");
    const navUl = document.querySelector("nav").querySelector("ul");
    // const closeIcon = '<i class="fi fi-rr-cross"></i>';
    const closeIcon = '<ion-icon name="close"></ion-icon>';
    const openIcon = '<ion-icon name="menu"></ion-icon>';
    // const openIcon = '<i class="fi fi-rr-menu-burger"></i>';
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