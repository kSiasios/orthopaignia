<?php

if (isset($_POST['submit'])) {

    require_once "db.info.php";
    require_once "functions.php";

    $questionText = $_POST["question"];
    $rightAnswer = $_POST["right-answer"];
    $ruleID = $_POST["rule"];

    $questionID;
    $answerID;

    if (!($questionText != "" && $rightAnswer != "" && $ruleID != "")) {
        echo "error=emptyInputs";
        exit();
    }

    // ----------------------------------------------------------------------------------------------
    // CREATE QUESTION
    // GET QUESTION ID
    $sqlCreateQuestion = "INSERT INTO questions(questionText, ruleID) VALUES (?, ?);";
    //   SELECT * FROM questions WHERE questionText = ?;";
    $stmtCreateQuestion = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtCreateQuestion, $sqlCreateQuestion)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtCreateQuestion, "si", $questionText, $ruleID);
    mysqli_stmt_execute($stmtCreateQuestion);
    mysqli_stmt_close($stmtCreateQuestion);

    $sqlGetQuestionID = "SELECT * FROM questions WHERE questionText = ?;";
    $stmtGetQuestionID = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmtGetQuestionID, $sqlGetQuestionID)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtGetQuestionID, "s", $questionText);
    mysqli_stmt_execute($stmtGetQuestionID);

    $resultData = mysqli_stmt_get_result($stmtGetQuestionID);
    if ($row = mysqli_fetch_assoc($resultData)) {
        $questionID = $row["questionID"];
    } else {
        echo "error=questionNotCreatedOrNoID";
        mysqli_stmt_close($stmtGetQuestionID);
        exit();
    }

    // ----------------------------------------------------------------------------------------------
    // CREATE RIGHT ANSWER
    // GET THE ID OF THE RIGHT ANSWER
    $sqlCreateRightAnswer = "INSERT INTO answers(answerText, questionID) VALUES (?, ?);";
    // SELECT answerID FROM answers WHERE answerText = ?;";
    $stmtCreateRightAnswer = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtCreateRightAnswer, $sqlCreateRightAnswer)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtCreateRightAnswer, "si", $rightAnswer, $questionID);
    mysqli_stmt_execute($stmtCreateRightAnswer);
    mysqli_stmt_close($stmtCreateRightAnswer);

    $sqlGetRightAnswerID = "SELECT answerID FROM answers WHERE answerText = ?;";
    $stmtGetRightAnswerID = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtGetRightAnswerID, $sqlGetRightAnswerID)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtGetRightAnswerID, "s", $rightAnswer);
    mysqli_stmt_execute($stmtGetRightAnswerID);
    // mysqli_stmt_close($stmtGetRightAnswerID);

    $resultAnswerID = mysqli_stmt_get_result($stmtGetRightAnswerID);
    if ($row2 = mysqli_fetch_assoc($resultAnswerID)) {
        $answerID = $row2["answerID"];
    } else {
        echo "error=rightAnswerNotCreatedOrNoID";
        mysqli_stmt_close($stmtGetRightAnswerID);
        exit();
    }

    // ----------------------------------------------------------------------------------------------
    // CREATE WRONG ANSWERS
    $sqlCreateWrongAnswers = "INSERT INTO answers(answerText, questionID) VALUES";
    $index = 0;
    $bindValues = [];
    $bindTypes = "";
    foreach ($_POST as $postVarKey => $postVarVal) {
        if (str_contains($postVarKey, "wrong-answer")) {
            if ($index != 0) {
                $sqlCreateWrongAnswers .= ",";
            }
            $sqlCreateWrongAnswers .= "(?, ?)";
            $index++;
            array_push($bindValues, $postVarVal, $questionID);
            $bindTypes .= "si";
        }
    }
    $sqlCreateWrongAnswers .= ";";

    if ($index == 0) {
        // THERE ARE NO WRONG ANSWERS
        echo "error=noWrongAnswers";
        exit();
    }

    $stmtCreateWrongAnswers = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtCreateWrongAnswers, $sqlCreateWrongAnswers)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtCreateWrongAnswers, $bindTypes, ...$bindValues);
    mysqli_stmt_execute($stmtCreateWrongAnswers);
    mysqli_stmt_close($stmtCreateWrongAnswers);
    // ----------------------------------------------------------------------------------------------
    // UPDATE QUESTION TO PROVIDE THE RIGHT ANSWER ID
    $sqlUpdateQuestion = "UPDATE questions SET answerID = ? WHERE questionID = ?;";
    $stmtUpdateQuestion = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmtUpdateQuestion, $sqlUpdateQuestion)) {
        echo ("error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmtUpdateQuestion, "ii", $answerID, $questionID);
    mysqli_stmt_execute($stmtUpdateQuestion);
    mysqli_stmt_close($stmtUpdateQuestion);

    echo "error=none";
} else {
    echo ("error=accessDenied");
    exit();
}
