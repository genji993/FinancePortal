<?php
      session_start();
      //require_once "shared/https.php";
      if(isset($_SESSION['user'])) {header("location: index.php");exit;}
      include "shared/connect.php";
      include "shared/utils.php";

      if($_SERVER["REQUEST_METHOD"]=="POST"){
        /* Login script */
        if(isset($_POST["submit"])){

          if($_POST["email"]!="" && $_POST["password"]!="" )
          {


      //      $email    = mysqli_real_escape_string($connection,$_POST["email"]);
            $connection = dbConnect();

            $email      = $_POST["email"];
            $password   = $_POST["password"];

            sanitize($email);
            sanitize($password);


            $query    = "SELECT * FROM users WHERE email='".$email."'";

            $result   = mysqli_query($connection,$query);

            if($result){

              $num_rows = mysqli_num_rows($result);

              if($num_rows) {

                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $salt               = $row["salt"];
                $dbpassword         = $row["encrypted_password"];
                $password           = md5($salt.$password);

                if($password==$dbpassword){

                  /*session  start() */
                  $_SESSION['id']     = $row["id"];
                  $_SESSION['user']   = $email;
                  $_SESSION['expire'] = time()+EXPIRE_TIME;
                  header("location: index.php");

                }else {
                  $errMsg = "Wrong Password!";
                  echo "<div class='alert alert-danger'><strong>".$errMsg."</strong></div>";
                }

              }else { $errMsg = "User does not exists!!"; echo "<div class='alert alert-danger'><strong>".$errMsg."</strong></div>"; }

              mysqli_free_result($result);

            } else die(mysqli_error($connection));

            mysqli_close($connection);

          }// else echo("Please fill in all fields");
        }

      }



?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/validation.js"></script>
    <!-- Controllo Se i Cookie son abilitati -->
    <script>if(!navigator.cookieEnabled) window.location.href = 'error.html';</script>

</head>
<body>

  <div class="container">

    <div class="row">
      <div class="col-xs-12">
        <!-- Header goes here -->
        <?php require("shared/header.php") ?>


      </div>

    </div> <!-- ./row -->

      <div class="row">
        <div class="col-sm-3">
          <!-- Navbar goes here -->
          <?php require("shared/navbar.php"); ?>
        </div>

        <div class="col-sm-9">
          <!-- Main Content here -->
          <div name="loginForm">
            <h3>Login</h3>

            <p id="errJs"></p>

            <form method="POST" onsubmit="return validateLoginForm()">

              <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Insert email here...">
              </div>

              <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Insert password here...">
              </div>


              <button type="submit" class="btn btn-default" name="submit">Login</button>
            </form>

            <br>




          </div>
        </div>

      </div> <!-- ./row -->

  </div>
  <!-- ./container -->






</body>
</html>
