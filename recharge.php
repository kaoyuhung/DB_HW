<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    if(isset($_POST['number'])){
        try{
            if(!preg_match("/^[1-9][0-9]*$/",$_POST['number'])){
                throw new Exception("非正整數!");
            }
            $num=intval($_POST['number']);
            $account = $_SESSION['account'];
            $name =  $_SESSION['name'];
            $_SESSION['balance']+=$num;
            $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("UPDATE user SET balance = :balance WHERE account=:account");
            $stmt->execute(array('account' => $account, 'balance' => $_SESSION['balance']));

            while(1){
                $RID = rand(0,1000000);
                $stmt = $conn->prepare('SELECT * from `transaction` where RID=:RID');
                $stmt->execute(array('RID' =>$RID));
                if($stmt->rowCount()==0){
                    break;
                }
            }

            $stmt = $conn->prepare("INSERT INTO transaction values (:RID,:user,:type,:time,:trader,:val)");
            $stmt->execute(array('RID' => $RID,'user' => $account, 'type' => 'Recharge','time'=>date("Y-m-d H:i:s"),'trader'=>$name,'val'=>'+'.$num));
            echo "加值成功!";
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
?>