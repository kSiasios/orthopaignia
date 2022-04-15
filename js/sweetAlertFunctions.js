function sweetAlertWarning({
  title = "Σίγουρα;",
  text = "Η διαδικασία είναι μη αναστρέψιμη!",
  confirmText = "Ναι, σίγουρα",
  cancelText = "Ακύρωση",
} = {}) {
  Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    // showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: confirmText,
    // cancelButtonText: cancelText,
  }).then(() => {
    // fetch(`/${baseURL}/includes/deleteAccount.php`)
    //     .then((res) => {
    //         return res.text();
    //     })
    //     .then((text) => {
    //         let error = text.split("=")[1];
    //         if (error === "none")
    //             window.location = `/${baseURL}`;
    //         console.log(`Server Response: ${text}`);
    //     })
    //     .catch((err) => {
    //         console.error(`An error occured: ${err}`);
    //     });
  });
}

function sweetAlertError({
  title = "Κάτι πήγε στραβά!",
  text = "Συνέβη ένα άγνωστο σφάλμα!",
  confirmText = "Εντάξει",
  cancelText = "Ακύρωση",
} = {}) {
  Swal.fire({
    title: title,
    text: text,
    icon: "error",
    // showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: confirmText,
    // cancelButtonText: cancelText,
  }).then(() => {
    // fetch(`/${baseURL}/includes/deleteAccount.php`)
    //     .then((res) => {
    //         return res.text();
    //     })
    //     .then((text) => {
    //         let error = text.split("=")[1];
    //         if (error === "none")
    //             window.location = `/${baseURL}`;
    //         console.log(`Server Response: ${text}`);
    //     })
    //     .catch((err) => {
    //         console.error(`An error occured: ${err}`);
    //     });
  });
}
