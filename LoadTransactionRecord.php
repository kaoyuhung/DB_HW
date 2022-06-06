<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    
    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("Select * from `transaction` WHERE user=:account order by RID");
    $stmt->execute(array('account' => $_SESSION['account']));
    if ($stmt->rowCount()==0){
        exit();
    }
    else{
        $data = $stmt->fetchAll();
        echo  json_encode($data);
    }
?>