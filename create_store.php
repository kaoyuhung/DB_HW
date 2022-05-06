<?php
    session_start();
    $_SESSION['Authenticated'] = false;
    $dbservername = 'localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    
    $name = $_POST['store_name'];
    $type = $_POST['store_type'];
    $lat = $_POST['store_lat'];
    $long = $_POST['store_long'];
    echo $name;
    echo $type;
    echo $lat;
    echo $long;
    echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$name");
        </script>
        </body>
        </html>
        EOT;

    
?>