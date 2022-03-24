<?php
session_start();
// echo "Henlo";
session_unset();
session_destroy();
exit();