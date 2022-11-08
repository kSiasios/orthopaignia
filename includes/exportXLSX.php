<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}
require_once "db.info.php";
require_once "functions.php";

function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
        $str = '"' . str_replace('"', '""', $str) . '"';
    }
}

$filename = "orthopaignia_data_" . date('d-m-Y') . ".csv";

$fields = array("ID", "ΟΝΟΜΑ", "ΕΠΩΝΥΜΟ", "E-MAIL", "ΟΝΟΜΑ ΧΡΗΣΤΗ", "ΕΚΠΑΙΔΕΥΣΗ");

// CSV Format
$excelData = implode(", ", array_values($fields)) . "\n";
// FETCH USERDATA FROM DATABASE
$sql = "SELECT * FROM users ORDER BY userID ASC;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo ("error=stmtFailed");
    exit();
}

mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

$users = array();

while ($row = mysqli_fetch_assoc($resultData)) {
    // FETCH DATA FOR EACH INDIVIDUAL
    $lineData = array(
        $row["userID"],
        decrypt($row["userFirstName"]),
        decrypt($row["userLastName"]),
        decrypt($row["userEmail"]),
        decrypt($row["userUsername"]),
        convertEducationToReadable($row["userEducation"])
    );
    array_walk($lineData, 'filterData');
    $excelData .= implode(", ", array_values($lineData)) . "\n";
    array_push($users, $lineData);
}

mysqli_stmt_close($stmt);

// FOREACH QUIZ, FETCH USER EVALUATIONS
// FOR IT TO HAVE THE BELOW STRUCTURE
// |         | USER   | GRADE_1 | GRADE_1 |
// | STAGE_1 |
// |         | USER_1 | GRADE_1 | GRADE_2 |
// |         | USER_2 | GRADE_1 | GRADE_2 |
// | STAGE_2 |
// |         | USER_1 | GRADE_1 | GRADE_2 |
// |         | USER_2 | GRADE_1 | GRADE_2 |

$fields2 = array("ΕΡΩΤΗΜΑΤΟΛΟΓΙΟ", "ΧΡΗΣΤΗΣ", "ΠΡΩΤΗ ΠΡΟΣΠΑΘΕΙΑ", "", "", "", "ΤΕΛΕΥΤΑΙΑ ΠΡΟΣΠΑΘΕΙΑ", "", "", "");
$fields3 = array("", "", "ΗΜΕΡΟΜΗΝΙΑ", "ΠΟΣΟΣΤΟ ΕΠΙΤΥΧΙΑΣ", "ΧΡΟΝΟΣ ΟΛΟΚΛΗΡΩΣΗΣ", "ΧΡΟΝΟΣ ΜΕΛΕΤΗΣ", "ΗΜΕΡΟΜΗΝΙΑ", "ΠΟΣΟΣΤΟ ΕΠΙΤΥΧΙΑΣ", "ΧΡΟΝΟΣ ΟΛΟΚΛΗΡΩΣΗΣ", "ΧΡΟΝΟΣ ΜΕΛΕΤΗΣ");

$excelData .= "\n\n";
$excelData .= implode(", ", array_values($fields2)) . "\n";
$excelData .= implode(", ", array_values($fields3)) . "\n";

// FETCH ALL DATA
$sqlQuizzes = "SELECT * FROM quizzes;";
$stmtQuizzes = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtQuizzes, $sqlQuizzes)) {
    echo ("error=stmtFailed");
    exit();
}
mysqli_stmt_execute($stmtQuizzes);

$quizzesResultData = mysqli_stmt_get_result($stmtQuizzes);

$quizzes = array();

while ($rowQuizzes = mysqli_fetch_assoc($quizzesResultData)) {
    array_push($quizzes, $rowQuizzes);
}

mysqli_stmt_close($stmtQuizzes);

$sqlEvals = "SELECT * FROM evaluations;";
$stmtEvals = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtEvals, $sqlEvals)) {
    echo ("error=stmtFailed");
    exit();
}
mysqli_stmt_execute($stmtEvals);

$evalsResultData = mysqli_stmt_get_result($stmtEvals);

$evals = array();

while ($rowEvals = mysqli_fetch_assoc($evalsResultData)) {
    array_push($evals, $rowEvals);
}

mysqli_stmt_close($stmtEvals);

$sqlGrades = "SELECT * FROM grades;";
$stmtGrades = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmtGrades, $sqlGrades)) {
    echo ("error=stmtFailed");
    exit();
}
mysqli_stmt_execute($stmtGrades);

$gradesResultData = mysqli_stmt_get_result($stmtGrades);

$grades = array();

while ($rowGrades = mysqli_fetch_assoc($gradesResultData)) {
    array_push($grades, $rowGrades);
}

mysqli_stmt_close($stmtGrades);

foreach ($quizzes as $quiz) {
    $emptyRow = array("");
    $excelData .= implode(", ", array_values($emptyRow)) . "\n";

    $quizTitle = array($quiz["quizTitle"]);
    $excelData .= implode(", ", array_values($quizTitle)) . "\n";

    foreach ($evals as $eval) {
        if ($quiz["quizID"] == $eval["quizID"]) {
            $connectedUser = "";
            foreach ($users as $user) {
                if ($user[0] == $eval["userID"]) {
                    $connectedUser = $user[4];
                }
            }

            $firstAttempt = array();
            $latestAttempt = array();

            foreach ($grades as $grade) {
                if ($grade["gradeID"] == $eval["firstAttempt"]) {
                    $firstAttempt = $grade;
                }

                if ($grade["gradeID"] == $eval["latestAttempt"]) {
                    $latestAttempt = $grade;
                }
            }

            $userData = array("", $connectedUser, $firstAttempt["gradeDate"], ($firstAttempt["successRatio"] * 100) . "%", $firstAttempt["answerTime"], $firstAttempt["studyTime"], $latestAttempt["gradeDate"], ($latestAttempt["successRatio"] * 100) . "%", $latestAttempt["answerTime"], $latestAttempt["studyTime"]);
            $excelData .= implode(", ", array_values($userData)) . "\n";
        }
    }
}

// HEADERS FOR DOWNLOADING
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

echo $excelData;

exit;
