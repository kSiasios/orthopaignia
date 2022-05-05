<?php

if (isset($_POST['submit'])) {
    $categoryText = $_POST["category-text"];

    require_once "db.info.php";
    require_once "functions.php";

    // CHECK IF CATEGORY ALREADY EXISTS

    $sql = "SELECT * FROM category WHERE categoryName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $_POST["category-text"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        echo "error=categoryAlreadyExists";
        mysqli_stmt_close($stmt);
        exit();
    } else {
        mysqli_stmt_close($stmt);

        $sql = "INSERT INTO category(categoryName) VALUES (?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=stmtFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "s", $_POST["category-text"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    echo "error=none";
} else {
    echo ("error=accessDenied");
    exit();
}
