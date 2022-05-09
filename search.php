<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    try{
       echo $_POST['shop_name'].'<br>';
       echo $_POST['distance'].'<br>';
       echo $_POST['lowerbound'].'<br>';
       echo $_POST['upperbound'].'<br>';
       echo $_POST['meal_name'].'<br>';
       echo $_POST['catogory'].'<br>';
    }
    catch (Exception $e){
        
    }
?>