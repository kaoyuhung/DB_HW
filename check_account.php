<?php
$dbservername='localhost';
$dbname='hw';
$dbusername='root';
$dbpassword='';
if (!isset($_REQUEST['account']) || $_REQUEST['account']==""){
    echo 'Type your acccout.';
    exit();
}
$account=$_REQUEST['account'];
$conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt=$conn->prepare("select account from user where account=:account");
$stmt->execute(array('account' => $account));    
if ($stmt->rowCount()==0){
    echo 'Avaliable';
}
else{
    echo 'This account has been used.';
}
?>
