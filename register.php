<?php
    session_start();
    $_SESSION['Authenticated'] = false;
    $dbservername = 'localhost';
    $dbname = 'hw';
    $dbusername = 'root';
    $dbpassword = '';

    try{
        if(preg_match("/^\s*$/",$_POST['account'])){
            throw new Exception("帳號欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['name'])){
            throw new Exception("姓名欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['phonenumber'])){
            throw new Exception("手機號碼欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['password'])){
            throw new Exception("密碼欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['re-password'])){
            throw new Exception("驗證密碼欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['latitude'])){
            throw new Exception("緯度欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST["longitude"])){
            throw new Exception("經度欄位空白!");  
        }
        $account = $_POST['account'];
        $pwd = $_POST['password'];
        $name = $_POST['name'];
        $phone = $_POST['phonenumber'];
        $re_pwd = $_POST['re-password'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        if(!(preg_match('/^(?:[A-Za-z]+(?:\s+|$)){1,3}$/',$name))){
            throw new Exception("姓名格式不對!");
        }
        if(!(preg_match('/^[0-9]{10}$/',$phone))){
            throw new Exception("手機號碼格式不對!");
        }
        if(!(preg_match('/^[A-Za-z0-9]+$/', $account))){
            throw new Exception("帳號格式不對!");
        }
        if(!(preg_match('/^[A-Za-z0-9]+$/', $pwd))){
            throw new Exception("密碼格式不對!");
        }
        if($pwd!=$re_pwd){
            throw new Exception("驗證密碼不對!");
        }
        $floatlong = (float)$longitude;
        $floatlat = (float)$latitude;
        if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$latitude) || $floatlat>90.0 || $floatlat<-90.0){
            throw new Exception("緯度格式錯誤!");
        }
        if(!preg_match("/^-?(\d|[1-9]+\d*|\.\d+|0\.\d+|[1-9]+\d*\.\d+)$/",$longitude) || $floatlong>180.0 || $floatlong<-180.0){
            throw new Exception("經度格式錯誤!");
        }
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("select account from user where account=:account");
        $stmt->execute(array('account' => $account));
        if ($stmt->rowCount()==0){
            $salt = strval(rand(1000, 9999));
            $hashvalue = hash('sha256', $salt . $pwd);
            $stmt = $conn->prepare("insert into user (account,password,name,identity,phonenumber,balance,salt,location) values 
                                    (:account,:pwd,:name,:identity,:phonenumber,:balance,:salt,
                                    ST_GeomFromText('POINT(".$longitude." ".$latitude.")'))");
            $stmt->execute(array('account' => $account, 'pwd' => $hashvalue,'name' => $name, 'identity' => 'user', 
                                 'phonenumber' => $phone, 'balance' => 0, 'salt' => $salt));
            $_SESSION['Authenticated'] = true;
            echo <<<EOT
            <!DOCTYPE html>
            <html>
            <body>
            <script>
            alert("Create a account successfully.");
            window.location.replace("index.php");
            </script>
            </body>
            </html>
            EOT;
            exit();
        }
        else{
            throw new Exception("帳號已被註冊!");
        }
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        session_unset();
        session_destroy();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg");
        window.location.replace("sign-up.php");
        </script>
        </body>
        </html>
        EOT;
    }   
?>