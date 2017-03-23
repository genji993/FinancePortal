<?php
  session_start();
  include "shared/utils.php";
  if(!isset($_SESSION["user"])) {header("location: index.php");exit;}
  if(isset($_SESSION["user"])) include "shared/expire_session.php";

  //if(isset($_SESSION['user'])) include "shared/https.php";

?>

<!DOCTYPE html>
<html>
<head>
    <title>I tuoi movimenti</title>
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
          <table class="table">
          <?php

            include "shared/connect.php";


            $connection = dbConnect();
            $query      = "SELECT * FROM movimenti WHERE user_id='".$_SESSION['id']."' ORDER BY timestamp DESC";
            $result     = mysqli_query($connection,$query);
            if($result){
              echo "<h4>I tuoi ultimi movimenti</h4>";
              $num_rows = mysqli_num_rows($result);
              for($i=0;$i<$num_rows;$i++){
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                echo "<tr><td><strong>".$row['action']."</strong></td><td>No. Azioni: <b>".$row['quantity']."</b></td><td>Prezzo: <b>".$row['price']."</b></td><td>".$row['timestamp']."</td></tr>";
              }
            }
            mysqli_close($connection);

          ?>
          </table>

        </div>

      </div> <!-- ./row -->

  </div>
  <!-- ./container -->






</body>
</html>
