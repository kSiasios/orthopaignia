<?php
session_start();

require_once "db.info.php";
require_once "functions.php";

if (isset($_SESSION['logged']) && isset($_SESSION['username']) & isset($_POST["submit"])) {
    // CHECK IF USER EXISTS
    if ($row = uidExists($conn, $_SESSION["username"], $_SESSION["username"])) {

        // USER EXISTS => DELETE USER
        // DELETE EVALUATIONS
        // // DELETE GRADES
        // $sqlUserEval = "SELECT * FROM evaluations WHERE userID = ?;";
        // $stmtUserEval = mysqli_stmt_init($conn);
        // if (!mysqli_stmt_prepare($stmtUserEval, $sqlUserEval)) {
        //     echo ("error=evaluationsDeletionFailed");
        //     exit();
        // }
        // mysqli_stmt_bind_param($stmtUserEval, "i", $row['userID']);
        // mysqli_stmt_execute($stmtUserEval);

        // $evaluations = mysqli_stmt_get_result($stmtUserEval);
        // while ($rowEval = mysqli_fetch_assoc($evaluations)) {
        //     // echo "HERE 1/2\n";
        //     // $sqlDeleteGrades = "DELETE FROM grades WHERE evaluationID = ?;";
        //     // $stmtDeleteGrades = mysqli_stmt_init($conn);
        //     // if (!mysqli_stmt_prepare($stmtDeleteGrades, $sqlDeleteGrades)) {
        //     //     echo ("error=deleteGradesFailed");
        //     //     exit();
        //     // }
        //     // mysqli_stmt_bind_param($stmtDeleteGrades, "i", $rowEval["evaluationID"]);
        //     // mysqli_stmt_execute($stmtDeleteGrades);
        //     // mysqli_stmt_close($stmtDeleteGrades);
        //     deleteEvaluation($conn, $rowEval["evaluationID"]);
        // }
        deleteEvaluationsForUser($conn, $row['userID']);

        // $sqlDeleteEval = "DELETE FROM evaluations WHERE userID = ?;";
        // $stmtDeleteEval = mysqli_stmt_init($conn);
        // if (!mysqli_stmt_prepare($stmtDeleteEval, $sqlDeleteEval)) {
        //     echo ("error=deleteEvaluationsFailed");
        //     exit();
        // }
        // mysqli_stmt_bind_param($stmtDeleteEval, "i", $row['userID']);
        // mysqli_stmt_execute($stmtDeleteEval);
        // mysqli_stmt_close($stmtDeleteEval);

        // // DELETE FROM ADMINISTRATORS
        $sql = "DELETE FROM administrators WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=administratorsDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        // // FINALLY DELETE FROM USERS
        $sql = "DELETE FROM users WHERE userID = ?;";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=userDeletionFailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $row['userID']);
        mysqli_stmt_execute($stmt);

        session_unset();
        session_destroy();

        echo ("error=none");
        exit();
    }
} else {
    echo ("error=notLoggedOrNoUsername");
}
