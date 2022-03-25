baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");

function logoutHandler() {
  console.log(sessionStorage);
  console.log("CLEARING SESSION");
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
