<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    if(isset($_POST['number'])){
        try{
            if(!preg_match("/^[1-9][0-9]*$/",$_POST['number'])){
                throw new Exception("請輸入合法數字!");
            }
            $num=intval($_POST['number']);
            $account = $_SESSION['account'];
            $_SESSION['balance']+=$num;
            $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("UPDATE user SET balance = :balance WHERE account=:account");
            $stmt->execute(array('account' => $account, 'balance' => $_SESSION['balance']));
            echo "加值成功!";
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
?>