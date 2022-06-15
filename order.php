<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbpassword = '';
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$_POST['detail']
    //echo $_POST['detail'];
    $detail=json_decode($_POST["detail"],true);
    $cost = (int)$detail[0]["total"];
    $error = "";
    for($i=1;$i<count($detail);$i++){
        $stmt = $conn->prepare('SELECT * from meal where meal_name=:food and store=:shop');
        $stmt->execute(array('food' => $detail[$i]["meal"],'shop' => $detail[0]["shop"]));
        if($stmt->rowCount()==0){
            echo "商品已被刪除！";
            exit();
        }
    }
    for($i=1;$i<count($detail);$i++){
        $stmt = $conn->prepare('SELECT price from meal where meal_name=:food and store=:shop');
        $stmt->execute(array('food' => $detail[$i]["meal"],'shop' => $detail[0]["shop"]));
        $new_price =(int)($stmt->fetch()[0]);
        if($new_price!=(int)$detail[$i]["price"]){
            echo "商品價格已更動！";
            exit();
        }
    }
    for($i=1;$i<count($detail);$i++){
        $stmt = $conn->prepare('SELECT quantity from meal where meal_name=:food and store=:shop');
        $stmt->execute(array('food' => $detail[$i]["meal"],'shop' => $detail[0]["shop"]));
        $remain =(int)($stmt->fetch()[0]);
        if($remain<(int)$detail[$i]["quantity"]){
            $error = $error.$detail[$i]["meal"]."庫存不足\n";
        }
    }
    try{
        if($error){
            throw new Exception($error);
        }
        if($cost>$_SESSION['balance']){
            throw new Exception("餘額不足!");
        }
        // $file = fopen("test.txt", 'w');
        // fwrite($file,$_POST["detail"]);
        // fclose($file);
       
        // exit();
        for($i=1;$i<count($detail);$i++){
            $stmt = $conn->prepare('UPDATE meal set quantity=quantity-:num where meal_name=:food and store=:shop');
            $stmt->execute(array('food' => $detail[$i]["meal"],'shop' => $detail[0]["shop"],'num' =>$detail[$i]["quantity"]));
        }
       
        $stmt = $conn->prepare('UPDATE user set balance=balance-:num where account=:account');
        $stmt->execute(array('num' =>$cost,'account'=>$_SESSION['account']));
        
        $stmt = $conn->prepare('UPDATE user set balance=balance+:num where account=
                              (SELECT account from user join store on user.account=
                              store.owner where store_name=:store)');
        $stmt->execute(array('num' =>$cost,'store'=>$detail[0]["shop"]));

        
        while(1){
            $OID = rand(0,1000000);
            $stmt = $conn->prepare('SELECT * from `order` where OID=:OID');
            $stmt->execute(array('OID' =>$OID));
            if($stmt->rowCount()==0){
                break;
            }
        }
        $stmt = $conn->prepare('INSERT INTO `order` (OID,status,start,orderer,shop,price,detail) values (:OID,:status,:date,:account,:shop,:price,:detail)');
        $stmt->execute(array('OID' => $OID,'status' =>"Not Finished",'date'=>date("Y-m-d H:i:s"),'account'=>$_SESSION['account'],"shop" => $detail[0]["shop"],"price"=>$cost,"detail"=>$_POST['detail']));

        
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
        $stmt->execute(array('RID' => $RID,'account' => $orderer, 'type' => 'Payment','time'=>$time,'trader'=>$shop,'val'=>'-'.$val));
    
        
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
        $stmt->execute(array('RID' => $RID,'account' => $shopowner, 'type' => 'Receive','time'=>$time,'trader'=>$orderer_name,'val'=>'+'.$val));

        echo "訂購成功";
        
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>