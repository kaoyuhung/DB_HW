<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw2';
    $dbusername = 'root';
    $dbpassword = '';
    $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        // echo $_POST['shop_name'].'<br>';
        // echo $_POST['distance'].'<br>';
        // echo $_POST['lowerbound'].'<br>';
        // echo $_POST['upperbound'].'<br>';
        // echo $_POST['meal_name'].'<br>';
        // echo $_POST['catogory'].'<br>';
        $shop = $_POST['shop_name'];
        $dis = $_POST['distance'];
        $lowerbound = $_POST['lowerbound'];
        $upperbound = $_POST['upperbound'];
        if($lowerbound==""){$lowerbound=0;}
        if($upperbound==""){$upperbound=10000000000;}

        $meal = $_POST['meal_name'];
        $cat = $_POST['catogory'];
        $array=array();
        $array['type'] = $cat;
        $array['lowerbound'] = $lowerbound;
        $array['upperbound'] = $upperbound;
        $array['meal_name'] = "%".$meal."%";
        $array['shop'] = "%".$shop."%";
        $query = 'select store, dis from(select m,mm,store,type,(latitude*latitude+longitude*longitude) as dis from (select min(price) as m, max(price) as MM, store from meal 
        where meal_name like :meal_name group by store) as H, store where H.store = store.store_name and type = :type order by dis desc) as qq where qq.m<=:upperbound 
        and qq.mm>=:lowerbound and store like :shop';

        $stmt = $conn->prepare($query);
        $stmt->execute($array);
        echo $stmt->rowCount();
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>

<!-- select m,mm,store,(latitude*latitude+longitude*longitude) as dis from 
(select min(price) as m, max(price) as MM, store from meal group by store) as H, store where H.store = store.store_name order by dis desc; -->

<!-- select store, dis from(select m,mm,store,type,(latitude*latitude+longitude*longitude) as dis from (select min(price) as m, max(price) as MM, store from meal 
where meal_name like :meal_name group by store) as H, store where H.store = store.store_name and type = :type order by dis desc) as qq where qq.m<=:upperbound 
and qq.mm>=:lowerbound and store like :shop; -->