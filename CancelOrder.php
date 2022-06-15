<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    
    $OID = (int)$_POST["OID"];

    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT status,orderer,shop,price,detail from `order` where OID=:OID");
    $stmt->execute(array('OID' => $OID));
    $row = $stmt->fetch();
    try{
        if($row['status']=='Finished'){
            throw new Exception("取消失敗!訂單狀態:Finished");
        }
        if($row['status']=='Cancel'){
            throw new Exception("取消失敗!訂單已被取消");
        }
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
    
    
        $stmt = $conn->prepare('UPDATE `order` set status="Cancel" where OID=:OID');
        $stmt->execute(array('OID' => $OID));

        $time = date("Y-m-d H:i:s");
      
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
    catch (Exception $e){
        echo $e->getMessage();
    }
    
    
?>