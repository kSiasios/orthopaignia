let empty;
let answers;
let answersContainer;
let currentAnswer;
let previousAnswer;

function initializeElements() {
  setTimeout(() => {
    answersContainer = document.querySelector(".quiz-answers");
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
  this.classList.add("hold");
  setTimeout(() => {
    this.classList.add("invisible");
  }, 0);
  currentAnswer = event.target;
  document.body.style.cursor = "grabbing";
}

function dragEnd(event) {
  this.classList.remove("hold", "invisible");
  document.body.style.cursor = "auto";
}

function dragOver(event) {
  event.preventDefault();
}
function dragEnter(event) {
  event.preventDefault();
  empty.classList.add("hovered");
}
function dragLeave(event) {
  empty.classList.remove("hovered");
  empty.innerHTML = "";
  answersContainer.appendChild(currentAnswer);
}
function dragDrop(event) {
  empty.classList.remove("hovered");
  if (empty != previousAnswer && previousAnswer != null) {
    answersContainer.appendChild(previousAnswer);
  }
  empty.appendChild(currentAnswer);
  previousAnswer = currentAnswer;
}
