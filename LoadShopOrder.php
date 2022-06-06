<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    
    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("Select * from `store` WHERE owner=:account");
    $stmt->execute(array('account' => $_SESSION['account']));
    if ($stmt->rowCount()==0){
        exit();
    }
    $stmt = $conn->prepare("Select * from `order` WHERE shop=:shop order by OID");
    $stmt->execute(array('shop' => $_SESSION['store_name']));
    if ($stmt->rowCount()){
        $data = $stmt->fetchAll();
        echo  json_encode($data);
    }
?>