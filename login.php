<?php
   session_start();
   $_SESSION['Authenticated'] = false;
   $dbservername = 'Localhost';
   $dbname = 'hw2';
   $dbusername = 'root';
   $dbuserpassword = '';

   try{
        if ($_POST['account']=="" || $_POST['password']=="") {
            throw new Exception("請輸入帳號和密碼");
        } 
        $act = $_POST['account'];
        $pwd = $_POST['password'];
        $conn = new PDO("mysql:host = $dbservername;dbname=$dbname", $dbusername, $dbuserpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("select * from user where account=:account");
        $stmt->execute(array('account' => $act));
        if ($stmt->rowCount()) {
            $row = $stmt->fetch();
            if ($row['password'] == hash('sha256', $row['salt'] . $pwd)) {
                $_SESSION['Authenticated'] = true;
                $_SESSION['account'] = $row[0];
                $_SESSION['phonenumber'] = $row[6];
                $_SESSION['name'] = $row[2];
                $_SESSION['identity'] = $row[3];
                $_SESSION['latitude'] = $row[4];
                $_SESSION['longitude'] = $row[5];
                $_SESSION['balance'] = $row[7];
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

