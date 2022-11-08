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

function deleteRule($conn, $ruleID)
{
    // DELETE RULE
    $sqlDeleteRule = "DELETE FROM rules WHERE ruleID = ?;";
    $stmtDeleteRule = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtDeleteRule, $sqlDeleteRule)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtDeleteRule, "i", $ruleID);
    mysqli_stmt_execute($stmtDeleteRule);
    mysqli_stmt_close($stmtDeleteRule);
}

function deleteQuestion($conn, $questionID)
{
    $sqlGetAnswers = "SELECT * FROM answers WHERE questionID = ?;";
    $stmtGetAnswers = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtGetAnswers, $sqlGetAnswers)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtGetAnswers, "i", $questionID);
    mysqli_stmt_execute($stmtGetAnswers);

    $resultData = mysqli_stmt_get_result($stmtGetAnswers);
    while ($row = mysqli_fetch_assoc($resultData)) {
        deleteAnswer($conn, $row["answerID"]);
    }

    // DELETE ALL QUESTIONS THAT USE THIS RULE
    $sqlDeleteQuestion = "DELETE FROM questions WHERE questionID = ?;";
    $stmtDeleteQuestion = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtDeleteQuestion, $sqlDeleteQuestion)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtDeleteQuestion, "i", $questionID);
    mysqli_stmt_execute($stmtDeleteQuestion);
    mysqli_stmt_close($stmtDeleteQuestion);
}

function deleteAnswer($conn, $answerID)
{
    $sqlDeleteAnswers = "DELETE FROM answers WHERE answerID = ?;";
    $stmtDeleteAnswers = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtDeleteAnswers, $sqlDeleteAnswers)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtDeleteAnswers, "i", $answerID);
    mysqli_stmt_execute($stmtDeleteAnswers);
    mysqli_stmt_close($stmtDeleteAnswers);
}

function deleteEvaluation($conn, $evalID)
{
    // DELETE CONNECTED GRADES
    $sqlGetGrades = "SELECT * FROM grades WHERE evaluationID = ?;";
    $stmtGetGrades = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtGetGrades, $sqlGetGrades)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtGetGrades, "i", $evalID);
    mysqli_stmt_execute($stmtGetGrades);

    $resultData = mysqli_stmt_get_result($stmtGetGrades);
    while ($row = mysqli_fetch_assoc($resultData)) {
        deleteGrade($conn, $row["gradeID"]);
    }

    mysqli_stmt_close($stmtGetGrades);

    // DELETE EVALUATION
    $sqlDeleteEval = "DELETE FROM evaluations WHERE evaluationID = ?;";
    $stmtDeleteEval = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtDeleteEval, $sqlDeleteEval)) {
        echo ("error=deleteEvaluationsFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmtDeleteEval, "i", $evalID);
    mysqli_stmt_execute($stmtDeleteEval);
    mysqli_stmt_close($stmtDeleteEval);
}

function deleteGrade($conn, $gradeID)
{
    $sqlDeleteGrades = "DELETE FROM grades WHERE gradeID = ?;";
    $stmtDeleteGrades = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtDeleteGrades, $sqlDeleteGrades)) {
        echo ("error=deleteGradesFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmtDeleteGrades, "i", $gradeID);
    mysqli_stmt_execute($stmtDeleteGrades);
    mysqli_stmt_close($stmtDeleteGrades);
}

function deleteEvaluationsForUser($conn, $userID)
{
    $sqlUserEval = "SELECT * FROM evaluations WHERE userID = ?;";
    $stmtUserEval = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUserEval, $sqlUserEval)) {
        echo ("error=evaluationsDeletionFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmtUserEval, "i", $userID);
    mysqli_stmt_execute($stmtUserEval);

    $evaluations = mysqli_stmt_get_result($stmtUserEval);
    while ($rowEval = mysqli_fetch_assoc($evaluations)) {
        deleteEvaluation($conn, $rowEval["evaluationID"]);
    }
}

function deleteEvaluationsForQuiz($conn, $quizID)
{
    $sqlUserEval = "SELECT * FROM evaluations WHERE quizID = ?;";
    $stmtUserEval = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUserEval, $sqlUserEval)) {
        echo ("error=evaluationsDeletionFailed");
        exit();
    }
    mysqli_stmt_bind_param($stmtUserEval, "i", $quizID);
    mysqli_stmt_execute($stmtUserEval);

    $evaluations = mysqli_stmt_get_result($stmtUserEval);
    while ($rowEval = mysqli_fetch_assoc($evaluations)) {
        deleteEvaluation($conn, $rowEval["evaluationID"]);
    }
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

function checkForeignKey($conn, $check)
{
    if ($check) {
        # code...
        $sql = "SET FOREIGN_KEY_CHECKS = 1;";
    } else {
        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
    }

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function fetchUserData($conn, $userID)
{
    // GET USER DATA
    $sql = "SELECT * FROM users WHERE userID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ("error=noUsersFound");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $responseData = array();

    if ($row = mysqli_fetch_assoc($resultData)) {
        $row["userFirstName"] = decrypt($row["userFirstName"]);
        $row["userLastName"] = decrypt($row["userLastName"]);
        $row["userUsername"] = decrypt($row["userUsername"]);
        $row["userEmail"] = decrypt($row["userEmail"]);

        $noPass = array();
        foreach ($row as $key => $value) {
            if ($key == "userPassword") {
                continue;
            }
            $noPass[$key] = $value;
        }

        $responseData["user"] = $noPass;

        $sqlEvals = "SELECT * FROM evaluations WHERE userID = ?;";
        $stmtEvals = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtEvals, $sqlEvals)) {
            echo ("error=noUsersFound");
            exit();
        }
        mysqli_stmt_bind_param($stmtEvals, "i", $userID);
        mysqli_stmt_execute($stmtEvals);

        $resultDataEvals = mysqli_stmt_get_result($stmtEvals);

        $evaluations = array();
        // FETCH CONNECTED EVALUATIONS
        while ($rowEvals = mysqli_fetch_assoc($resultDataEvals)) {

            $evaluation = array();
            $evaluation["evaluation"] = $rowEvals;
            $grades = array();
            //      FOR EACH EVALUATION FETCH GARDES
            $sqlGrades = "SELECT * FROM grades WHERE gradeID = ? OR gradeID = ?;";
            $stmtGrades = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmtGrades, $sqlGrades)) {
                echo ("error=noUsersFound");
                exit();
            }
            mysqli_stmt_bind_param($stmtGrades, "ii", $rowEvals['firstAttempt'], $rowEvals['latestAttempt']);
            mysqli_stmt_execute($stmtGrades);

            $resultDataGrades = mysqli_stmt_get_result($stmtGrades);
            while ($rowGrades = mysqli_fetch_assoc($resultDataGrades)) {
                array_push($grades, $rowGrades);
            }
            $evaluation["grades"] = $grades;
            //      PUSH EVALUATIONS TO A NEW ARRAY CALLED EVALUATIONS
            array_push($evaluations, $evaluation);
        }

        $responseData["user"]["evaluations"] = $evaluations;

        return json_encode($responseData);
    } else {
        return "error=noUsersFound";
    }
}

function debugEcho($reset = false)
{
    if ($reset) {
        $_SESSION["debugLine"] = 1;
    }
    echo "CHECKPOINT " . $_SESSION["debugLine"] . "\n";
    $_SESSION["debugLine"]++;
}

function getEvaluations($conn, $userID, $quizID)
{
    $sqlGetEvaluations = "SELECT * FROM evaluations WHERE userID = ? AND quizID = ?;";
    $stmtGetEvaluations = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetEvaluations, $sqlGetEvaluations)) {
        echo '{"error": "stmtFailed"}';
        exit();
    }

    mysqli_stmt_bind_param($stmtGetEvaluations, "ii", $userID, $quizID);
    mysqli_stmt_execute($stmtGetEvaluations);

    $resultData = mysqli_stmt_get_result($stmtGetEvaluations);
    if ($rowEval = mysqli_fetch_assoc($resultData)) {
        return $rowEval;
    }

    return false;
}