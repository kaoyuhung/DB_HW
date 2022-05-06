<?php
    session_start();
    $_SESSION['Authenticated'] = false;
    $dbservername = 'localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    
    try{
        if($_POST['store_name'] == "" || $_POST['store_type'] == "" || $_POST['store_lat'] == ""
        || $_POST['store_long'] == ""){
            throw new Exception("有欄位空白!");  
        }
        $name = $_POST['store_name'];
        $type = $_POST['store_type'];
        $lat = $_POST['store_lat'];
        $long = $_POST['store_long'];
        
        // 判斷店名跟類別還沒寫
        
        
        $floatlong = (float)$long;
        $floatlat = (float)$lat;
        if(strval($floatlat)!=$lat || $floatlat>90.0 || $floatlat<-90.0){
            throw new Exception("緯度格式錯誤!");
        }
        if(strval($floatlong)!=$long || $floatlong>180.0 || $floatlong<-180.0){
            throw new Exception("經度格式錯誤!");
        }

        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt=$conn->prepare("select store_name from store where store_name=:store_name");
        $stmt->execute(array('store_name' => $name));
        if ($stmt->rowCount()==0){
            $stmt = $conn->prepare("insert into store values (:store_name,:type,:latitude,:longitude,:owner)");
            $stmt->execute(array('store_name' => $name, 'type' => $type, 'latitude' => $floatlat, 'longitude' => $floatlong, 
            'owner' => $_SESSION['account']));
            $_SESSION['store_Authenticated'] = true;
            $_SESSION['store_name'] = $name;
            $_SESSION['store_type'] = $type;
            $_SESSION['store_latitude'] = $lat;
            $_SESSION['store_longitude'] = $long;
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