function logoutHandler() {
  console.log(sessionStorage);
  console.log("CLEARING SESSION");
  window.sessionStorage.clear();

  fetch("/sostografia/includes/logoutHandler.php", {
    method: "GET",
  })
    .then(function () {
      window.location = "/sostografia";
    })
    .catch(function (error) {
      console.log(error);
    });
}
