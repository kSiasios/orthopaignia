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
  //   console.log("drag started");
  this.classList.add("hold");
  setTimeout(() => {
    this.classList.add("invisible");
  }, 0);
  currentAnswer = event.target;
}

function dragEnd(event) {
  //   console.log("drag ended");
  this.classList.remove("hold", "invisible");
}

function dragOver(event) {
  event.preventDefault();
  //   console.log("drag over");
  //   this.classList.add("hovered");
}
function dragEnter(event) {
  event.preventDefault();
  empty.classList.add("hovered");
  //   console.log("drag enter");
}
function dragLeave() {
  empty.classList.remove("hovered");
  //   console.log("drag leave");
}
function dragDrop(event) {
  //   console.log("drag drop");
  console.log(event);
  const previousAnswer = event.target;

  //   remove(previousAnswer);

  //   console.log("Node to remove");
  //   console.log(empty);
  //   console.log(previousAnswer);
  if (empty != previousAnswer) {
    empty.removeChild(previousAnswer);
    answersContainer.appendChild(previousAnswer);
  }
  empty.appendChild(currentAnswer);
}
