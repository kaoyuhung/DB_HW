<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbuserpassword = '';
    try{
        echo $_POST["meal_name"]."<br>";
        echo $_FILES["image"]["name"]."<br>";
        echo $_FILES["image"]["type"]."<br>";
        echo $_FILES["image"]["tmp_name"]."<br>";
        $file = fopen($_FILES["image"]["tmp_name"], "rb");
        $fileContents = fread($file, filesize($_FILES["image"]["tmp_name"])); 
        fclose($file);
        $fileContents = base64_encode($fileContents);
        
        
    }
    catch (Exception $e){
        echo $e->getMessage();
    }

?>