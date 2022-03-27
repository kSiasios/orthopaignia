<?php

if (!file("variables.env")) {
    $baseURL = file("../variables.env")[0];
} else {
    $baseURL = file("variables.env")[0];
}

// echo "HENLO!!";
header("location: " . $baseURL);
