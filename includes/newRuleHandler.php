<?php

if (isset($_POST['submit'])) {
    // echo ("Username: " . $_POST["username"]);
    $catID = intval($_POST["rule-category"], $base = 10);
    // echo ("Category: " . $catID);
    // $categoryText = $_POST["category-text"];

    require_once "db.info.php";
    require_once "functions.php";

    // CHECK IF RULE ALREADY EXISTS
    $sql = "SELECT * FROM rule WHERE ruleName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $_POST["rule-name"]);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        echo "error=ruleAlreadyExists";
        mysqli_stmt_close($stmt);
        exit();
    } else {

        // CHECK IF RULE'S CATEGORY EXISTS
        mysqli_stmt_close($stmt);
        $sqlCheckCat = "SELECT * FROM category WHERE categoryID = ?;";
        $stmtCheckCat = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtCheckCat, $sqlCheckCat)) {
            echo ("error=stmtFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmtCheckCat, "i", $catID);
        mysqli_stmt_execute($stmtCheckCat);

        // echo "HERE!";

        $resultDataCheckCat = mysqli_stmt_get_result($stmtCheckCat);
        // echo " HERE 2!";
        if ($row2 = mysqli_fetch_assoc($resultDataCheckCat)) {
            // echo " HERE 3!";

            mysqli_stmt_close($stmtCheckCat);
            $sqlInsert = "INSERT INTO rule(ruleName, ruleText, categoryID) VALUES (?, ?, ?);";
            $stmtInsert = mysqli_stmt_init($conn);
            // echo " HERE 4!";

            if (!mysqli_stmt_prepare($stmtInsert, $sqlInsert)) {
                echo ("error=stmtFailed");
                exit();
            }
            // echo " HERE 5!";

            mysqli_stmt_bind_param($stmtInsert, "ssi", $_POST["rule-name"], $_POST["rule-text"], $catID);

            // echo " HERE 6!";
            // echo ("Statement: " . $stmtInsert);

            mysqli_stmt_execute($stmtInsert);
            echo "error=none";
            mysqli_stmt_close($stmtInsert);
            exit();
        } else {
            // echo " HERE 7!";
            echo "error=categoryNotFound";
            exit();
        }
    }
} else {
    echo ("error=accessDenied");
    exit();
}
