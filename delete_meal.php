<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbpassword = '';
    // echo $_POST["store_name"];
    // echo $_POST["meal_name"];
    try{
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("DELETE from meal where store=:store_name and meal_name=:meal_name");
        $stmt->execute(array('meal_name' => $_POST["meal_name"], 'store_name' =>  $_POST["store_name"]));
        echo "already delete :(";
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>