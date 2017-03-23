<?php





    $connection = dbConnect();
    $query      =  "SELECT * FROM users WHERE email= '".$_SESSION['user']."'";
    $ris        =  mysqli_query($connection,$query);
    if($ris){

      /* Welcome + User Portfolio */
      if((mysqli_num_rows($ris)) > 0){
        $userInfo = mysqli_fetch_array($ris,MYSQLI_ASSOC);
        echo "<h4>Benvenuto ".$userInfo['nome']."</h4>";
        echo "<strong>Credito:</strong> ".$userInfo['credito']."€ - <strong>Azioni:</strong> ".$userInfo['azioni']." <br><br><br>";
      }

    } else {

      die("Error finding User Information");
      exit;
    }

    mysqli_close($connection);





?>
