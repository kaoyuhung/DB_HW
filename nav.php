<?php
  session_start();
  $dbservername='localhost';
  $dbname='hw';
  $dbusername='root';
  $dbpassword='';
  if(!isset($_SESSION['account'])){
    header("Location:index.php");
  }
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt=$conn->prepare("select balance from user where account=:account");
  $stmt->execute(array('account' => $_SESSION['account']));
  $_SESSION['balance'] = $stmt->fetch()[0];

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
      <li><a href="#shop">shop</a></li>
      <li><a href="#MyOrder">MyOrder</a></li>
      <li><a href="#ShopOrder">Shop Order</a></li>
      <li><a href="#TransactionRecord">Transaction Record</a></li>
      <li><a href="#logout" onclick="logout()">Logout</a></li>
      <!-- <button type="button" onclick="logout()" class="btn btn-info" data-dismiss="modal" style="margin-left: 900px;">Logout</button> -->
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
            <button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Recharge</button>
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
            xhr.open("POST", "recharge.php", true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.send("number="+document.getElementById("value").value);
          }
        </script>
      
        <h3>Search</h3>
        <div class="row  col-xs-8">
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
            <table class="table" style=" margin-top: 15px;" id = "mytable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance(km)</th>
                  
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
                      $dis = number_format(((float)$store[$i-1]['dis'])/1000, 2, '.', '');
                      echo <<< EOT
                        <tbody>
                        <tr>
                          <th scope="row">$i</th>
                      
                          <td>{$store[$i-1]['store']}</td>
                          <td>{$store[$i-1]['type']}</td>
                        
                          <td>{$dis}</td>
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
                              <table class="table" style=" margin-top: 15px;" id="table$i">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Picture</th>
                                  
                                    <th scope="col">meal name</th>
                                
                                    <th scope="col">price</th>
                                    <th scope="col">Quantity</th>
                                  
                                    <th scope="col">Order</th>
                                  </tr>
                                </thead>
                                <tbody>
                    EOT;
                    for($j=1;$j<=$stmt->rowCount();$j++){
                      $row = $stmt->fetch();
                      echo <<< EOT
                        <tr>
                          <th scope="row">$j</th>
                          <td><img src="data:$row[5];base64,$row[3]" width="125" height="100"></td>
                          <td>$row[0]</td>
                          <td>$row[1]</td>
                          <td>$row[2]</td>
                          <td><input type="number" min="0" style="width: 6em" id="$i-$j" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"></td>
                        </tr>
                      EOT;
                    }
                    echo <<< EOT
                              </tbody>
                              </table>  
                            </div>
                            <label for="type$i">Type</label>
                            <select name="type" id="type$i">
                              <option>Delivery</option>
                              <option>Pick-up</option>
                            </select>
                          </div>
                        </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-info" data-dismiss="modal" onclick="calculate($i)">Calculate the price</button>
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

      <div id="shop" class="tab-pane fade">
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
                    <td><img src="data:$row[5];base64,$row[3]" width="150" height=100"></td>
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
      <div id="MyOrder" class="tab-pane fade">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-1" for="MyOrderstatus">Status</label>
            
            <div class="col-sm-2">
              <select class="form-control" id = "MyOrderstatus" onchange=MyOrderStatusChange()>
                      <option>All</option>
                      <option>Finished</option>
                      <option>Not Finished</option>
                      <option>Cancel</option>
              </select>
            </div>

          </div>
          
          <button type="button" class="btn btn-danger" id="MyOrderSelectedCancel" onclick=funcMOcancel()>Cancel Selected Orders</button>
         
        </form>
        <div class="row">
          <div class="col-xs-8">
            <table class="table" style=" margin-top: 15px;" id = "MyOrderTable">
            <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Order ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Start</th>
                  <th scope="col">End</th>
                  <th scope="col">Shop name</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Order Details</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody id="MyOrderTableContent">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="ShopOrder" class="tab-pane fade">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-1" for="ShopOrderstatus">Status</label>
            <div class="col-sm-2">
              <select class="form-control" id = "ShopOrderstatus" onchange=ShopOrderStatusChange()>
                      <option>All</option>
                      <option>Finished</option>
                      <option>Not Finished</option>
                      <option>Cancel</option>
              </select>
            </div>
          </div>
        </form>
        <button type="button" class="btn btn-success"  onclick=funcSOcomplete()>Finish Selected Orders</button>
        <button type="button" class="btn btn-danger"  onclick=funcSOcancel()>Cancel Selected Orders</button>
        <div class="row">
          <div class="col-xs-8">
            <table class="table" style=" margin-top: 15px;" id = "ShopOrderTable">
            <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Order ID</th>
                  <th scope="col">Status</th>
                  <th scope="col">Start</th>
                  <th scope="col">End</th>
                  <th scope="col">Shop name</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Order Details</th>
                  <th scope="col">Action</th>
                </tr>
              </thead> 
              <tbody id="ShopOrderTableContent">
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="TransactionRecord" class="tab-pane fade">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="control-label col-sm-1" for="TransactionRecordstatus">Status</label>
            <div class="col-sm-2">
              <select class="form-control" id = "TransactionRecordstatus" onchange=TransactionRecordStatusChange()>
                      <option>All</option>
                      <option>Payment</option>
                      <option>Receive</option>
                      <option>Recharge</option>
              </select>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-xs-8">
            <table class="table" style=" margin-top: 15px;" id = "TransactionRecordTable">
            <thead>
                <tr>
                  <th scope="col">Record ID</th>
                  <th scope="col">Action</th>
                  <th scope="col">Time</th>
                  <th scope="col">Trader</th>
                  <th scope="col">Amount change</th>
                </tr>
              </thead> 
              <tbody id="TransactionRecordTableContent">
              </tbody>
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

      function calculate(num){
        var table = document.getElementById('table'+num);
        var mytable = document.getElementById('mytable');
        var idx = (num % 5 == 0) ? 5 : num % 5;
        var dis = parseFloat(mytable.rows[idx].cells[3].innerHTML);
        var deliver_fee = Math.max(10,Math.round(dis*10));
        var deli = (document.getElementById("type"+num).value=="Delivery") ? deliver_fee : 0;
        var total = 0;
        var tbody = "";
        var flag = false;
        for (var i=1;i < table.rows.length;i++) {
          if(document.getElementById(num+"-"+i).value && document.getElementById(num+"-"+i).value!='0'){
             let price = parseInt(table.rows[i].cells[3].innerHTML);
             let quantity = parseInt(document.getElementById(num+"-"+i).value);
             tbody += "<tr>";
             tbody += "<th scope='row'>"+i+"</th>";
             tbody += "<td>"+table.rows[i].cells[1].innerHTML+"</td>";

             tbody += "<td>"+table.rows[i].cells[2].innerHTML+"</td>";
             tbody += "<td>"+price+"</td>";
             tbody += "<td>"+quantity+"</td>";
             total += price * quantity;
             tbody += "</tr>";
             flag = true;
          }
        }
        if(!flag){
          alert("No food ordered!")
          return;
        }
        if(document.getElementById("order")!=null){
          $('#order').remove();
        }
        const modal = document.createElement('div');
        modal.id = "order";    
        modal.className = 'modal fade';
        modal.setAttribute('data-modal', 'true');
        modal.setAttribute('data-backdrop', 'static');
        modal.setAttribute('data-keyboard','true')
        modal.innerHTML = `
          <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Order</h4>
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
                                      <th scope="col">Order Quantity</th>
                                    </tr>
                                  </thead>
                                  <tbody>`
                                  +tbody+
                                  `</tbody>
                                </table> 
                              </div>
                            </div>`
                    +`<p>Subtotal  $`+total+`</p>`
                    +`<p>Delivery fee  $`+deli+`.</p>`
                    +`<p>Total Price   $`+(total+deli)+`</p>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-info" data-dismiss="modal" onclick=order(`+num+`)>Order</button>
                    </div>
              </div>            
            </div>
          </div>
        `;
        document.querySelector('body').appendChild(modal);
        $('#order').modal('show');
      }

      function order(num){
        var table = document.getElementById('table'+num);
        var mytable = document.getElementById('mytable');
        var idx = (num % 5 == 0) ? 5 : num % 5;
        var shop = mytable.rows[idx].cells[1].innerHTML;
        var dis = parseFloat(mytable.rows[idx].cells[3].innerHTML);
        var deliver_fee = Math.max(10,Math.round(dis*10));
        var deli = (document.getElementById("type"+num).value=="Delivery") ? deliver_fee : 0;
        var total = 0;
        var detail = [];
        detail.push({});
        var idx = 1;
        for (var i=1;i < table.rows.length;i++) {
          if(document.getElementById(num+"-"+i).value && document.getElementById(num+"-"+i).value!='0'){
             let price = parseInt(table.rows[i].cells[3].innerHTML);
             let quantity = parseInt(document.getElementById(num+"-"+i).value);
             total+=price*quantity;
             detail.push({});
             detail[idx]['img'] = table.rows[i].cells[1].innerHTML;
             detail[idx]['meal'] = table.rows[i].cells[2].innerHTML;        
             detail[idx]['price'] = price;
             detail[idx]['quantity'] = quantity;
             idx+=1;
          }
        }
        detail[0]['deliver_fee'] = deli;
        detail[0]['subtotal'] = total;
        detail[0]['total'] = total + deli;
        detail[0]['shop'] = shop;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
            location.reload();
          }
        };
       
        xhttp.open("POST", "order.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("detail="+encodeURIComponent(JSON.stringify(detail)));
        
      }

      function LoadMyOrder(){
        var xhttp = new XMLHttpRequest();
       
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
              var json = this.responseText;
              var data = JSON.parse(json)
              var idx = 0;
              for(var i=0;i<data.length;i++){
                if(document.getElementById("MyOrderstatus").value=="All" || document.getElementById("MyOrderstatus").value==data[i]['status']){
                  let row1 = document.createElement('tr');
                  let row10 = document.createElement('td');
                  let row2 = document.createElement('th');
                  let row3 = document.createElement('td');
                  let row4 = document.createElement('td');
                  let row5 = document.createElement('td');
                  let row6 = document.createElement('td');
                  let row7 = document.createElement('td');
                  let row8 = document.createElement('td');
                  idx+=1;
                  if(data[i]['status']=="Not Finished"){
                    row10.innerHTML = '<input type="checkbox" id="MyOrderBox'+idx+'"></button>';
                  }
                  else{
                    row10.innerHTML = '';
                  }

                  row1.appendChild(row10);
                  row2.setAttribute('scope', 'row');
                  row2.innerHTML=data[i]["OID"];   
                  row3.innerHTML=data[i]['status'];
                  row4.innerHTML=data[i]['start'];            
                  row5.innerHTML=data[i]["end"];        
                  row6.innerHTML=data[i]["shop"];
                  row7.innerHTML=data[i]["price"];
                  row8.innerHTML='<button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Order'+data[i]["OID"]+'")>order details</button>';
                  
                  row1.appendChild(row2);
                  row1.appendChild(row3);
                  row1.appendChild(row4);
                  row1.appendChild(row5);
                  row1.appendChild(row6);
                  row1.appendChild(row7);
                  row1.appendChild(row8);

                  if(data[i]['status']=="Not Finished"){
                    let row9 = document.createElement('td');
                    row9.innerHTML = '<button type="button" class="btn btn-danger" onclick=CancelOrder(['+data[i]["OID"]+'])>Cancel</button>';
                    row1.appendChild(row9);
                    
                  }
                  
                  document.querySelector('#MyOrderTableContent').appendChild(row1);

                  if(document.getElementById("Order"+data[i]["OID"])!=null){
                    $('#Order'+data[i]["OID"]).remove();
                  }
                
                  var detail = JSON.parse(data[i]["detail"]);

                  const modal = document.createElement('div');
                  modal.id = "Order"+data[i]["OID"];    
                  modal.className = 'modal fade';
                  modal.setAttribute('data-modal', 'true');
                  modal.setAttribute('data-backdrop', 'static');
                  modal.setAttribute('data-keyboard','true');
                  var tbody = "";
                  var table = document.getElementById('table6');
                  for(var j=1;j<detail.length;j++){
                      tbody += "<tr>";
                      tbody += "<th scope='row'>"+j+"</th>";
                      tbody += "<td>"+detail[j]["img"]+"</td>";
                      tbody += "<td>"+detail[j]["meal"]+"</td>";
                      tbody += "<td>"+detail[j]["price"]+"</td>";
                      tbody += "<td>"+detail[j]["quantity"]+"</td>";
                      tbody += "</tr>";
                  }
                  modal.innerHTML = `
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Order</h4>
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
                                        <th scope="col">Order Quantity</th>
                                      </tr>
                                    </thead>
                                    <tbody>`
                                    +tbody+
                                    `</tbody>
                                  </table> 
                                </div>
                              </div>`
                              +`<p>Subtotal  $`+detail[0]["subtotal"]+`</p>`
                              +`<p>Delivery fee  $`+detail[0]["deliver_fee"]+`.</p>`
                              +`<p>Total Price   $`+detail[0]["total"]+`</p>
                        </div>            
                      </div>
                    </div>
                  `;
                  document.querySelector('body').appendChild(modal);
                }
                
              }
            }
          }
        };
        xhttp.open("POST", "LoadMyOrder.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send();
      }

      function LoadShopOrder(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
              var json = this.responseText;
              var data = JSON.parse(json);
              var idx = 0;
              for(var i=0;i<data.length;i++){
                if(document.getElementById("ShopOrderstatus").value=="All" || document.getElementById("ShopOrderstatus").value==data[i]['status']){
                  let row1 = document.createElement('tr');
                  let row2 = document.createElement('th');
                  let row3 = document.createElement('td');
                  let row4 = document.createElement('td');
                  let row5 = document.createElement('td');
                  let row6 = document.createElement('td');
                  let row7 = document.createElement('td');
                  let row8 = document.createElement('td');
                  let row10 = document.createElement('td');
                  idx+=1;
                  if(data[i]['status']=="Not Finished"){
                    row10.innerHTML = '<input type="checkbox" id="ShopOrderBox'+idx+'"></button>';
                  }
                  else{
                    row10.innerHTML = '';
                  }
                  row1.appendChild(row10);
                  row2.setAttribute('scope', 'row');
                  row2.innerHTML=data[i]["OID"];   
                  row3.innerHTML=data[i]['status'];
                  row4.innerHTML=data[i]['start'];            
                  row5.innerHTML=data[i]["end"];        
                  row6.innerHTML=data[i]["shop"];
                  row7.innerHTML=data[i]["price"];
                  row8.innerHTML='<button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Order'+data[i]["OID"]+'")>order details</button>';
                  
                  row1.appendChild(row2);
                  row1.appendChild(row3);
                  row1.appendChild(row4);
                  row1.appendChild(row5);
                  row1.appendChild(row6);
                  row1.appendChild(row7);
                  row1.appendChild(row8);

                  if(data[i]['status']=="Not Finished"){
                    let row9 = document.createElement('td');
                    let row10 = document.createElement('td');

                    row9.innerHTML = '<button type="button" class="btn btn-success"  onclick=CompleteOrder(['+data[i]["OID"]+'])>Done</button>';
                    row10.innerHTML = '<button type="button" class="btn btn-danger" onclick=CancelOrder(['+data[i]["OID"]+'])>Cancel</button>';
                                      
                    row1.appendChild(row9);
                    row1.appendChild(row10);
                  }
                  
                  document.querySelector('#ShopOrderTableContent').appendChild(row1);

                  if(document.getElementById("Order"+data[i]["OID"])!=null){
                    $('#Order'+data[i]["OID"]).remove();
                  }
                
                  var detail = JSON.parse(data[i]["detail"]);

                  const modal = document.createElement('div');
                  modal.id = "Order"+data[i]["OID"];    
                  modal.className = 'modal fade';
                  modal.setAttribute('data-modal', 'true');
                  modal.setAttribute('data-backdrop', 'static');
                  modal.setAttribute('data-keyboard','true');
                  var tbody = "";
                  var table = document.getElementById('table6');
                  for(var j=1;j<detail.length;j++){
                      tbody += "<tr>";
                      tbody += "<th scope='row'>"+j+"</th>";
                      tbody += "<td>"+detail[j]["img"]+"</td>";
                      tbody += "<td>"+detail[j]["meal"]+"</td>";
                      tbody += "<td>"+detail[j]["price"]+"</td>";
                      tbody += "<td>"+detail[j]["quantity"]+"</td>";
                      tbody += "</tr>";
                  }
                  modal.innerHTML = `
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Order</h4>
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
                                        <th scope="col">Order Quantity</th>
                                      </tr>
                                    </thead>
                                    <tbody>`
                                    +tbody+
                                    `</tbody>
                                  </table> 
                                </div>
                              </div>`
                              +`<p>Subtotal  $`+detail[0]["subtotal"]+`</p>`
                              +`<p>Delivery fee  $`+detail[0]["deliver_fee"]+`.</p>`
                              +`<p>Total Price   $`+detail[0]["total"]+`</p>
                        </div>            
                      </div>
                    </div>
                  `;
                  document.querySelector('body').appendChild(modal);
                }
              }
            }
          }
        };
        xhttp.open("POST", "LoadShopOrder.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send();
      }
      
      function LoadTransactionRecord(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
             
              var json = this.responseText;
              var data = JSON.parse(json)
              for(var i=0;i<data.length;i++){
                if(document.getElementById("TransactionRecordstatus").value=="All" || document.getElementById("TransactionRecordstatus").value==data[i]['type']){
                  let row1 = document.createElement('tr');
                  let row2 = document.createElement('th');
                  let row3 = document.createElement('td');
                  let row4 = document.createElement('td');
                  let row5 = document.createElement('td');
                  let row6 = document.createElement('td');
                 
                  
                  
                  row2.setAttribute('scope', 'row');
                  row2.innerHTML=data[i]["RID"];   
                  row3.innerHTML=data[i]['type'];
                  row4.innerHTML=data[i]['time'];            
                  row5.innerHTML=data[i]["trader"];        
                  row6.innerHTML=data[i]["amount_change"];
                  
                  
                  row1.appendChild(row2);
                  row1.appendChild(row3);
                  row1.appendChild(row4);
                  row1.appendChild(row5);
                  row1.appendChild(row6);
               
                  document.querySelector('#TransactionRecordTableContent').appendChild(row1);
                }
              }
            }
          }
        };
        xhttp.open("POST", "LoadTransactionRecord.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send();
      }
      window.onload=function (){
        LoadMyOrder();
        LoadShopOrder();  
        LoadTransactionRecord();
      }

      function CancelOrder(OID){
        if(OID[0]!='['){
          OID = JSON.stringify(OID);
        }
       
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
              alert(this.responseText);
            }
            location.reload();
          };
        }
        xhttp.open("POST", "CancelOrder.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("OID="+OID);
      }

      function CompleteOrder(OID){

        if(OID[0]!='['){
          OID = JSON.stringify(OID);
        }
       
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
              alert(this.responseText);
            }
            location.reload();
          };
        }
        xhttp.open("POST", "CompleteOrder.php", true);
        xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhttp.send("OID="+OID);
      }

      function MyOrderStatusChange(){
        $("#MyOrderTable tbody tr").remove();
        LoadMyOrder();
      }

      function ShopOrderStatusChange(){
        $("#ShopOrderTable tbody tr").remove();
        LoadShopOrder();
      }

      function TransactionRecordStatusChange(){
        $("#TransactionRecordTable tbody tr").remove();
        LoadTransactionRecord();
      }

      function funcMOcancel(){
        var MOtable = document.getElementById('MyOrderTable');
        var length = MOtable.rows.length;
        var arr = [];
        for(var i=1;i<length;i++){
          if(document.getElementById('MyOrderBox'+i)){
            if($('#MyOrderBox'+i).is(':checked')){
              arr.push(MOtable.rows[i].cells[1].innerHTML);
            }
          }
        }
        if(arr.length){
          CancelOrder(JSON.stringify(arr));
        }
        else{
          alert("未選取!");
        }
      }

      function funcSOcancel(){
        var SOtable = document.getElementById('ShopOrderTable');
        var length = SOtable.rows.length;
        var arr = [];
        for(var i=1;i<length;i++){
          if(document.getElementById('ShopOrderBox'+i)){
            if($('#ShopOrderBox'+i).is(':checked')){
              arr.push(SOtable.rows[i].cells[1].innerHTML);
            }
          }
        }
        if(arr.length){
          CancelOrder(JSON.stringify(arr));
        }
        else{
          alert("未選取!");
        }
      }

      function funcSOcomplete(){
        var SOtable = document.getElementById('ShopOrderTable');
        var length = SOtable.rows.length;
        var arr = [];
        for(var i=1;i<length;i++){
          if(document.getElementById('ShopOrderBox'+i)){
            if($('#ShopOrderBox'+i).is(':checked')){
              arr.push(SOtable.rows[i].cells[1].innerHTML);
            }
          }
        }
        if(arr.length){
          CompleteOrder(JSON.stringify(arr));
        }
        else{
          alert("未選取!");
        }
      }
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>
