<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbpassword = '';
    // echo $_POST["store_name"];
    // echo $_POST["meal_name"];
    // echo $_POST["price"];
    // echo $_POST["quantity"];
    try{
        if(!preg_match("/^[1-9][0-9]*$/",$_POST['price'])){
            throw new Exception("請輸入合法價錢!");
        }
        if(!preg_match("/^[1-9][0-9]*$/",$_POST['quantity']) && $_POST['quantity']!="0"){
            throw new Exception("請輸入合法數量!");
        }
        $price = intval($_POST["price"]);
        $quantity = intval($_POST["quantity"]);
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("UPDATE meal set price=:price,quantity=:quantity where store=:store_name and meal_name=:meal_name");
        $stmt->execute(array('meal_name' => $_POST["meal_name"], 'price' => $price,'quantity' => $quantity, 'store_name' =>  $_POST["store_name"]));
        echo "success!";
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>