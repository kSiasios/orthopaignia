console.log("Fetching Admin's Data");

fetch("/sostografia/includes/fetchAdminData.php")
  .then((res) => {
    return res.text();
  })
  .then((text) => {
    console.log(`Server Response: ${text}`);
  })
  .catch((err) => {
    console.error(`An error occured: ${err}`);
  });
