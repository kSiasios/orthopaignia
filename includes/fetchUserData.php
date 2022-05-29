<?php

session_start();

if (!isset($_SESSION['logged']) || !isset($_SESSION['isAdmin'])) {
    echo "error=unauthorized";
    exit();
}

require_once "db.info.php";
require_once "functions.php";


// // GET USER DATA
// $sql = "SELECT * FROM users WHERE userID = ?;";
// $stmt = mysqli_stmt_init($conn);
// if (!mysqli_stmt_prepare($stmt, $sql)) {
//     echo ("error=noUsersFound");
//     exit();
// }
// mysqli_stmt_bind_param($stmt, "s", $_POST['user']);
// mysqli_stmt_execute($stmt);

// $resultData = mysqli_stmt_get_result($stmt);

// $responseData = array();

// if ($row = mysqli_fetch_assoc($resultData)) {

//     // $userFirstName = decrypt($row["userFirstName"]);
//     // $userLastName = decrypt($row["userLastName"]);
//     // $userUsername = decrypt($row["userUsername"]);
//     // $userEmail = decrypt($row["userEmail"]);
//     $row["userFirstName"] = decrypt($row["userFirstName"]);
//     $row["userLastName"] = decrypt($row["userLastName"]);
//     $row["userUsername"] = decrypt($row["userUsername"]);
//     $row["userEmail"] = decrypt($row["userEmail"]);

//     $noPass = array();
//     foreach ($row as $key => $value) {
//         // $arr[3] will be updated with each value from $arr...
//         // echo "{$key} => {$value} ";
//         // print_r($arr);
//         if ($key == "userPassword") {
//             # code...
//             continue;
//         }
//         $noPass[$key] = $value;
//         // array_push($noPass, $key);
//     }

//     // array_push($responseData, $noPass);
//     $responseData["user"] = $noPass;

//     $sqlEvals = "SELECT * FROM evaluations WHERE userID = ?;";
//     $stmtEvals = mysqli_stmt_init($conn);
//     if (!mysqli_stmt_prepare($stmtEvals, $sqlEvals)) {
//         echo ("error=noUsersFound");
//         exit();
//     }
//     mysqli_stmt_bind_param($stmtEvals, "s", $_POST['user']);
//     mysqli_stmt_execute($stmtEvals);

//     $resultDataEvals = mysqli_stmt_get_result($stmtEvals);

//     $evaluations = array();
//     // FETCH CONNECTED EVALUATIONS
//     while ($rowEvals = mysqli_fetch_assoc($resultDataEvals)) {

//         $evaluation = array();
//         // array_push($evaluation, $rowEvals);
//         $evaluation["evaluation"] = $rowEvals;
//         $grades = array();
//         //      FOR EACH EVALUATION FETCH GARDES
//         $sqlGrades = "SELECT * FROM grades WHERE gradeID = ? OR gradeID = ?;";
//         $stmtGrades = mysqli_stmt_init($conn);
//         if (!mysqli_stmt_prepare($stmtGrades, $sqlGrades)) {
//             echo ("error=noUsersFound");
//             exit();
//         }
//         mysqli_stmt_bind_param($stmtGrades, "ii", $rowEvals['firstAttempt'], $rowEvals['latestAttempt']);
//         mysqli_stmt_execute($stmtGrades);

//         $resultDataGrades = mysqli_stmt_get_result($stmtGrades);
//         while ($rowGrades = mysqli_fetch_assoc($resultDataGrades)) {
//             array_push($grades, $rowGrades);
//         }
//         // array_push($evaluation, $grades);
//         $evaluation["grades"] = $grades;
//         //      PUSH EVALUATIONS TO A NEW ARRAY CALLED EVALUATIONS
//         array_push($evaluations, $evaluation);
//     }

//     // array_push($responseData, ($evaluations));
//     $responseData["user"]["evaluations"] = $evaluations;


//     echo json_encode($responseData);
//     // echo '{"firstName": "' . $userFirstName . '","lastName": "' . $userLastName . '","username": "' . $userUsername . '","email": "' . $userEmail . '"}';
// } else {
//     echo "error=noUsersFound";
// }

if (isset($_POST["multiple"])) {
    if ($_POST["multiple"] == "true") {
        // FETCH DATA FOR ALL THE USERS
        $sql = "SELECT * FROM users";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo ("error=noUsersFound");
            exit();
        }

        mysqli_stmt_execute($stmt);

        $resultData = mysqli_stmt_get_result($stmt);

        $responseData = array();

        while ($row = mysqli_fetch_assoc($resultData)) {
            array_push($responseData, json_decode(fetchUserData($conn, $row["userID"])));
        }

        echo json_encode($responseData);
        exit();
    }
}

echo fetchUserData($conn, $_POST["user"]);
exit();
