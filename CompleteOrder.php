<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    
    $OID = (int)$_POST["OID"];

    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    $stmt = $conn->prepare('SELECT status from `order` where OID=:OID');
    $stmt->execute(array('OID' => $OID));
    if($stmt->fetch()['status']=='Cancel'){
        echo "完成失敗!訂單已被取消";
        exit();
    }    
    
    $time = date("Y-m-d H:i:s");
    $stmt = $conn->prepare('UPDATE `order` set status="Finished", end=:END where OID=:OID');
    $stmt->execute(array('OID' => $OID,'END' => $time));

    $stmt = $conn->prepare('SELECT balance from user where account=:account');
    $stmt->execute(array('account' => $_SESSION['account']));
    $_SESSION['balance'] = $stmt->fetch()[0];
    
?>