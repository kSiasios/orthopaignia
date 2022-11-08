function fillNewQuestion(
  questionText = "Ποιά είναι η σωστή ορθογραφία της παρακάτω λέξης;",
  questionType = "drag-drop",
  givenSection = "Ο ταραχοπ___ός",
  quiz = "2",
  rightAnswer = "οι",
  wrong1 = "ει",
  wrong2 = "υ",
  wrong3 = "η"
) {
  const event = new Event("change");

  document.querySelector("[name='choose-question-type']").value = questionType;
  document.querySelector("[name='choose-question-type']").dispatchEvent(event);

  let form;

  if (questionType == "drag-drop") {
    form = document.querySelector("#form-drag-drop");
    form
      .querySelector("[name='question-answer-text']")
      .setAttribute("value", givenSection);
  } else {
    form = document.querySelector("#form-multiple-choice");
  }

  form
    .querySelector("[name='question-text']")
    .setAttribute("value", questionText);
  form.querySelector("#question-quiz").value = quiz;
  form
    .querySelector("[name='right-answer-text']")
    .setAttribute("value", rightAnswer);
  form
    .querySelector("[name='wrong-answer-1-text']")
    .setAttribute("value", wrong1);

  form.querySelectorAll(".form-buttons a.button")[0].click();
  form
    .querySelector("[name='wrong-answer-2-text']")
    .setAttribute("value", wrong2);

  form.querySelectorAll(".form-buttons a.button")[0].click();
  form
    .querySelector("[name='wrong-answer-3-text']")
    .setAttribute("value", wrong3);
}
