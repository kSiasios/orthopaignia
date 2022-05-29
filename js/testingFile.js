function fillNewQuestion(
  questionText = "Ποιά είναι η σωστή ορθογραφία της παρακάτω λέξης;",
  questionType = "drag-drop",
  //   questionType = "multiple-choice",
  givenSection = "Ο ταραχοπ___ός",
  quiz = "2",
  rightAnswer = "οι",
  wrong1 = "ει",
  wrong2 = "υ",
  wrong3 = "η"
) {
  // document.querySelector("[name='question-text']").value = questionText;
  //   document.querySelector("[name='question-text']").innerText = questionText;

  //   document
  //     .querySelector("[name='choose-question-type']")
  //     .setAttribute("value", questionType);

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

  //   document.querySelector("[name='question-answer-text']").value = givenSection;
  //   document.querySelector("[name='question-answer-text']").innerText =
  //     givenSection;

  //   document.querySelector("#question-quiz").value = quiz;
  form.querySelector("#question-quiz").value = quiz;
  //   document.querySelector("#question-quiz").innerText = quiz;

  //   document.querySelector("[name='right-answer-text']").value = rightAnswer;
  //   document.querySelector("[name='right-answer-text']").innerText = rightAnswer;
  form
    .querySelector("[name='right-answer-text']")
    .setAttribute("value", rightAnswer);

  //   document.querySelector("[name='wrong-answer-1-text']").value = wrong1;
  //   document.querySelector("[name='wrong-answer-1-text']").innerText = wrong1;
  form
    .querySelector("[name='wrong-answer-1-text']")
    .setAttribute("value", wrong1);

  form.querySelectorAll(".form-buttons a.button")[0].click();
  //   document.querySelector("[name='wrong-answer-2-text']").value = wrong2;
  //   document.querySelector("[name='wrong-answer-2-text']").innerText = wrong2;
  form
    .querySelector("[name='wrong-answer-2-text']")
    .setAttribute("value", wrong2);

  form.querySelectorAll(".form-buttons a.button")[0].click();
  //   document.querySelector("[name='wrong-answer-3-text']").value = wrong3;
  //   document.querySelector("[name='wrong-answer-3-text']").innerText = wrong3;
  form
    .querySelector("[name='wrong-answer-3-text']")
    .setAttribute("value", wrong3);
}
