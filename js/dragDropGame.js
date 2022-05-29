let empty;
let answers;
let answersContainer;
let currentAnswer;
let previousAnswer;

function initializeElements() {
  setTimeout(() => {
    // const answersContainer = document.querySelector(".potential-answers");
    answersContainer = document.querySelector(".quiz-answers");
    // const answers = document.querySelectorAll(".potential-answer");
    answers = document.querySelectorAll("div[draggable=true]");
    empty = document.querySelector(".empty");

    previousAnswer = answers[0];
    currentAnswer = answers[0];

    for (const answer of answers) {
      answer.addEventListener("dragstart", dragStart);
      answer.addEventListener("dragend", dragEnd);
    }

    empty.addEventListener("dragover", dragOver);
    empty.addEventListener("dragenter", dragEnter);
    empty.addEventListener("dragleave", dragLeave);
    empty.addEventListener("drop", dragDrop);
  }, 1000);
}
// Drag Functions
function dragStart(event) {
  // console.log("start");
  this.classList.add("hold");
  setTimeout(() => {
    this.classList.add("invisible");
  }, 0);
  currentAnswer = event.target;

  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);

  document.body.style.cursor = "grabbing";
}

function dragEnd(event) {
  // console.log("end");
  this.classList.remove("hold", "invisible");
  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);

  document.body.style.cursor = "auto";
}

function dragOver(event) {
  event.preventDefault();
  //   console.log("Previous Answer: \n");
  //   console.log(previousAnswer);
}
function dragEnter(event) {
  event.preventDefault();
  empty.classList.add("hovered");
  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);
}
function dragLeave(event) {
  empty.classList.remove("hovered");
  empty.innerHTML = "";
  // currentAnswer = null;
  answersContainer.appendChild(currentAnswer);
  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);
}
function dragDrop(event) {
  empty.classList.remove("hovered");
  // const previousAnswer = event.target;

  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);
  // console.log("Current Answer: \n");
  // console.log(currentAnswer);

  if (empty != previousAnswer && previousAnswer != null) {
    // empty.removeChild(previousAnswer);
    answersContainer.appendChild(previousAnswer);
  }
  empty.appendChild(currentAnswer);
  previousAnswer = currentAnswer;
  // console.log("Previous Answer: \n");
  // console.log(previousAnswer);
}
