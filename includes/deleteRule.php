<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    // header("location: " . $baseURL);
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";


if (!isset($_POST['submit']) || !isset($_POST['ruleID'])) {
    // header("location: " . $baseURL);
    echo "error=notEnoughVariables";
    exit();
}

$ruleID = $_POST['ruleID'];

// $sql = "SELECT * FROM category;";
// $stmt = mysqli_stmt_init($conn);
// if (!mysqli_stmt_prepare($stmt, $sql)) {
//     echo ("error=stmtFailed");
//     exit();
// }

// FIRST, NEED TO DELETE ALL ENTRIES THAT USE THIS RULE
// DELETE ALL QUESTIONS THAT USE THIS RULE
// FOREACH QUESTION, DELETE ALL ANSWERS

// $sql = "SELECT * FROM questions WHERE questions.ruleID = ?;";
// $stmt = mysqli_stmt_init($conn);
// if (!mysqli_stmt_prepare($stmt, $sql)) {
//     echo ("error=stmtFailed");
//     exit();
// }

$sqlGetQuestions = "SELECT * FROM questions WHERE questions.ruleID = ?;";
$stmtGetQuestions = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtGetQuestions, $sqlGetQuestions)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtGetQuestions, "i", $ruleID);
mysqli_stmt_execute($stmtGetQuestions);

$resultData = mysqli_stmt_get_result($stmtGetQuestions);
while ($row = mysqli_fetch_assoc($resultData)) {
    // FOREACH QUESTION, DELETE ALL ANSWERS
    $sqlDeleteAnswers = "DELETE FROM answers WHERE questionID = ?;";
    $stmtDeleteAnswers = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtDeleteAnswers, $sqlDeleteAnswers)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $row["questionID"]);
    mysqli_stmt_execute($stmtDeleteAnswers);
    mysqli_stmt_close($stmtDeleteAnswers);

    // DELETE ALL QUESTIONS THAT USE THIS RULE
    $sqlDeleteQuestion = "DELETE FROM questions WHERE questionID = ?;";
    $stmtDeleteQuestion = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtDeleteQuestion, $sqlDeleteQuestion)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtDeleteQuestion, "i", $row["questionID"]);
    mysqli_stmt_execute($stmtDeleteQuestion);
    mysqli_stmt_close($stmtDeleteQuestion);

    // $returnTxt = $returnTxt . "<div class='category'><p class='category-name'>" . $row['categoryName'] . "</p><button class='red' onclick='deleteCategory(" . $row['categoryID'] . ")'>Διαγραφή</button></div>";
}

// DELETE RULE
$sqlDeleteRule = "DELETE FROM rule WHERE ruleID = ?;";
$stmtDeleteRule = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmtDeleteRule, $sqlDeleteRule)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_bind_param($stmtDeleteRule, "i", $ruleID);
mysqli_stmt_execute($stmtDeleteRule);
mysqli_stmt_close($stmtDeleteRule);

echo "error=none";
