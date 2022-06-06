<?php
    session_start();
    $dbservername = 'Localhost';
    $dbname = 'hw';
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
        $sort_key = $_POST['sort_key'];
        $sort = $_POST['sort'];
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
        if($sort_key=="Shop"){
            $sort_key="store";
        }
        else{
            $sort_key="dis";
        }
        $meal = $_POST['meal_name'];
        $cat = $_POST['catogory'];
        $array=array();
        $array['type'] = $cat;
        $array['lowerbound'] = intval($lowerbound);
        $array['upperbound'] = intval($upperbound);
        $array['meal_name'] = "%".$meal."%";
        $array['shop'] = "%".$shop."%";
        $query = 'SELECT count(*) from (SELECT DISTINCT(store),type from meal,store where meal.store = store.store_name and price 
                  BETWEEN :lowerbound and :upperbound and lower(meal_name) like lower(:meal_name)) as H where lower(H.store) like lower(:shop) and type like :type';
        $stmt = $conn->prepare($query);
        $stmt->execute($array);
        $num = (string)$stmt->fetch()[0];
        $num = intval($num);
        if($dis=='far'){
            $query = "SELECT * from (SELECT * from (SELECT DISTINCT(store) as store,type,ST_Distance_Sphere(:ulocation,store.location) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and lower(meal_name) like lower(:meal_name)) as H where lower(H.store) like lower(:shop) and type like :type order by dis desc limit :num) as A order by $sort_key $sort";
            $stmt = $conn->prepare($query);
            $stmt -> bindValue(":ulocation",$_SESSION['ulocation'],PDO::PARAM_STR);
            $stmt -> bindValue(":lowerbound",intval($lowerbound),PDO::PARAM_INT);
            $stmt -> bindValue(":upperbound",intval($upperbound),PDO::PARAM_INT);
            $stmt -> bindValue(":meal_name","%".$meal."%",PDO::PARAM_STR);
            $stmt -> bindValue(":shop","%".$shop."%",PDO::PARAM_STR);
            $stmt -> bindValue(":type",$cat,PDO::PARAM_STR);
            $stmt -> bindValue(":num",(int)floor($num/3),PDO::PARAM_INT);
        }
        if($dis=='medium'){
            $query = "SELECT * from (SELECT * from (SELECT DISTINCT(store) as store,type,ST_Distance_Sphere(:ulocation,store.location) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and lower(meal_name) like lower(:meal_name)) as H where lower(H.store) like lower(:shop) and type like :type order by dis desc limit :num1 offset :num) as A order by $sort_key $sort";
            $stmt = $conn->prepare($query);
            $stmt -> bindValue(":ulocation",$_SESSION['ulocation'],PDO::PARAM_STR);
            $stmt -> bindValue(":lowerbound",intval($lowerbound),PDO::PARAM_INT);
            $stmt -> bindValue(":upperbound",intval($upperbound),PDO::PARAM_INT);
            $stmt -> bindValue(":meal_name","%".$meal."%",PDO::PARAM_STR);
            $stmt -> bindValue(":shop","%".$shop."%",PDO::PARAM_STR);
            $stmt -> bindValue(":type",$cat,PDO::PARAM_STR);
            $stmt -> bindValue(":num",(int)(floor($num/3)),PDO::PARAM_INT);
            $stmt -> bindValue(":num1",(int)(floor($num*2/3)-floor($num/3)),PDO::PARAM_INT);
        }
        if($dis=='near'){
            $query = "SELECT * from (SELECT * from (SELECT DISTINCT(store) as store,type,ST_Distance_Sphere(:ulocation,store.location) as dis from meal,store where meal.store = store.store_name and price 
            BETWEEN :lowerbound and :upperbound and lower(meal_name) like lower(:meal_name)) as H where lower(H.store) like lower(:shop) and type like :type order by dis desc limit :num2 offset :num1) as A order by $sort_key $sort";
            $stmt = $conn->prepare($query);
            $stmt -> bindValue(":ulocation",$_SESSION['ulocation'],PDO::PARAM_STR);
            $stmt -> bindValue(":lowerbound",intval($lowerbound),PDO::PARAM_INT);
            $stmt -> bindValue(":upperbound",intval($upperbound),PDO::PARAM_INT);
            $stmt -> bindValue(":meal_name","%".$meal."%",PDO::PARAM_STR);
            $stmt -> bindValue(":shop","%".$shop."%",PDO::PARAM_STR);
            $stmt -> bindValue(":type",$cat,PDO::PARAM_STR);
            $stmt -> bindValue(":num1",(int)floor($num*2/3),PDO::PARAM_INT);
            $stmt -> bindValue(":num2",(int)($num-floor($num*2/3)),PDO::PARAM_INT);
        }
        $stmt->execute(); 
        $store = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['search'] = json_encode($store); 
        $_SESSION['dist'] = $dis;
        $_SESSION['page'] = 1;
    }
    catch (Exception $e){
        $msg = $e->getMessage();
        echo $msg.$_SESSION['ulocation'];
    }
?>
