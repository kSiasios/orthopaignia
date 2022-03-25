<script src="<?php echo $baseURL ?>/js/global.js"></script>
<nav>
    <!-- <a href="/sostografia/"><i class="fi fi-rr-arrow-small-left"></i></a> -->
    <ul>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/">ΑΡΧΙΚΗ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/category.php">ΚΑΝΟΝΕΣ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/grades.php">ΒΑΘΜΟΛΟΓΙΑ</a></li>
        <li class="nav-link"><a href="<?php echo $baseURL ?>/routes/account.php">ΛΟΓΑΡΙΑΣΜΟΣ</a></li>
        <?php
        if (isset($_SESSION["isAdmin"])) {
            echo '<li class="nav-link"><a href="$baseURL/routes/admin_panel.php">ΔΙΑΧΕΙΡΗΣΗ</a></li>';
        }
        if (isset($_SESSION["logged"])) {
            echo '<li class="nav-link"><a onclick="logoutHandler()">ΑΠΟΣΥΝΔΕΣΗ</a></li>';
        }
        //  else {
        //     echo '<li class="nav-link"><a href="/sostografia/">ΣΥΝΔΕΣΗ</a></li>';
        // }
        ?>
    </ul>
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
</script>