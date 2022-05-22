<?php
  session_start();
  $dbservername='localhost';
  $dbname='hw';
  $dbusername='root';
  $dbpassword='';
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt=$conn->prepare("select * from store where owner=:account");
  $stmt->execute(array('account' => $_SESSION['account']));
  if ($stmt->rowCount()==0){
    $disable = "";
    $store_name="macdonald";
    $store_name_fixed=$store_name;
    $store_type="fast food";
    $store_type_fixed=$store_type;
    $latitude="121.00028167648875";
    $latitude_fixed=$latitude;
    $longitude="24.78472733371133";
    $longitude_fixed=$longitude;
  }
  else{
    $row = $stmt->fetch();
    $disable = "disabled";
    $store_name=$row[0];
    $store_name_fixed=$row[0];
    $store_type=$row[1];
    $store_type_fixed=$row[1];
    $latitude=$row[2];
    $latitude_fixed=$row[2];
    $longitude=$row[3];
    $longitude_fixed=$row[3];
    $_SESSION['store_name'] = $store_name_fixed;
  }
  $stmt=$conn->prepare("select distinct type from store");
  $stmt->execute();
  $catagory = $stmt;

?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>Hello, world!</title>
</head>

<body>
 
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand " href="#">WebSiteName</a>
      </div>

    </div>
  </nav>
  <div class="container">

    <ul class="nav nav-tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#menu1">shop</a></li>
      <button type="button" onclick="logout()" class="btn btn-info" data-dismiss="modal" style="margin-left: 900px;">Logout</button>
    </ul>

    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
            Accouont: <?php echo $_SESSION['account']?>, 
            Name: <?php echo $_SESSION['name']?>, 
            PhoneNumber: <?php echo $_SESSION['phonenumber']?>,  
            location: <?php echo $_SESSION['location']?>
            
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">edit location</button>
            <!--  -->
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">edit location</h4>
                  </div>
                  <div class="modal-body">
                    <label class="control-label " for="latitude">latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label " for="longitude">longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="enter longitude">
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="edit()" class="btn btn-default" data-dismiss="modal">Edit</button>
                  </div>
                </div>
              </div>
            </div> 
            

            walletbalance: <?php echo $_SESSION['balance']?>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add value</button>
            <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add value</h4>
                  </div>
                  <div class="modal-body">
                    <input type="text" class="form-control" id="value" placeholder="enter add value">
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="add()" class="btn btn-default" data-dismiss="modal">Add</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <script>
          function logout(){
            alert("已登出");
            location.replace(href = "index.php");
          }
          function edit() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
                location.reload()
              }
            };
            xhr.open("POST", "edit_location.php", true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.send("latitude="+document.getElementById("latitude").value+
                     "&longitude="+document.getElementById("longitude").value);
          }

          function add() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                alert(this.responseText);
                location.reload()
              }
            };
            xhr.open("POST", "add_balance.php", true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.send("number="+document.getElementById("value").value);
          }
        </script>
      
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Enter Shop name" id="search1">
              </div>
              
              <label class="control-label col-sm-1" for="distance">distance</label>
              
              <div class="col-sm-5">
                <select class="form-control" id="search2">
                  <option>near</option>
                  <option>medium </option>
                  <option>far</option>  
                </select>
              </div>
      
            </div>

            <div class="form-group">

              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">

                <input type="text" class="form-control" id="search3">

              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">

                <input type="text" class="form-control" id="search4">

              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
    
                <input type="text" class="form-control" id="search5" placeholder="Enter Meal">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  
                  <select id="search6" class="form-control">
                    <!-- <option value="Pendiente">Pendiente</option>
                    <option value="Frenada">Frenada</option>
                    <option value="Finalizada">Finalizada</option> -->
                    <?php
                      for($i=0;$i<$catagory->rowCount();$i++){
                          $row = $catagory->fetch();
                          echo '<option value="'.$row[0].'">'.$row[0].'</option>';
                      }
                    ?>
                  </select><br>
                </div>

                <label class="control-label col-sm-1" for="Meal">排序</label>
                <div class="col-sm-2">
                  <select class="form-control" id="sort_key">
                    <option>Shop</option>
                    <option>distance </option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <select class="form-control" id="sort">
                    <option>desc</option>
                    <option>asc</option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="submit" style="margin-left: 18px;"class="btn btn-primary" onclick="search()">Search</button>
                </div>
              </div>
          </form>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
                  
                  <?php
                  if(isset($_SESSION['search'])){
                    $store = json_decode($_SESSION['search'],true);
                    $pages = ceil(count($store)/5);
                    echo '<th scope="col">';
                    echo '<select id="page" onchange="if(this.selectedIndex) ChangePage()">';
                    echo '<option value="-1">--</option>';
                    for($i=1;$i<=$pages;$i++){
                       echo "<option>$i</option>";
                    }
                    echo '</select>';
                    echo '</th>';
                    }
                  ?>
                </tr>
              </thead>
              <?php
                  if(isset($_SESSION['search'])){
                    
                    #var_dump($store);
                    for($i=5*($_SESSION['page']-1)+1;$i<=count($store) && $i<=5*($_SESSION['page']-1)+5;$i++){
                      echo <<< EOT
                        <tbody>
                        <tr>
                          <th scope="row">$i</th>
                      
                          <td>{$store[$i-1]['store']}</td>
                          <td>{$store[$i-1]['type']}</td>
                        
                          <td>{$_SESSION['dist']}</td>
                          <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#store$i">Open menu</button></td>
                    
                        </tr>
                        </tbody>
                      EOT;
                    }
                  }
              ?>
            </table>
                <!-- Modal -->
              <?php
                if(isset($_SESSION['search'])){
                  for($i=5*($_SESSION['page']-1)+1;$i<=count($store);$i++){
                    $store_name = $store[$i-1]['store'];
                    $stmt=$conn->prepare("select * from meal where store=:store");
                    $stmt->execute(array('store' =>  $store_name));
                    echo <<< EOT
                    <div class="modal fade" id="store$i" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">menu</h4>
                          </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="  col-xs-12">
                              <table class="table" style=" margin-top: 15px;">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Picture</th>
                                  
                                    <th scope="col">meal name</th>
                                
                                    <th scope="col">price</th>
                                    <th scope="col">Quantity</th>
                                  
                                    <th scope="col">Order check</th>
                                  </tr>
                                </thead>
                                <tbody>
                    EOT;
                    for($j=1;$j<=$stmt->rowCount();$j++){
                      $row = $stmt->fetch();
                      echo <<< EOT
                        <tr>
                          <th scope="row">$j</th>
                          <td><img src="data:$row[5];base64,$row[3]" width="200" height="100"></td>
                          <td>$row[0]</td>
                          <td>$row[1]</td>
                          <td>$row[2]</td>
                          <td> <input type="checkbox" id="cbox$j" value=$row[0]></td>
                        </tr>
                      EOT;
                    }
                    echo <<< EOT
                              </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
                            </div>
                        </div>
                      </div>
                    </div>
                    EOT;
                  }
                }
              ?>
        </div>
      </div>
    </div>

      <div id="menu1" class="tab-pane fade">
        <form action="create_store.php" method="post" class="fh5co-form animate-box" data-animate-effect="fadeIn">
          <h3> Start a business </h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-2">
                <label for="store_name">shop name</label>
                <input class="form-control" id="store_name" placeholder="<?php echo $store_name_fixed ?>" type="text" autocomplete="off" name = "store_name" oninput="check_name(this.value)" <?php echo $disable ?> ><br><label id="msg">Type your store name.</label>
              </div>
              <div class="col-xs-2">
                <label for="store_type">shop category</label>
                <input class="form-control" id="store_type" placeholder="<?php echo $store_type_fixed ?>" type="text" autocomplete="off" name = "store_type" <?php echo $disable ?> >
              </div>
              <div class="col-xs-2">
                <label for="store_lat">latitude</label>
                <input class="form-control" id="store_lat" placeholder="<?php echo $latitude_fixed ?>" type="text" autocomplete="off" name = "store_lat" <?php echo $disable ?> >
              </div>
              <div class="col-xs-2">
                <label for="store_long">longitude</label>
                <input class="form-control" id="store_long" placeholder="<?php echo $longitude_fixed ?>" type="text" autocomplete="off" name = "store_long" <?php echo $disable ?> >
              </div>
            </div>

          </div>


          <div class=" row" style=" margin-top: 25px;">
            <div class=" col-xs-3">
            <input type="submit" value="register" class="btn btn-primary" <?php echo $disable ?> >
            </div>
          </div>
        </form>
        <hr>
        <form action="add_meal.php" method = "post" class="fh5co-form animate-box" data-animate-effect="fadeIn" enctype="multipart/form-data">
          <h3>ADD</h3>
          <div class="form-group ">
            <div class="row">
              <div class="col-xs-6">
                <label for="ex3">meal name</label>
                <input class="form-control" id="ex3" type="text" name="meal_name">
              </div>
            </div>
            <div class="row" style=" margin-top: 15px;">
              <div class="col-xs-3">
                <label for="ex7">price</label>
                <input class="form-control" id="ex7" type="text" name="price">
              </div>
              <div class="col-xs-3">
                <label for="ex4">quantity</label>
                <input class="form-control" id="ex4" type="text" name="quantity">
              </div>
            </div>


            <div class="row" style=" margin-top: 25px;">

              <div class=" col-xs-3">
                <label for="ex12">上傳圖片</label>
                <input id="myFile" type="file" name="image" multiple class="file-loading">

              </div>
              <div class=" col-xs-3">

                <!-- <button style=" margin-top: 15px;" type="button" class="btn btn-primary">Add</button> -->
                <input type="submit" value="add" class="btn btn-primary">
              </div>
              
            </div>
          </div>
        </form>

        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Picture</th>
                  <th scope="col">meal name</th>
                  <th scope="col">price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Edit</th>
                  <th scope="col">Delete</th>
                </tr>
              </thead> 
                <?php 
                if($_SESSION['identity']=='manager'){
                  $stmt=$conn->prepare("SELECT * from meal where store=:store_name");
                  $stmt->execute(array('store_name' => $store_name_fixed));
                  for ($i = 1; $i <= $stmt->rowCount(); $i++) {
                    $row = $stmt->fetch();
                    echo <<< EOT
                    <tbody>
                    <tr>
                    <th scope="row">$i</th>
                    <td><img src="data:$row[5];base64,$row[3]" widt="200" height="100"></td>
                    <td>$row[0]</td>
                    <td>$row[1]</td>
                    <td>$row[2]</td>
                    <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#meal-$i">
                      Edit
                      </button></td>
                          <div class="modal fade" id="meal-$i" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="row" >
                                    <div class="col-xs-6">
                                      <label for="price-$row[0]">price</label>
                                      <input class="form-control" id="price-$row[0]" type="text">
                                    </div>
                                    <div class="col-xs-6">
                                      <label for="quantity-$row[0]">quantity</label>
                                      <input class="form-control" id="quantity-$row[0]" type="text">
                                    </div>
                                  </div>
                        
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" id="$row[0]" onclick = "edit_meal(this.id)">Edit</button>
                                </div>
                              </div>
                            </div>
                          </div>
                    <td><button type="button" id="$row[0]" onclick = "del(this.id)" class="btn btn-danger">Delete</button></td>
                    </tr>
                    </tbody>
                    EOT;
                  }
                }
                ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script>
      $(document).ready(function () {
        $(".nav-tabs a").click(function () {
          $(this).tab('show');
        });
      });

   
		  function check_name(name){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("msg").innerHTML = this.responseText;
          }
        };
        xhttp.open("POST", "check_store_name_ajax.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("store_name="+name);
    	}

      function edit_meal(name){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
            location.reload();
          }
        };
        xhttp.open("POST", "edit_meal.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("store_name="+"<?php echo $store_name_fixed?>"+"&"+"meal_name="+name+"&"
                    +"price="+document.getElementById("price-"+name).value+"&"+"quantity="+document.getElementById("quantity-"+name).value);
      }

      function del(name){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
            location.reload();
          }
        };
        xhttp.open("POST", "delete_meal.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("store_name="+"<?php echo $store_name_fixed?>"+"&"+"meal_name="+name);
      }

      function search(){
        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
              alert(this.responseText);
            }
            location.reload();
          }
        };
        xhttp.open("POST", "search.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("shop_name="+document.getElementById("search1").value
                    +"&"+"distance="+document.getElementById("search2").value
                    +"&"+"lowerbound="+document.getElementById("search3").value
                    +"&"+"upperbound="+document.getElementById("search4").value
                    +"&"+"meal_name="+document.getElementById("search5").value
                    +"&"+"catogory="+document.getElementById("search6").value
                    +"&"+"sort_key="+document.getElementById("sort_key").value
                    +"&"+"sort="+document.getElementById("sort").value);
      }
      function ChangePage(){
        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            location.reload();
          }
        };
        xhttp.open("POST", "changepage.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("page="+document.getElementById("page").value);
      }
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>
