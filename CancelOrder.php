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
            echo "取消失敗! 訂單已被完成或取消過";
            exit(); 
        }
    }
    
    for($j=0;$j<count($OIDs);$j++){
        $OID = (int)$OIDs[$j];
        $stmt = $conn->prepare("SELECT status,orderer,shop,price,detail from `order` where OID=:OID");
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();
        $detail = json_decode($row['detail'],true);
        for($i=1;$i<count($detail);$i++){
            $stmt = $conn->prepare('UPDATE meal set quantity=quantity+:num where meal_name=:food and store=:shop');
            $stmt->execute(array('food' => $detail[$i]["meal"],'shop' => $detail[0]["shop"],'num' => (int)$detail[$i]["quantity"]));
        }
        
        $stmt = $conn->prepare('UPDATE user set balance=balance+:num where account=:account');
        $stmt->execute(array('num' =>(int)$row['price'],'account'=>$row['orderer']));
    
        $stmt = $conn->prepare('UPDATE user set balance=balance-:num where account=
                              (SELECT account from user join store on user.account=
                               store.owner where store_name=:store)');
        $stmt->execute(array('num' =>(int)$row['price'],'store'=>$row["shop"]));
    
        $time = date("Y-m-d H:i:s");
      
        $stmt = $conn->prepare('UPDATE `order` set status="Cancel", end=:END where OID=:OID');
        $stmt->execute(array('OID' => $OID,'END' => $time));    

        
        $stmt = $conn->prepare('SELECT shop,price,orderer from `order` where OID=:OID');
        $stmt->execute(array('OID' => $OID));
        $row = $stmt->fetch();
        $val = $row[1];
        $shop = $row[0];
        $orderer = $row[2];
        $account = $_SESSION['account'];
    
        $stmt = $conn->prepare('SELECT owner from `store` where store_name=:shop');
        $stmt->execute(array('shop' => $shop));
        $shopowner = $stmt->fetch()[0];
      
        while(1){
            $RID = rand(0,1000000);
            $stmt = $conn->prepare('SELECT * from `transaction` where RID=:RID');
            $stmt->execute(array('RID' =>$RID));
            if($stmt->rowCount()==0){
                break;
            }
        }
        
        $stmt = $conn->prepare('INSERT INTO `transaction` (RID,user,type,time,trader,amount_change) values 
                               (:RID,:account,:type,:time,:trader,:val)');
        $stmt->execute(array('RID' => $RID,'account' => $orderer, 'type' => 'Receive','time'=>$time,'trader'=>$shop,'val'=>'+'.$val));
    
        
        while(1){
            $RID = rand(0,1000000);
            $stmt = $conn->prepare('SELECT * from `transaction` where RID=:RID');
            $stmt->execute(array('RID' =>$RID));
            if($stmt->rowCount()==0){
                break;
            }
        }
        
        $stmt = $conn->prepare('SELECT name from `user` where account=:account');
        $stmt->execute(array('account'=>$orderer));
        $orderer_name = $stmt->fetch()[0];
        
        $stmt = $conn->prepare('INSERT INTO `transaction` (RID,user,type,time,trader,amount_change) values 
                               (:RID,:account,:type,:time,:trader,:val)');
        $stmt->execute(array('RID' => $RID,'account' => $shopowner, 'type' => 'Payment','time'=>$time,'trader'=>$orderer_name,'val'=>'-'.$val));
    }
?>