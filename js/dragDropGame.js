const answersContainer = document.querySelector(".potential-answers");
const answers = document.querySelectorAll(".potential-answer");
const empty = document.querySelector(".empty");

let currentAnswer = answers[0];

for (const answer of answers) {
  answer.addEventListener("dragstart", dragStart);
  answer.addEventListener("dragend", dragEnd);
}

empty.addEventListener("dragover", dragOver);
empty.addEventListener("dragenter", dragEnter);
empty.addEventListener("dragleave", dragLeave);
empty.addEventListener("drop", dragDrop);

// Drag Functions
function dragStart(event) {
  this.classList.add("hold");
  setTimeout(() => {
    this.classList.add("invisible");
  }, 0);
  currentAnswer = event.target;
}

function dragEnd(event) {
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
