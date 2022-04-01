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

$alphabet = " !\"#$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~}";

$letters = "abcdef";

function randomize($string, $seed)
{
    srand($seed);
    $str = str_shuffle($string);
    return $str;
}

function level_One($message, $alphabet, $shuffled_alphabet)
{
    $lvl_one = "";
    $message_array = str_split($message);
    $i = 0;
    foreach ($message_array as $char) {
        $index = strpos($alphabet, $char);
        $lvl_one[$i] = $shuffled_alphabet[$index];
        $i++;
    }
    return $lvl_one;
}

function randomChars($num, $alphabet)
{
    $i = 0;
    $arr = "";
    for ($i = 0; $i < $num; $i++) {
        $index = rand(0, strlen($alphabet) - 1);
        $arr[$i] = $alphabet[$index];
    }
    return $arr;
}

function level_Two($message, $random_characters, $alphabet)
{
    // echo "\n\nMessage input: " . $message;
    $lvl_two = randomChars($random_characters, $alphabet);
    $arr = str_split($message);
    foreach ($arr as $char) {
        $lvl_two = $lvl_two . $char;
        $lvl_two = $lvl_two . randomChars($random_characters, $alphabet);
    }
    return $lvl_two;
}

// function encrypt($message, $alphabet, $letters)
function encrypt($message, $alphabet, $letters)
{
    $seconds = time();
    $shuffled_alphabet = randomize($alphabet, $seconds);
    // echo "Initial message: " . $message . "\n";
    $lvl_one = level_One($message, $alphabet, $shuffled_alphabet);
    // echo "********\n\n\nLevel One: " . $lvl_one . "\n\n\n********";

    $random_char_amount = rand(1, 6);
    $chance = rand(1, 100);
    $letter = "";
    if ($chance < 50) {
        $letter = $letter . $letters[$random_char_amount - 1];
    } else {
        $letter = $letter . strtoupper($letters[$random_char_amount - 1]);
    }

    $lvl_two = level_Two($lvl_one, $random_char_amount, $alphabet);
    // echo "\n\nLevel two: " . $lvl_two . "\n\n";

    $timestamp_hex = dechex($seconds);
    // $final_message = "";
    $final_message = $letter . $timestamp_hex . $lvl_two;
    return $final_message;
}

function decrypt($message, $alphabet, $letters)
{
    $chars = strpos($letters, strtolower($message[0])) + 1;
    $timestamp = hexdec(substr($message, 1, 8));

    $shuffled_alphabet = randomize($alphabet, $timestamp);
    $msg = "";
    for ($i = 9 + $chars; $i < strlen($message); $i = $i + $chars + 1) {
        $msg = $msg . $message[$i];
    }
    $final = "";
    $arr = str_split($msg);
    foreach ($arr as $char) {
        $final = $final . $alphabet[strpos($shuffled_alphabet, $char)];
    }
    return $final;
}


function getUserID($conn, $credential)
{
    $sql = "SELECT userID FROM users WHERE userUsername = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=adminFetchFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $credential, $credential);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        // if ($row["userFirstName"] === "")
        //     $_SESSION['username'] = $row['userUsername'];
        // else
        //     $_SESSION['username'] = $row['userFirstName'];

        return $row["userID"];
    } else {
        return false;
    }
    mysqli_stmt_close($stmt);
}

function getQuestionByText($conn, $text)
{
    $sqlQuestionID = "SELECT questionID FROM questions WHERE questionText = ?;";
    // echo "HERE 1.75<br/>";
    $stmtQuestionID = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtQuestionID, $sqlQuestionID)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtQuestionID, "s", $text);
    mysqli_stmt_execute($stmtQuestionID);
    // echo "HERE 2<br/>";

    $resultData = mysqli_stmt_get_result($stmtQuestionID);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row["questionID"];
    } else {
        echo '{"error":"questionIDNotFound"}';
        exit();
    }
    mysqli_stmt_close($stmtQuestionID);
}

// $message = "hellooo";

// $enc_msg = encrypt($message, $alphabet, $letters);
// echo "\n\n\nEncrypted message: " . $enc_msg;
// $dec_msg = decrypt($enc_msg, $alphabet, $letters);
// echo "\n\n\nDecrypted message: " . $dec_msg;
