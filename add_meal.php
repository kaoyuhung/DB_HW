<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbuserpassword = '';
    try{
        echo $_POST["meal_name"]."<br>";
        echo $_FILES["image"]["name"]."\n";
        echo $_FILES["image"]["type"]."\n";
        
    }
    catch (Exception $e){
        echo $e->getMessage();
    }

?>