function sweetAlertWarning({
  title = "Σίγουρα;",
  text = "Η διαδικασία είναι μη αναστρέψιμη!",
  confirmText = "Ναι, σίγουρα",
  cancelText = "Ακύρωση",
  redirect = "",
} = {}) {
  Swal.fire({
    title: title,
    text: text,
    icon: "warning",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: confirmText,
  }).then(() => {
    if (redirect != "") {
      window.location = redirect;
    }
  });
}

function sweetAlertError({
  title = "Κάτι πήγε στραβά!",
  text = "Συνέβη ένα άγνωστο σφάλμα!",
  confirmText = "Εντάξει",
  cancelText = "Ακύρωση",
  redirect = "",
} = {}) {
  Swal.fire({
    title: title,
    text: text,
    icon: "error",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: confirmText,
  }).then(() => {
    if (redirect != "") {
      window.location = redirect;
    }
  });
}
