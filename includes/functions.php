<?php
function emptyInputRegister($username, $email, $password, $studentName, $studentLastName, $studentGrade)
{
    if (empty($username) || empty($email) || empty($password) || empty($studentName) || empty($studentLastName) || empty($studentGrade))
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
    $sql = "SELECT * FROM users;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../?error=stmtFailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($resultData)) {
        if ($uid == decrypt($row["userUsername"]) || $uemail == decrypt(($row["userEmail"]))) {
            return $row;
        }
    }

    return false;
}

function createUser($conn, $email, $username, $password, $studentName, $studentLastname, $studentGrade)
{
    $sql = "INSERT INTO users (userEmail, userUsername, userPassword, userFirstName, userLastName, userEducation) VALUES (?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    // HASH PASSWORD
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // ENCRYPT DATA
    $encryptedEmail = encrypt($email);
    $encryptedUsername = encrypt($username);
    $encryptedStudentName = encrypt($studentName);
    $encryptedStudentLastName = encrypt($studentLastname);

    mysqli_stmt_bind_param($stmt, "ssssss", $encryptedEmail, $encryptedUsername, $hash, $encryptedStudentName, $encryptedStudentLastName, $studentGrade);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($row = uidExists($conn, $username, $username)) {
        // USER EXISTS => CHECK PASSWORD
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

        // GET USER ID
        $sql = "SELECT * FROM users;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=adminFetchFailed");
            exit();
        }
        mysqli_stmt_execute($stmt);

        $userID = -1;
        $resultData = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($resultData)) {
            if (decrypt($row["userUsername"]) == $_POST['username'] || decrypt($row["userEmail"]) == $_POST['username']) {

                $userID = $row["userID"];
                $_SESSION['username'] = decrypt($row['userUsername']);
                $_SESSION['firstname'] = decrypt($row['userFirstName']);
                $_SESSION['lastname'] = decrypt($row['userLastName']);
            }
        }

        if ($userID == -1) {
            echo "error=userNotFound";
            exit();
        }

        // CHECK IF USER IS ADMIN
        $sql = "SELECT * FROM administrators WHERE administrators.userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=adminFetchFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $userID);
        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($resultData)) {
            $_SESSION['isAdmin'] = true;
        }
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

function randomChars($num)
{
    global $alphabet;
    $i = 0;
    $arr = "";
    for ($i = 0; $i < $num; $i++) {
        $index = rand(0, strlen($alphabet) - 1);
        $arr[$i] = $alphabet[$index];
    }
    return $arr;
}

$key = "OrthopaigniaSecureKeyForEncryptionDecryption";

function encrypt($message)
{
    global $key;
    $encryptionKey = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $finalMessage = openssl_encrypt($message, 'aes-256-cbc', $encryptionKey, 0, $iv);
    return base64_encode($finalMessage . "::" . $iv);
}

function decrypt($message)
{
    global $key;
    $encryptionKey = base64_decode($key);
    list($encryptedData, $iv) = array_pad(explode("::", base64_decode($message), 2), 2, null);
    return openssl_decrypt($encryptedData, 'aes-256-cbc', $encryptionKey, 0, $iv);
}

function getUserID($conn, $credential)
{
    $sql = "SELECT * FROM users;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=adminFetchFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ii", $credential, $credential);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($resultData)) {
        if ($credential == decrypt($row["userUsername"]) || $credential == decrypt($row["userEmail"])) {

            return $row["userID"];
        }
    }
    return false;
    mysqli_stmt_close($stmt);
}

function getQuestionByText($conn, $text)
{
    $sqlQuestionID = "SELECT * FROM questions WHERE questionText = ?;";
    $stmtQuestionID = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtQuestionID, $sqlQuestionID)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtQuestionID, "s", $text);
    mysqli_stmt_execute($stmtQuestionID);

    $resultData = mysqli_stmt_get_result($stmtQuestionID);
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        return '{"error":"questionIDNotFound"}';
        exit();
    }
    mysqli_stmt_close($stmtQuestionID);
}


function updateGradePerRule($conn, $ruleID, $userID)
{
    // CHECK IF RULE EXISTS
    $sqlRuleInfo = "SELECT * FROM rule WHERE ruleID = ?;";
    $stmtRuleInfo = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtRuleInfo, $sqlRuleInfo)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtRuleInfo, "i", $ruleID);
    mysqli_stmt_execute($stmtRuleInfo);

    $resultData = mysqli_stmt_get_result($stmtRuleInfo);
    if (!mysqli_fetch_assoc($resultData)) {
        echo '{"error":"ruleNotFound", "ruleID":"' . $ruleID . '"}';
        exit();
    }
    mysqli_stmt_close($stmtRuleInfo);

    // FETCH GRADE FOREACH CONNECTED QUESTION
    $sqlConnectedQuestions = "SELECT * FROM questions WHERE ruleID = ?;";
    $stmtConnectedQuestions = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtConnectedQuestions, $sqlConnectedQuestions)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtConnectedQuestions, "i", $ruleID);
    mysqli_stmt_execute($stmtConnectedQuestions);

    $resultData = mysqli_stmt_get_result($stmtConnectedQuestions);
    $sum = 0;
    $questionCount = 0;
    while ($row = mysqli_fetch_assoc($resultData)) {

        $questionID = $row["questionID"];
        $sqlGetQuestionGrade = "SELECT * FROM gradeperquestion WHERE questionID = ? AND userID = ?;";
        $stmtGetQuestionGrade = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtGetQuestionGrade, $sqlGetQuestionGrade)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtGetQuestionGrade, "ii", $questionID, $userID);
        mysqli_stmt_execute($stmtGetQuestionGrade);
        $resultDataGetGrades = mysqli_stmt_get_result($stmtGetQuestionGrade);

        if ($rowGetGrades = mysqli_fetch_assoc($resultDataGetGrades)) {
            $sum += $rowGetGrades["relevantGrade"];
            $questionCount++;
        }

        mysqli_stmt_close($stmtGetQuestionGrade);
    }

    $newGrade = $sum / $questionCount;
    mysqli_stmt_close($stmtConnectedQuestions);

    // UPDATE RULE GRADE

    // CHECK IF THE ENTRY ALREADY EXISTS
    $sqlGetPrevGrade = "SELECT * FROM gradeperrule WHERE ruleID = ? AND userID = ?;";
    $stmtGetPrevGrade = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetPrevGrade, $sqlGetPrevGrade)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtGetPrevGrade, "ii", $ruleID, $userID);
    mysqli_stmt_execute($stmtGetPrevGrade);

    $resultData = mysqli_stmt_get_result($stmtGetPrevGrade);
    if (!mysqli_fetch_assoc($resultData)) {
        // A PREVIOUS ENTRY DOES NOT EXIST => CREATE ONE
        $sqlCreateGrade = "INSERT INTO gradeperrule(userID, ruleID) VALUES (?, ?);";
        $stmtCreateGrade = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtCreateGrade, "ii", $userID, $ruleID);
        mysqli_stmt_execute($stmtCreateGrade);
        mysqli_stmt_close($stmtCreateGrade);
    }
    mysqli_stmt_close($stmtGetPrevGrade);

    $sqlUpdate = "UPDATE gradeperrule SET grade = ? WHERE ruleID = ? AND userID = ?;";
    $stmtUpdate = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUpdate, $sqlUpdate)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtUpdate, "dii", $newGrade, $ruleID, $userID);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);
}

function updateGradePerCategory($conn, $categoryID, $userID)
{
    // CHECK IF RULE EXISTS
    $sqlCategoryInfo = "SELECT * FROM category WHERE categoryID = ?;";
    $stmtCategoryInfo = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtCategoryInfo, $sqlCategoryInfo)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtCategoryInfo, "i", $categoryID);
    mysqli_stmt_execute($stmtCategoryInfo);

    $resultData = mysqli_stmt_get_result($stmtCategoryInfo);
    if (!mysqli_fetch_assoc($resultData)) {
        echo '{"error":"categoryNotFound"}';
        exit();
    }
    mysqli_stmt_close($stmtCategoryInfo);

    // FETCH GRADE FOREACH CONNECTED QUESTION
    $sqlConnectedRules = "SELECT * FROM rule WHERE categoryID = ?;";
    $stmtConnectedRules = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtConnectedRules, $sqlConnectedRules)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtConnectedRules, "i", $categoryID);
    mysqli_stmt_execute($stmtConnectedRules);

    $resultData = mysqli_stmt_get_result($stmtConnectedRules);
    $sum = 0;
    $rulesCount = 0;
    while ($row = mysqli_fetch_assoc($resultData)) {
        $sqlGetQuestionGrade = "SELECT * FROM gradeperrule WHERE ruleID = ? AND userID = ?;";
        $stmtGetQuestionGrade = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtGetQuestionGrade, $sqlGetQuestionGrade)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtGetQuestionGrade, "ii", $row["ruleID"], $userID);
        mysqli_stmt_execute($stmtGetQuestionGrade);
        $resultDataGetGrades = mysqli_stmt_get_result($stmtConnectedRules);


        if ($rowGetGrades = mysqli_fetch_assoc($resultDataGetGrades)) {
            $sum += $rowGetGrades["grade"];
            $rulesCount++;
        }

        mysqli_stmt_close($stmtGetQuestionGrade);
    }

    $newGrade = $sum / $rulesCount;
    mysqli_stmt_close($stmtConnectedRules);

    // UPDATE RULE GRADE

    // CHECK IF THE ENTRY ALREADY EXISTS
    $sqlGetPrevGrade = "SELECT * FROM gradeperrule WHERE ruleID = ? AND userID = ?;";
    $stmtGetPrevGrade = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetPrevGrade, $sqlGetPrevGrade)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtGetPrevGrade, "ii", $ruleID, $userID);
    mysqli_stmt_execute($stmtGetPrevGrade);

    $resultData = mysqli_stmt_get_result($stmtGetPrevGrade);
    if (!mysqli_fetch_assoc($resultData)) {
        // A PREVIOUS ENTRY DOES NOT EXIST => CREATE ONE
        $sqlCreateGrade = "INSERT INTO gradepercategory(userID, categoryID) VALUES (?, ?);";
        $stmtCreateGrade = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtCreateGrade, $sqlCreateGrade)) {
            echo '{"error": "stmtFailed"}';
            exit();
        }

        mysqli_stmt_bind_param($stmtCreateGrade, "ii", $userID, $categoryID);
        mysqli_stmt_execute($stmtCreateGrade);
        mysqli_stmt_close($stmtCreateGrade);
    }
    mysqli_stmt_close($stmtGetPrevGrade);

    $sqlUpdate = "UPDATE gradepercategory SET grade = ? WHERE categoryID = ? AND userID = ?;";
    $stmtUpdate = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUpdate, $sqlUpdate)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtUpdate, "dii", $newGrade, $categoryID, $userID);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtGetPrevGrade);
}

function convertEducationToReadable($level)
{
    switch ($level) {
        case '3':
            return "Γ' Δημοτικού";
            break;
        case '4':
            return "Δ' Δημοτικού";
            break;
        case '5':
            return "Ε' Δημοτικού";
            break;
        case '6':
            return "ΣΤ' Δημοτικού";
            break;
        case 'other':
            return "Δευτεροβάθμια";
            break;

        default:
            return "error";
            break;
    }
}
