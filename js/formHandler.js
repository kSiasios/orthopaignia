function submitLogin() {
  const formData = new FormData(
    document.querySelector(".form-inputs.login").getElementsByTagName("form")[0]
  );

  const searchParams = new URLSearchParams();

  for (const pair of formData) {
    if (pair[0] == "" || pair[0] == null || pair[1] == "" || pair[0] == null) {
      window.alert("Some information are missing");
      return;
    }
    searchParams.append(pair[0], pair[1]);
  }

  searchParams.append("submit", "submit");

  fetch("/sostografia/includes/loginHandler.php", {
    method: "POST",
    body: searchParams,
  })
    .then(function (response) {
      window.location = "/sostografia";
      return response.text();
    })
    .then(function (text) {
      console.log(`Response Text: ${text}`);
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
      window.alert("Some information are missing");
      return;
    }
    searchParams.append(pair[0], pair[1]);

    console.log();
  }

  //   console.log(searchParams.get("password"));

  if (searchParams.get("password") !== searchParams.get("repeat-password")) {
    window.alert("Passwords do not match!");
    return;
  }

  searchParams.append("submit", "submit");

  fetch("/sostografia/includes/registerHandler.php", {
    method: "POST",
    body: searchParams,
  })
    .then(function (response) {
      return response.text();
    })
    .then(function (text) {
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
