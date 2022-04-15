<?php
if (!file("variables.env")) {
    $baseURL = file("../variables.env")[0];
} else {
    $baseURL = file("variables.env")[0];
}
// $baseURL
// echo "<script>console.log($baseURL)</script>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php
    echo $stylesheets;
    ?>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-bold-rounded/css/uicons-bold-rounded.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-bold-straight/css/uicons-bold-straight.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-straight/css/uicons-regular-straight.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css" />

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo $baseURL ?>/js/sweetAlertFunctions.js"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $baseURL ?>/images/favicon.ico">
    <title>
        <?php echo $title ?>
        - Σωστογραφία</title>
    <script>
        let baseURL = window.location.pathname.split("/")[1].replace(/(?:\r\n|\r|\n)/g, "");
    </script>
</head>