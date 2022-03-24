<script src="/sostografia/js/global.js"></script>
<nav>
    <!-- <a href="/sostografia/"><i class="fi fi-rr-arrow-small-left"></i></a> -->
    <ul>
        <li class="nav-link"><a href="/sostografia/">ΑΡΧΙΚΗ</a></li>
        <li class="nav-link"><a href="/sostografia/routes/category.php">ΚΑΝΟΝΕΣ</a></li>
        <li class="nav-link"><a href="/sostografia/routes/grades.php">ΒΑΘΜΟΛΟΓΙΑ</a></li>
        <li class="nav-link"><a href="/sostografia/routes/account.php">ΛΟΓΑΡΙΑΣΜΟΣ</a></li>
        <?php
        if (isset($_SESSION["isAdmin"])) {
            echo '<li class="nav-link"><a href="/sostografia/routes/admin_panel.php">ΔΙΑΧΕΙΡΗΣΗ</a></li>';
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
    case "/sostografia/":
        // console.log("HOME PAGE");
        links[0].classList.add("active");
        break;
    case "/sostografia/index.php":
        // console.log("HOME PAGE");
        links[0].classList.add("active");
        break;
    case "/sostografia/routes/category.php":
        // console.log("HOME PAGE");
        links[1].classList.add("active");
        break;
    case "/sostografia/routes/account.php":
        // console.log("HOME PAGE");
        links[3].classList.add("active");
        break;
    default:
        break;
}
</script>