<?php
   session_start();
   $_SESSION['Authenticated'] = false;
   $dbservername = 'Localhost';
   $dbname = 'hw';
   $dbusername = 'root';
   $dbuserpassword = '';

   try{
        if(preg_match("/^\s*$/",$_POST['account'])){
            throw new Exception("帳號欄位空白!");  
        }
        if(preg_match("/^\s*$/",$_POST['password'])){
            throw new Exception("密碼欄位空白!");  
        }
        $act = $_POST['account'];
        $pwd = $_POST['password'];
        $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("select *,ST_AsText(location) as 'loca' from user where account=:account");
        $stmt->execute(array('account' => $act));
        if ($stmt->rowCount()) {
            $row = $stmt->fetch();
            if ($row['password'] == hash('sha256', $row['salt'] . $pwd)) {
                $_SESSION['Authenticated'] = true;
                $_SESSION['account'] = $row['account'];
                $_SESSION['phonenumber'] = $row['phoneNumber'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['identity'] = $row['identity'];
                $_SESSION['location'] = $row['loca'];//'POINT(x,y)'
                $_SESSION['ulocation'] = $row['location'];
                header("Location:nav.php");
                exit();
            } else {
                throw new Exception("密碼錯誤");
            }
        } 
        else {
            throw new Exception("帳號不存在");
        }
    } 
    catch (Exception $e) {
        $msg = $e->getMessage();
        session_unset();
        session_destroy();
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("$msg");
        window.location.replace("index.php");
        </script>
        </body>
        </html>
        EOT;
    }
?>

