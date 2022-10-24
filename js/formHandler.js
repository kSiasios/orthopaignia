baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");

function submitLogin() {
  const formData = new FormData(
    document.querySelector(".form-inputs.login").getElementsByTagName("form")[0]
  );

  const searchParams = new URLSearchParams();

  for (const pair of formData) {
    if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
      sweetAlertWarning({
        title: "Προσοχή!",
        text: "Κάποια πεδία είναι κενά!",
        confirmText: "Εντάξει",
      });
      return;
    }
    searchParams.append(pair[0], pair[1]);
  }

  searchParams.append("submit", "submit");

  fetch(`/${baseURL}/includes/loginHandler.php`, {
    method: "POST",
    body: searchParams,
  })
    .then(function (response) {
      return response.text();
    })
    .then(function (text) {
      let error = text.split("=")[1];
      switch (error) {
        case "none":
          window.location = `/${baseURL}`;
          break;
        case "userDoesNotExist":
          sweetAlertError({
            text: "Δεν υπάρχει χρήστης με αυτό το όνομα / email.",
          });
          break;
        case "wrongPassword":
          sweetAlertError({
            text: "Ο κωδικός που δώσατε είναι λάθος!",
          });
          break;
        default:
          break;
      }
    })
    .catch(function (error) {
      console.log(error);
    });
}

function submitRegister() {
  const formData = new FormData(
    document
      .querySelector(".form-inputs.register")
      .getElementsByTagName("form")[0]
  );

  const searchParams = new URLSearchParams();

  for (const pair of formData) {
    if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
      sweetAlertWarning({
        title: "Προσοχή!",
        text: "Κάποια πεδία είναι κενά!",
        confirmText: "Εντάξει",
      });
      return;
    }
    searchParams.append(pair[0], pair[1]);
  }

  if (searchParams.get("password") !== searchParams.get("repeat-password")) {
    sweetAlertWarning({
      title: "Προσοχή!",
      text: "Οι κωδικοί δεν είναι ίδιοι. Ελέγξτε ότι επαναλάβατε τον κωδικό σας σωστά!",
      confirmText: "Εντάξει",
    });
    return;
  }

  searchParams.append("submit", "submit");

  fetch(`/${baseURL}/includes/registerHandler.php`, {
    method: "POST",
    body: searchParams,
  })
    .then(function (response) {
      return response.text();
    })
    .then(function (text) {
      let error = text.split("=")[1];

      switch (error) {
        case "none":
          window.location = `/${baseURL}`;
          break;
        case "invalidEmail":
          sweetAlertError({ text: "Αυτό το email δεν είναι αποδεκτό!" });
          break;
        case "emptyInput":
          sweetAlertError({ text: "Κάποιο πεδίο είναι κενό!" });
          break;
        case "invalidUsername":
          sweetAlertError({ text: "Αυτό το όνομα χρήστη δεν είναι αποδεκτό!" });
          break;
        case "userExists":
          sweetAlertWarning({
            text: "Αυτό το όνομα χρήστη ή το email δεν είναι διαθέσιμο!",
          });
          break;
        default:
          sweetAlertError({ text: `Υπήρξε ένα ασυνήθιστο λάθος: ${error}` });

          break;
      }
      console.log(`Response Text: ${text}`);
    })
    .catch(function (error) {
      console.log(error);
    });
}
