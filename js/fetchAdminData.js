baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");

fetch(`/${baseURL}/includes/fetchAdminData.php`)
  .then((res) => {
    return res.text();
  })
  .then((text) => {})
  .catch((err) => {});
