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
        if(preg_match("/^\s*$/",$lowerbound)){$lowerbound=0;}
        else{
            if(!preg_match("/^[0-9][0-9]*$/",$lowerbound)){
                throw new Exception("請輸入合法價格範圍!");
            }
        }
        if(preg_match("/^\s*$/",$upperbound)){$upperbound=10000000000;}
        else{
            if(!preg_match("/^[0-9][0-9]*$/",$upperbound)){
                throw new Exception("請輸入合法價格範圍!");
            }
        }
        $meal = $_POST['meal_name'];
        $cat = $_POST['catogory'];
        $array=array();
        $array['type'] = $cat;
        $array['lowerbound'] = intval($lowerbound);
        $array['upperbound'] = intval($upperbound);
        $array['meal_name'] = "%".$meal."%";
        $array['shop'] = "%".$shop."%";
        $array['lat'] = (float)$_SESSION['latitude'];
        $array['long'] = (float)$_SESSION['longitude'];
        $query = 'SELECT count(*) from (SELECT DISTINCT(store),type,((longitude-:long)*(longitude-:long)+(latitude-:lat)*(latitude-:lat)) as dis from meal,store where meal.store = store.store_name and price 
                  BETWEEN :lowerbound and :upperbound and meal_name like :meal_name) as H where H.store like :shop and type like :type order by dis desc;';
        $stmt = $conn->prepare($query);
        $stmt->execute($array);
        $num = $stmt->fetch()[0];
        $num = intval($num);
        if($dis=='far'){
            $query = 'SELECT * from (SELECT DISTINCT(store),type,((longitude-:long)*(longitude-:long)+(latitude-:lat)*(latitude-:lat)) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and meal_name like :meal_name) as H where H.store like :shop and type like :type order by dis limit :num;';
            $array['num'] = floor($num/3);
        }
        if($dis=='medium'){
            $query = 'SELECT * from (SELECT DISTINCT(store),type,((longitude-:long)*(longitude-:long)+(latitude-:lat)*(latitude-:lat)) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and meal_name like :meal_name) as H where H.store like :shop and type like :type order by dis limit :num1 offset :num;';
            $array['num'] = floor($num/3);
            $array['num1'] = floor($num*2/3)-floor($num/3);
        }
        if($dis=='near'){
            $query = 'SELECT * from (SELECT DISTINCT(store),type,((longitude-:long)*(longitude-:long)+(latitude-:lat)*(latitude-:lat)) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and meal_name like :meal_name) as H where H.store like :shop and type like :type order by dis limit :num2 offset :num1';
            $array['num1'] = floor($num*2/3);
            $array['num2'] = $num-floor($num*2/3);
        }
        $stmt = $conn->prepare($query);
        $stmt->execute($array); 
        $_SESSION['search'] = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 
        $_SESSION['dist'] = $dis;
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg;
    }
?>
