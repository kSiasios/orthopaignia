<?php
function emptyInputRegister($username, $email, $password)
{
    if (empty($username) || empty($email) || empty($password))
        $result = true;
    else
        $result = false;
    return $result;
}

function invalidUID($username)
{
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username))
        $result = true;
    else
        $result = false;
    return $result;
}

function invalidEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $result = true;
    else
        $result = false;
    return $result;
}

function uidExists($conn, $uid, $uemail)
{
    $sql = "SELECT * FROM users WHERE userUsername = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=stmtFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $uid, $uemail);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $username, $password)
{
    $sql = "INSERT INTO users (userEmail, userUsername, userPassword) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    // HASH PASSWORD
    $hash = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    if ($row = uidExists($conn, $username, $username)) {
        // USER EXISTS => CHECK PASSWORD
        // logUserIn();
        logUserIn($conn, $password, $row["userPassword"]);
    }

    echo ("error=none");
    exit();
}

function logUserIn($conn, $pwd, $pwdHash)
{
    if (password_verify($pwd, $pwdHash)) {
        session_start();
        $_SESSION['logged'] = time();

        // CHECK IF USER IS ADMIN
        $sql = "SELECT * FROM administrators WHERE (SELECT userID FROM users WHERE userUsername = ? OR userEmail = ?) = administrators.userID;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=adminFetchFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['username']);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($resultData)) {
            $_SESSION['isAdmin'] = true;
        }

        // GET USER'S USERNAME (MIGHT HAVE LOGGED IN USING EMAIL)
        // CHECK IF USER IS ADMIN
        $sql = "SELECT userUsername, userFirstName, userLastName FROM users WHERE userUsername = ? OR userEmail = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=userFetchFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['username']);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($resultData)) {
            // if ($row["userFirstName"] === "")
            //     $_SESSION['username'] = $row['userUsername'];
            // else
            //     $_SESSION['username'] = $row['userFirstName'];

            $_SESSION['username'] = $row['userUsername'];
            $_SESSION['firstname'] = $row['userFirstName'];
            $_SESSION['lastname'] = $row['userLastName'];
        }

        // print_r($_SESSION);
        // echo ("error=none");
    } else {
        echo ("error=wrongPassword");
        exit();
    }
}

function deleteRulesFunction($conn, $ruleID)
{
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
}
