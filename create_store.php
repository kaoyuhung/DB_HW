<?php
    session_start();
    $_SESSION['Authenticated'] = false;
    $dbservername = 'localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    
    try{
        if(preg_match("/^\s*$/",$_POST['store_name'])){
            throw new Exception("店名欄位空白!");  
        }
        if(preg_match("/^\s*$/", $_POST['store_type'])){
            throw new Exception("店別欄位空白!");  
        }
        if(preg_match("/^\s*$/", $_POST['store_lat'] )){
            throw new Exception("緯度欄位空白!");  
        }
        if(preg_match("/^\s*$/", $_POST['store_long'] )){
            throw new Exception("經度欄位空白!");  
        }
        $name = $_POST['store_name'];
        $type = $_POST['store_type'];
        $lat = $_POST['store_lat'];
        $long = $_POST['store_long'];

        $floatlong = (double)$long;
        $floatlat = (double)$lat;
        if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$lat) || $floatlat>90.0 || $floatlat<-90.0){
            throw new Exception("緯度格式錯誤!");
        }
        if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$long) || $floatlong>180.0 || $floatlong<-180.0){
            throw new Exception("經度格式錯誤!");
        }
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("select store_name from store where store_name=:store_name");
        $stmt->execute(array('store_name' => $name));
        if ($stmt->rowCount()==0){
            $stmt = $conn->prepare("insert into store values (:store_name,:type,:latitude,:longitude,:owner)");
            $stmt->execute(array('store_name' => $name, 'type' => $type, 'latitude' => $lat, 'longitude' => $long, 
            'owner' => $_SESSION['account']));
            $stmt = $conn->prepare("UPDATE user SET identity = 'manager' where account = :account");
            $stmt->execute(array('account' => $_SESSION['account']));
            $_SESSION['store_Authenticated'] = true;
            $_SESSION['store_name'] = $name;
            $_SESSION['store_type'] = $type;
            $_SESSION['store_latitude'] = $floatlat;
            $_SESSION['store_longitude'] = $floatlong;
            $_SESSION['identity'] = 'manager';
            echo <<<EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
            alert("register a store successfully.");
            window.location.replace("nav.php");
            </script>
            </body>
            </html>
            EOT;
            exit();
        }
        else{
            throw new Exception("店名已被註冊過!");
        }
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg");
        window.location.replace("nav.php");
        </script>
        </body>
        </html>
        EOT;
    }
?>