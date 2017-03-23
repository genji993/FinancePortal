<?php
  session_start();
  include "shared/utils.php";
 if(isset($_SESSION["user"]))   include "shared/expire_session.php";
  include "shared/connect.php";

//  if(isset($_SESSION['user'])) include "shared/https.php";


  if($_GET){

    $code = preg_replace('/[^-a-zA-Z0-9_]/', '', $_GET['c']);
    $code = htmlspecialchars($code);

    switch($code){
      case SESSION_EXPIRED:
           printErrMsg("Session Timed Out - <a href='login.php'>Login</a>");
           break;
      default:
           break;
    }

  }


?>
<!DOCTYPE html>
<html>
<head>
    <title>Progetto</title>
    <!-- Controllo Se i Cookie son abilitati -->
    <script>if(!navigator.cookieEnabled) window.location.href = 'error.html';</script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/validation.js"></script>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

  <!-- Controllo se Javascript è abilitato -->
  <noscript>

      <div class="alert alert-danger">
        <strong>You don't have javascript enabled.</strong>
      </div>

  </noscript>

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

          <?php

            if(isset($_SESSION['user'])){

              // User Info If Logged In
              include ("shared/welcome.php");
              // Acquista/vendi -->
              include ("shared/service.php");

              echo "<hr>";
            }

          ?>

          <!-- PRINT BOOK -->
          <?php include ("shared/table.php")    ?>

        </div>

      </div> <!-- ./row -->

  </div>
  <!-- ./container -->






</body>
</html>
