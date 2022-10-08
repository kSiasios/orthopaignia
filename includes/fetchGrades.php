<?php

session_start();

if (!isset($_SESSION['logged'])) {
    echo '{"error": "unauthorized"}';
    exit();
}

require_once "db.info.php";
require_once "functions.php";

if (!isset($_POST['submit']) || !isset($_POST['user'])) {
    echo '{"error": "notEnoughVariables"}';
    exit();
}

$userID = $_POST["user"];

if (!isset($_POST["evaluationID"])) {
    // fetch grades for all evaluations
    $returnData = array();
    $sqlEvals = "SELECT * FROM evaluations WHERE userID = ?;";
    $stmtEvals = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtEvals, $sqlEvals)) {
        echo ('{"error": "stmtFailed"}');
        exit();
    }
    mysqli_stmt_bind_param($stmtEvals, "i", $userID);
    mysqli_stmt_execute($stmtEvals);

    $evaluations = mysqli_stmt_get_result($stmtEvals);
    $index = 0;

    $evalData = array();
    while ($row = mysqli_fetch_assoc($evaluations)) {
        // FOREACH EVALUATION, FETCH FIRST AND LATEST ATTEMPT AND RETURN INFO
        array_push($evalData, $row);

        // FETCH FIRST ATTEMPT
        $sqlFirstAttempt = "SELECT * FROM grades WHERE gradeID = ?;";
        $stmtFirstAttempt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtFirstAttempt, $sqlFirstAttempt)) {
            echo ('{"error": "stmtFailed"}');
            exit();
        }
        mysqli_stmt_bind_param($stmtFirstAttempt, "i", $row["firstAttempt"]);
        mysqli_stmt_execute($stmtFirstAttempt);

        $firstAttempt = mysqli_stmt_get_result($stmtFirstAttempt);
        $index = 0;

        while ($row2 = mysqli_fetch_assoc($firstAttempt)) {
            // FOREACH GRADE, RETURN ITS INFO
            array_push($evalData, "{\"firstAttemptData\": $row2}");
        }

        // FETCH LATEST ATTEMPT
        $sqlLatestAttempt = "SELECT * FROM grades WHERE gradeID = ?;";
        $stmtLatestAttempt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmtLatestAttempt, $sqlLatestAttempt)) {
            echo ('{"error": "stmtFailed"}');
            exit();
        }
        mysqli_stmt_bind_param($stmtLatestAttempt, "i", $row["latestAttempt"]);
        mysqli_stmt_execute($stmtLatestAttempt);

        $latestAttempt = mysqli_stmt_get_result($stmtLatestAttempt);
        $index = 0;

        while ($row3 = mysqli_fetch_assoc($latestAttempt)) {
            // FOREACH GRADE, RETURN ITS INFO
            array_push($evalData, "{\"latestAttemptData\": $row3}");
        }
    }

    array_push($returnData, $evalData);
    array_push($returnData, '{"error": "none"}');
    $jsonData = json_encode($returnData);

    echo $jsonData;
} else {
    $evaluationID = $_POST["evaluationID"];

    $returnData = array();
    $sql = "SELECT * FROM grades WHERE evaluationID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo ('{"error": "stmtFailed"}');
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $evaluationID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $index = 0;

    while ($row = mysqli_fetch_assoc($resultData)) {
        // FOREACH GRADE, RETURN ITS INFO
        array_push($returnData, $row);
    }

    array_push($returnData, '{"error": "none"}');
    $jsonData = json_encode($returnData);

    echo $jsonData;
}
