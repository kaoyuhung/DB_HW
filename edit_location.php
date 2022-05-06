<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbuserpassword = '';
    echo "QQQ \n aaa"; //還沒成功
    if(isset($_POST['latitude']) && isset($_POST['longitude'])){
        echo "AAA";
        try{
            echo $_POST['latitude']."<br>".$_POST['longitude'];
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
?>