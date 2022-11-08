baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");

function logoutHandler() {
  console.log(sessionStorage);
  console.log("CLEARING SESSION");

  localStorage.clear();
  window.sessionStorage.clear();

  fetch(`/${baseURL}/includes/logoutHandler.php`, {
    method: "GET",
  })
    .then(function () {
      window.location = `/${baseURL}`;
    })
    .catch(function (error) {
      console.log(error);
    });
}

function replaceSpecialCharacters(str) {
  return str.replaceAll('"', '"').replaceAll("\n", "");
}

function convertEducationToReadable(level) {
  switch (level) {
    case "3":
      return "Γ' Δημοτικού";
      break;
    case "4":
      return "Δ' Δημοτικού";
      break;
    case "5":
      return "Ε' Δημοτικού";
      break;
    case "6":
      return "ΣΤ' Δημοτικού";
      break;
    case "other":
      return "Δευτεροβάθμια";
      break;
    default:
      return "error";
      break;
  }
}
