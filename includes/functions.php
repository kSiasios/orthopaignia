<?php
function emptyInputRegister($username, $email, $password)
{
    $result;
    if (empty($username) || empty($email) || empty($password))
        $result = true;
    else
        $result = false;
    return $result;
}

function invalidUID($username)
{
    $result;
    if (!preg_match("/^[a-zA-Z0-9_]*$/", $username))
        $result = true;
    else
        $result = false;
    return $result;
}

function invalidEmail($email)
{
    $result;
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
        header("location: ../?error=stmtFailed");
        exit();
    }
    // HASH PASSWORD
    $hash = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sss", $email, $username, $hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../?error=none");
    exit();
}