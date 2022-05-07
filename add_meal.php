<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';

    try{
        if($_POST["meal_name"]=="" || $_POST["price"] == "" || $_POST["quantity"] == "" || $_FILES["image"]["name"] == ""
            || preg_match("/^\s+$/",$_POST["meal_name"]) ){
            throw new Exception("有欄位空白！");
        }
        if(!preg_match("/^[1-9][0-9]*$/",$_POST['price'])){
            throw new Exception("請輸入合法價錢!");
        }
        if($_POST["quantity"] != "0"){
            if(!preg_match("/^[1-9][0-9]*$/",$_POST['quantity'])){
                throw new Exception("請輸入合法數量!");
            }
        }
        if($_SESSION['identity'] == 'user'){
            throw new Exception("你不是店長:(");
        }
        $file = fopen($_FILES["image"]["tmp_name"], "rb");
        $fileContents = fread($file, filesize($_FILES["image"]["tmp_name"])); 
        fclose($file);
        $fileContents = base64_encode($fileContents);

        $name = $_POST["meal_name"];
        $price = $_POST["price"];
        $quantity = $_POST["quantity"];
        $store = $_SESSION['store_name'];
        
        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("insert into meal values (:meal_name,:price,:quantity,:image,:store)");
        $stmt->execute(array('meal_name' => $name, 'price' => $price,'quantity' => $quantity, 'image' => $fileContents, 
                                'store' => $store));
        echo <<<EOT
        <!DOCTYPE html>
        <html>
        <body>
        <script>
        alert("新增餐點成功！");
        window.location.replace("nav.php");
        </script>
        </body>
        </html>
        EOT;
        exit();
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