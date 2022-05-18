let empty;
let answers;
let answersContainer;
let currentAnswer;
setTimeout(() => {
  // const answersContainer = document.querySelector(".potential-answers");
  answersContainer = document.querySelector(".quiz-answers");
  // const answers = document.querySelectorAll(".potential-answer");
  answers = document.querySelectorAll(".answer-container div[draggable=true]");
  empty = document.querySelector(".empty");

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

// Drag Functions
function dragStart(event) {
  console.log("start");
  this.classList.add("hold");
  setTimeout(() => {
    this.classList.add("invisible");
  }, 0);
  currentAnswer = event.target;
}

function dragEnd(event) {
  console.log("end");
  this.classList.remove("hold", "invisible");
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
  const previousAnswer = event.target;

  if (empty != previousAnswer) {
    empty.removeChild(previousAnswer);
    answersContainer.appendChild(previousAnswer);
  }
  empty.appendChild(currentAnswer);
}
