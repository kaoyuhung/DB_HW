<?php
  session_start();
  $dbservername='localhost';
  $dbname='hw2';
  $dbusername='root';
  $dbpassword='';
  $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt=$conn->prepare("select * from store where owner=:account");
  $stmt->execute(array('account' => $_SESSION['account']));
  if ($stmt->rowCount()==0){
    $disable = "";
    $store_name="macdonald";
    $store_type="fast food";
    $latitude="121.00028167648875";
    $longitude="24.78472733371133";
  }
  else{
    $row = $stmt->fetch();
    $disable = "disabled";
    $store_name=$row[0];
    $store_type=$row[1];
    $latitude=$row[2];
    $longitude=$row[3];
    $_SESSION['store_name'] = $store_name;
  }
  $stmt=$conn->prepare("select distinct type from store");
  $stmt->execute();
  $catagory = $stmt
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
            Accouont: <?php echo $_SESSION['account']?>, <?php echo $_SESSION['identity']?>, 
            PhoneNumber: <?php echo $_SESSION['phonenumber']?>,  
            location: <?php echo $_SESSION['latitude']?>, <?php echo $_SESSION['longitude']?>
            
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
        <!-- 
                
        -->
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
                <!-- <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist> -->
                <input type="text" class="form-control" id="search5" placeholder="Enter Meal">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> category</label>
            
              
                <div class="col-sm-5">
                  <!-- <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category">
                  <datalist id="categorys">

                    <option value="fast food">
               
                  </datalist> -->
                  <select id="search6" class="form-control">
                    <!-- <option value="Pendiente">Pendiente</option>
                    <option value="Frenada">Frenada</option>
                    <option value="Finalizada">Finalizada</option> -->
                    <?php
                      for($i=0;$i<$catagory->rowCount();$i++){
                          $row = $catagory->fetch();
                          echo "<option value=".$row[0].">".$row[0]."</option>";
                      }
                    ?>
                  </select><br>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary" onclick="search()">Search</button>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                
                  <th scope="col">shop name</th>
                  <th scope="col">shop category</th>
                  <th scope="col">Distance</th>
               
                </tr>
              </thead>
              <!-- <tbody>
                <tr>
                  <th scope="row">1</th>
               
                  <td>macdonald</td>
                  <td>fast food</td>
                
                  <td>near </td>
                  <td>  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#macdonald">Open menu</button></td>
            
                </tr>
           

              </tbody> -->
            </table>
                <!-- Modal -->
  <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">menu</h4>
        </div>
        <div class="modal-body">
         <!--  -->
  
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
                <tr>
                  <th scope="row">1</th>
                  <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                
                  <td>Hamburger</td>
                
                  <td>80 </td>
                  <td>20 </td>
              
                  <td> <input type="checkbox" id="cbox1" value="Hamburger"></td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
                 
                  <td>coffee</td>
             
                  <td>50 </td>
                  <td>20</td>
              
                  <td><input type="checkbox" id="cbox2" value="coffee"></td>
                </tr>

              </tbody>
            </table>
          </div>

        </div>
        

         <!--  -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
        </div>
      </div>
      
    </div>
  </div>
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
                <input class="form-control" id="store_name" placeholder=<?php echo $store_name ?> type="text" autocomplete="off" name = "store_name" oninput="check_name(this.value)" <?php echo $disable ?> ><br><label id="msg">Type your store name.</label>
              </div>
              <div class="col-xs-2">
                <label for="store_type">shop category</label>
                <input class="form-control" id="store_type" placeholder=<?php echo $store_type ?> type="text" autocomplete="off" name = "store_type" <?php echo $disable ?> >
              </div>
              <div class="col-xs-2">
                <label for="store_lat">latitude</label>
                <input class="form-control" id="store_lat" placeholder=<?php echo $latitude ?> type="text" autocomplete="off" name = "store_lat" <?php echo $disable ?> >
              </div>
              <div class="col-xs-2">
                <label for="store_long">longitude</label>
                <input class="form-control" id="store_long" placeholder=<?php echo $longitude ?> type="text" autocomplete="off" name = "store_long" <?php echo $disable ?> >
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
                  $stmt->execute(array('store_name' => $store_name));
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
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal" id= $row[0] onclick = "edit_meal(this.id)">Edit</button>
                                </div>
                              </div>
                            </div>
                          </div>
                    <td><button type="button" id=$row[0] onclick = "del(this.id)" class="btn btn-danger">Delete</button></td>
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
        xhttp.send("store_name="+"<?php echo $store_name?>"+"&"+"meal_name="+name+"&"
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
        xhttp.send("store_name="+<?php echo $store_name?>+"&"+"meal_name="+name);
      }

      function search(){
        var xhttp = new XMLHttpRequest();
        
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
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
                    +"&"+"catogory="+document.getElementById("search6").value);
      }
  </script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>