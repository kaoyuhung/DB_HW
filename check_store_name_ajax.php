<?php
$dbservername='localhost';
$dbname='hw';
$dbusername='root';
$dbpassword='';
if (!isset($_REQUEST['store_name']) || $_REQUEST['store_name']=="" || preg_match("/^\s+$/",$_POST['store_name'])){
    echo 'Type your store name.';
    exit();
}
$name=$_REQUEST['store_name'];
$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt=$conn->prepare("select * from store where store_name=:name");
$stmt->execute(array('name' => $name));    
if ($stmt->rowCount()==0){
    echo 'Avaliable';
}
else{
    echo 'This store name has been used.';
}
?>
