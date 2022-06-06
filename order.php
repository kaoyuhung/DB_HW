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

        $stmt = $conn->prepare('SELECT balance from user where account=:account');
        $stmt->execute(array('account'=>$_SESSION['account']));

        $_SESSION['balance'] = $stmt->fetch()[0];
        
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

        echo "訂購成功";
        
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>