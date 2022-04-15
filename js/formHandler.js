baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");

function submitLogin() {
  const formData = new FormData(
    document.querySelector(".form-inputs.login").getElementsByTagName("form")[0]
  );

  const searchParams = new URLSearchParams();

  for (const pair of formData) {
    if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
      // window.alert("Some information are missing");
      // window.alert("Κάποια πεδία είναι κενά!");
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
          // window.alert("Δεν υπάρχει χρήστης με αυτό το όνομα / email.");
          // sweetAlertWarning(
          //   "Προσοχή!",
          //   "Δεν υπάρχει χρήστης με αυτό το όνομα / email.",
          //   "Εντάξει"
          // );
          sweetAlertError({
            text: "Δεν υπάρχει χρήστης με αυτό το όνομα / email.",
          });
          break;
        case "wrongPassword":
          // window.alert("Ο κωδικός που δώσατε είναι λάθος!");
          sweetAlertError({
            text: "Ο κωδικός που δώσατε είναι λάθος!",
          });
          break;
        default:
          break;
      }
      // console.log(`Response Text: "${text}"`);
      // window.location = `/${baseURL}`;
    })
    .catch(function (error) {
      console.log(error);
    });
}

function submitRegister() {
  //   console.log("REGISTER REQUEST");

  const formData = new FormData(
    document
      .querySelector(".form-inputs.register")
      .getElementsByTagName("form")[0]
  );

  const searchParams = new URLSearchParams();

  for (const pair of formData) {
    if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
      // window.alert("Κάποια πεδία είναι κενά!");
      // window.alert("Some information are missing");
      sweetAlertWarning({
        title: "Προσοχή!",
        text: "Κάποια πεδία είναι κενά!",
        confirmText: "Εντάξει",
      });
      return;
    }
    searchParams.append(pair[0], pair[1]);

    // console.log();
  }

  //   console.log(searchParams.get("password"));

  if (searchParams.get("password") !== searchParams.get("repeat-password")) {
    // window.alert("Passwords do not match!");
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
          // window.alert("Αυτό το email δεν είναι αποδεκτό!");
          sweetAlertError({ text: "Αυτό το email δεν είναι αποδεκτό!" });
          break;
        case "emptyInput":
          // window.alert("Κάποια πεδία είναι άδεια!");
          sweetAlertError({ text: "Αυτό το email δεν είναι αποδεκτό!" });
          break;
        case "invalidUsername":
          // window.alert("Αυτό το όνομα χρήστη δεν είναι αποδεκτό!");
          sweetAlertError({ text: "Αυτό το email δεν είναι αποδεκτό!" });
          break;
        case "userExists":
          // window.alert("Αυτό το όνομα χρήστη ή το email δεν είναι διαθέσιμο!");
          sweetAlertWarning({
            text: "Αυτό το όνομα χρήστη ή το email δεν είναι διαθέσιμο!",
          });
          break;
        default:
          // window.alert(`Υπήρξε ένα ασυνήθιστο λάθος: ${error}`);
          sweetAlertError({ text: `Υπήρξε ένα ασυνήθιστο λάθος: ${error}` });

          break;
      }
      console.log(`Response Text: ${text}`);
    })
    .catch(function (error) {
      console.log(error);
    });

  //   if (userPWD !== userRepeatPWD) {
  //     console.error("Your passwords do not match!");
  //   } else {
  //     console.log(
  //       `Username: '${userUsername}', Email: '${userEmail}', Password: '${userPWD}'`
  //     );
  //   }
}
