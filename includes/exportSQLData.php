<?php

session_start();

if (!isset($_SESSION['logged']) && !isset($_SESSION['isAdmin'])) {
    header("location: " . $baseURL);
    exit();
}

// require_once "db.info.php";



$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "orthopaignia_demo";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);


$tables = array();

$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$return = '';
$return .= 'SET FOREIGN_KEY_CHECKS=0;';

foreach ($tables as $table) {
    $result = mysqli_query($conn, "SELECT * FROM " . $table);
    $num_fields = mysqli_num_fields($result);

    $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
    $row2 = mysqli_fetch_row(mysqli_query($conn, 'SHOW CREATE TABLE ' . $table));
    $return .= "\n\n" . $row2[1] . ";\n\n";

    for ($i = 0; $i < $num_fields; $i++) {
        while ($row = mysqli_fetch_row($result)) {
            $return .= 'INSERT INTO ' . $table . ' VALUES(';
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < $num_fields - 1) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
    }
    $return .= "\n\n\n";
}

$return .= 'SET FOREIGN_KEY_CHECKS=1;';


// $handle = fopen('backup.sql', 'w+');
// fwrite($handle, $return);
// fclose($handle);

// echo $return;

// echo "success";

// $file = "/home/konstantinoss/Desktop/test.sql";
// $txt = fopen($file, "w") or die("Unable to open file!");
// // $txt = fopen($file, "w");
// fwrite($txt, $return);
// fclose($txt);

// header('Content-Description: File Transfer');
// header('Content-Disposition: attachment; filename=' . basename($file));
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: ' . filesize($file));
// header("Content-Type: text/plain");
// readfile($file);

$filename = "orthopaignia_data_" . date('d-m-Y') . ".sql";


// HEADERS FOR DOWNLOADING
header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=\"$filename\"");

$data = array();
array_push($data, $return);

echo $return;

exit;
