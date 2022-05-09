<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        // echo $_POST['shop_name'].'<br>';
        // echo $_POST['distance'].'<br>';
        // echo $_POST['lowerbound'].'<br>';
        // echo $_POST['upperbound'].'<br>';
        // echo $_POST['meal_name'].'<br>';
        // echo $_POST['catogory'].'<br>';
        $shop = $_POST['shop_name'];
        $dis = $_POST['distance'];
        $lowerbound = $_POST['lowerbound'];
        $upperbound = $_POST['upperbound'];
        $meal = $_POST['meal_name'];
        $cat = $_POST['catogory'];
        $array=array();
        if($shop==""){
            $a = "1";
        }
        else{
            $a = "store_name=:store_name";
            $array['store_name']=$shop;
        }
        $stmt = $conn->prepare("SELECT * from store where ".$a);
        $stmt->execute($array);
        echo $stmt->rowCount();
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>