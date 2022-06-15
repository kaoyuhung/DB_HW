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
    // $stmt = $conn->prepare('SELECT shop,price,orderer from `order` where OID=:OID');
    // $stmt->execute(array('OID' => $OID));
    // $row = $stmt->fetch();
    // $val = $row[1];
    // $shop = $row[0];
    // $orderer = $row[2];
    // $account = $_SESSION['account'];

    // $stmt = $conn->prepare('SELECT owner from `store` where store_name=:shop');
    // $stmt->execute(array('shop' => $shop));
    // $shopowner = $stmt->fetch()[0];
  
    // while(1){
    //     $RID = rand(0,1000000);
    //     $stmt = $conn->prepare('SELECT * from `transaction` where RID=:RID');
    //     $stmt->execute(array('RID' =>$RID));
    //     if($stmt->rowCount()==0){
    //         break;
    //     }
    // }
    
    // $stmt = $conn->prepare('INSERT INTO `transaction` (RID,user,type,time,trader,amount_change) values 
    //                        (:RID,:account,:type,:time,:trader,:val)');
    // $stmt->execute(array('RID' => $RID,'account' => $orderer, 'type' => 'Payment','time'=>$time,'trader'=>$shop,'val'=>'-'.$val));

    
    // while(1){
    //     $RID = rand(0,1000000);
    //     $stmt = $conn->prepare('SELECT * from `transaction` where RID=:RID');
    //     $stmt->execute(array('RID' =>$RID));
    //     if($stmt->rowCount()==0){
    //         break;
    //     }
    // }
    
    // $stmt = $conn->prepare('SELECT name from `user` where account=:account');
    // $stmt->execute(array('account'=>$orderer));
    // $orderer_name = $stmt->fetch()[0];
    
    // $stmt = $conn->prepare('INSERT INTO `transaction` (RID,user,type,time,trader,amount_change) values 
    //                        (:RID,:account,:type,:time,:trader,:val)');
    // $stmt->execute(array('RID' => $RID,'account' => $shopowner, 'type' => 'Receive','time'=>$time,'trader'=>$orderer_name,'val'=>'+'.$val));
?>