<?php
  session_start();

  //require_once "shared/https.php";
  if(isset($_SESSION['user'])) {header("location: index.php");exit;}
  include ("shared/connect.php");
  include("shared/utils.php");

  if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["submit"])){
        if($_POST["email"]!="" && $_POST["password"]!="" && $_POST["confirm-password"]!=""){

            $connection = dbConnect();


            $nome = $_POST["nome"];
            $cognome = $_POST["cognome"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $cpassword = $_POST["confirm-password"];
            sanitize($nome);
            sanitize($cognome);
            sanitize($email);
            sanitize($password);
            sanitize($cpassword);


            if(filter_var($email,FILTER_VALIDATE_EMAIL)){

                if(strlen($password)>1 && (strlen(nome)>=3 && strlen(nome)<32) && (strlen(cognome)>=3 && strlen(cognome)<32)){
                    if($password == $cpassword){

                        $credito      = 50000;
                        $azioni       = 0;
                        $date         = date("Y-m-d H:i:s");

                        $salt         = randStrGen(10);
                        $md5_password = md5($salt.$password);


                        $query        = "INSERT INTO users (nome,cognome,email,encrypted_password,salt,credito,azioni,created_at) VALUES ('$nome','$cognome','$email','$md5_password','$salt','$credito','$azioni','$date')";

                        $result       = mysqli_query($connection,$query);

                        
                        if($result) header("Location: login.php"); //Registration Successufull


                        else echo "<div class='alert alert-danger'><strong>".mysqli_error($connection)."</strong></div>";

                        mysqli_free_result($result);
                        mysqli_close($connection);

                    } else echo "Passwords must be equal!";

                } else echo "Passwords length must be > 8";

            } else echo "Insert a valid email address!";

        } else echo "Fields cannot be empty!";

    }



  }





?>

<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>

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

                <div name="registrationForm">

                    <h3>Register</h3>

                    <p id="errJs"></p>

                    <form method="POST" action="register.php" onsubmit="return validateRegForm()">

                      <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Insert Name here...">
                      </div>
                      <div class="form-group">
                        <label for="cognome">Cognome:</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" placeholder="Insert Surname here...">
                      </div>

                      <div class="form-group">
                        <label for="email">Email address:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Insert email here...">
                      </div>

                      <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Insert password here...">
                      </div>

                      <div class="form-group">
                        <label for="confirm-pwd">Repeat Password:</label>
                        <input type="password" class="form-control" id="cpassword" name="confirm-password" placeholder="Please repeat password...">
                      </div>

                      <button type="submit" class="btn btn-default" name="submit">Register</button>
                    </form>


                </div>

              </div>

            </div> <!-- ./row -->

        </div>
        <!-- ./container -->




    </body>


</html>
