<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $OIDs = json_decode($_POST["OID"]);
     
    for($i=0;$i<count($OIDs);$i++){
        $OID = (int)$OIDs[$i];
        $stmt = $conn->prepare("SELECT status from `order` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();
        if($row['status']!='Not Finished'){
            echo "取消失敗! 訂單已取消";
            exit(); 
        }
    }
    $time = date("Y-m-d H:i:s");
    for($i=0;$i<count($OIDs);$i++){
        $OID = (int)$OIDs[$i];
        $stmt = $conn->prepare('UPDATE `order` set status="Finished", end=:END where OID=:OID');
        $stmt->execute(array('OID' => $OID,'END' => $time));
    }
 
?>