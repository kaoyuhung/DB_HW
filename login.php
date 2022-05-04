<?php
   session_start();
   $_SESSION['Authenticated'] = false;
   $dbservername = 'Localhost';
   $dbname = 'hw2';
   $dbusername = 'root';
   $dbuserpassword = '';

   try{
        if (empty($_POST['account']) || empty($_POST['password'])) {
            throw new Exception('1');
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
                $_SESSION['username'] = $row[0];
                header("Location:nav.html");
                exit();
            } else {
                throw new Exception('2');
            }
        } 
        else {
            throw new Exception('3');
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
        alert($msg+"login failed !!");
        window.location.replace("index.php");
        </script>
        </body>
        </html>
        EOT;
    }
?>

