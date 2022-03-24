<?php
session_start();
$title = "Βαθμολογία";
$stylesheets =
    '<link rel="stylesheet" href="../css/styles.css">';
include '../header.php';

// FETCH GRADES PER CAT AND PER RULE AND DISPLAY THEM
?>

<body>
    <?php include '../components/navbar.php'; ?>
    <div class="page-content">
        <div class="page-header">
            <h2>Η βαθμολογία μου</h2>
        </div>
    </div>
</body>

</html>