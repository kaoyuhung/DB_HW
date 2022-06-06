<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    
    $OID = (int)$_POST["OID"];

    $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT orderer,shop,price,detail from `order` where OID=:OID");
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

    $stmt = $conn->prepare('SELECT balance from user where account=:account');
    $stmt->execute(array('account'=>$_SESSION['account']));

    $_SESSION['balance'] = $stmt->fetch()[0];

    $stmt = $conn->prepare('UPDATE `order` set status="Cancel" where OID=:OID');
    $stmt->execute(array('OID' => $OID));
    
?>