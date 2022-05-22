<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbuserpassword = '';
    if(isset($_POST['latitude']) && isset($_POST['longitude'])){
        try{
            $floatlong = (float)$_POST['longitude'];
            $floatlat = (float)$_POST['latitude'];
            $longitude = $_POST['longitude'];
            $latitude = $_POST['latitude'];
            $account = $_SESSION['account'];
            if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$_POST['latitude']) || $floatlat > 90.0 || $floatlat < -90.0){
                throw new Exception("緯度格式錯誤!");
            }
            if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$_POST['longitude']) || $floatlong > 180.0 || $floatlong < -180.0){
                throw new Exception("經度格式錯誤!");
            }
            $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE user SET location = ST_GeomFromText('POINT($longitude $latitude)') WHERE account=:account";
            $stmt=$conn->prepare($sql);
            $stmt->execute(array('account' => $account));
            $_SESSION['location'] = 'POINT('.$longitude.','.$latitude.')';
            $sql = "SELECT location from user WHERE account=:account";
            $stmt=$conn->prepare($sql);
            $stmt->execute(array('account' => $account));
            $_SESSION['ulocation'] =$stmt->fetch()[0];
            echo "更改成功!";
        }
        catch (Exception $e){
            echo $e->getMessage();
        }
    }
?>