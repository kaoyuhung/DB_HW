<?php
    session_start();
    $dbservername='localhost';
    $dbname='hw';
    $dbusername='root';
    $dbpassword='';
    $page=$_REQUEST['page'];
    $_SESSION['page']=intval($page);
?>
