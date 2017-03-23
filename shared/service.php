<?php



define("VENDITA","VENDITA");
define("ACQUISTO","ACQUISTO");
define("ERR_MSG1","Impossibile prelevare informazioni dal database");
define("ERR_MSG2","Impossibile aggiornare il database");



class TempInfo{
  public $id;
  public $oquantity;
  public $nquantity;
}


function sommaVendita(&$azioni,&$credito,$num_a,$num_c){

  $azioni  -= $num_a;
  $credito += $num_c;

}


function writeTempLog(&$array,$id,$old,$new,&$index){
  /* Update Temporary Info */
  $array[$index]            = new TempInfo();
  $array[$index]->id        = $id;
  $array[$index]->oquantity = $old;
  $array[$index]->nquantity = $new;
  $index++;

}




if($_SERVER["REQUEST_METHOD"]=="POST"){

  if(isset($_POST["submit"])){

    if(isset($_SESSION["user"])) include "expire_session.php";

    if($_POST["quantity"]!="" && $_POST["action"]!=""){



      $connection         = dbConnect();
      $quantity_requested = (int) sanitize($_POST["quantity"]);
      $aux                = $quantity_requested;
      $action_requested   = $_POST["action"];

      /* Take User Info */
      $user_row = mysqli_query($connection,"SELECT * FROM users WHERE email='".$_SESSION['user']."'");
      $user_row = mysqli_fetch_array($user_row,MYSQLI_ASSOC);

      $ucredito     = (int) $user_row['credito'];
      $old_credito  = $ucredito;
      $uazioni      = (int) $user_row['azioni'];

      switch($action_requested){
        case  ACQUISTO:

              try{

                $log[] = array();
                $count = 0;

                if(!mysqli_autocommit($connection,false)) throw new Exception(ERR_MSG1);


                $tvendita = mysqli_query($connection,"SELECT * FROM vendita ORDER BY price ASC FOR UPDATE");
                if(!$tvendita) throw new Exception(ERR_MSG1);


                // table sum(quantity)
                $tQtot = mysqli_query($connection,"SELECT SUM(quantity) FROM vendita");
                if(!$tQtot) throw new Exception(ERR_MSG1);


                $tQtot = mysqli_fetch_array($tQtot,MYSQLI_NUM); $tQtot = $tQtot[0];

                if(mysqli_num_rows($tvendita)==0) { throw new Exception("Tabella Vuota");          break; }
                if($quantity_requested > $tQtot)  { throw new Exception("Quantità Insufficiente"); break; }

                while($quantity_requested>0){

                    $row          = mysqli_fetch_array($tvendita,MYSQLI_ASSOC);
                    $row_id       = (int) $row['id'];
                    $row_price    = (int) $row['price'];
                    $row_quantity = (int) $row['quantity'];


                    if($row_quantity >= $quantity_requested)
                      $q = $quantity_requested;
                    else
                      $q = $row_quantity;


                    $cost = $q * $row_price;

                    if($cost > $ucredito) { throw new Exception("Credito Insufficiente"); break; }
                    $uazioni  += $q;
                    $ucredito -= $cost;
                    writeTempLog($log,$row_id,$row_quantity,($row_quantity-$q),$count);
                    $quantity_requested -= $q;

                } // END WHILE


                /* Debug Info */
                //for($i=0;$i<$count;$i++){
                //  echo $log[$i]->id." ".$log[$i]->oquantity." ".$log[$i]->nquantity."<br>";
                //}


                // Update Book
                for($i=0;$i<$count;$i++){
                  if($log[$i]->nquantity > 0){
                    if(!mysqli_query($connection,"UPDATE vendita SET quantity='".$log[$i]->nquantity."' WHERE id='".$log[$i]->id."'"))
                      throw new Exception(ERR_MSG2);
                  }else if($log[$i]->nquantity == 0){
                    if(!mysqli_query($connection,"DELETE FROM vendita WHERE id='".$log[$i]->id."'"))
                        throw new Exception(ERR_MSG2);
                  }
                }



                // Update User Info
                $query  = "UPDATE users SET credito='".$ucredito."', azioni='".$uazioni."' WHERE id='".$_SESSION['id']."'";
                if(!mysqli_query($connection,$query)) throw new Exception(ERR_MSG2);

                // update Movements
                $cost  = $old_credito-$ucredito;
                $time  = date("Y-m-d H:i:s");
                $query = "INSERT INTO movimenti(user_id,action,quantity,price,timestamp) VALUES('".$_SESSION['id']."','".ACQUISTO."','".$aux."','".$cost."','".$time."')";
                if(!mysqli_query($connection,$query)) throw new Exception(mysqli_error($connection));

                if(!mysqli_commit($connection)) throw new Exception(ERR_MSG2);


                echo "<meta http-equiv='refresh' content='0'>"; //Refresh Page And Avoid Resubmit

              }catch(Exception $e){

                  mysqli_rollback($connection);
                  printErrMsg($e->getMessage());

              }



              break;

        case  VENDITA:

              try{

                $log[] = array();
                $count = 0;

                if(!mysqli_autocommit($connection,false)) throw new Exception(ERR_MSG1);


                $tacquisto = mysqli_query($connection,"SELECT * FROM acquisto ORDER BY price DESC FOR UPDATE");
                if(!$tacquisto) throw new Exception("Q1: ". ERR_MSG1);


                // table sum(quantity)
                $tQtot = mysqli_query($connection,"SELECT SUM(quantity) FROM acquisto");
                if(!$tQtot) throw new Exception(ERR_MSG1);


                $tQtot = mysqli_fetch_array($tQtot,MYSQLI_NUM); $tQtot = $tQtot[0];



                if(mysqli_num_rows($tacquisto)==0)  { throw new Exception("Tabella Vuota");          break; }
                if($uazioni < $quantity_requested)  { throw new Exception("Non disponibi di abbastanza azioni");}
                if($quantity_requested > $tQtot)    { throw new Exception("Quantità non disponibile"); break; }


                while($quantity_requested>0){

                    $row          = mysqli_fetch_array($tacquisto,MYSQLI_ASSOC);
                    $row_id       = (int) $row['id'];
                    $row_price    = (int) $row['price'];
                    $row_quantity = (int) $row['quantity'];


                    if($row_quantity >= $quantity_requested)
                      $q = $quantity_requested;
                    else
                      $q = $row_quantity;


                    $cost = $q * $row_price;

                    $uazioni  -= $q;
                    $ucredito += $cost;
                    writeTempLog($log,$row_id,$row_quantity,($row_quantity-$q),$count);
                    $quantity_requested -= $q;

                } // END WHILE


                /* Debug Info */
                //for($i=0;$i<$count;$i++){
                //  echo $log[$i]->id." ".$log[$i]->oquantity." ".$log[$i]->nquantity."<br>";
                //}


                // Update Book
                for($i=0;$i<$count;$i++){
                  if($log[$i]->nquantity > 0){
                    if(!mysqli_query($connection,"UPDATE acquisto SET quantity='".$log[$i]->nquantity."' WHERE id='".$log[$i]->id."'"))
                      throw new Exception(ERR_MSG2);
                  }else if($log[$i]->nquantity == 0){
                    if(!mysqli_query($connection,"DELETE FROM acquisto WHERE id='".$log[$i]->id."'"))
                        throw new Exception(ERR_MSG2);
                  }
                }



                // Update User Info
                $query  = "UPDATE users SET credito='".$ucredito."', azioni='".$uazioni."' WHERE id='".$_SESSION['id']."'";
                if(!mysqli_query($connection,$query)) throw new Exception(ERR_MSG2);

                // update Movements
                $cost  = $ucredito-$old_credito;
                $time  = date("Y-m-d H:i:s");
                $query = "INSERT INTO movimenti(user_id,action,quantity,price,timestamp) VALUES('".$_SESSION['id']."','".VENDITA."','".$aux."','".$cost."','".$time."')";
                if(!mysqli_query($connection,$query)) throw new Exception(mysqli_error($connection));

                if(!mysqli_commit($connection)) throw new Exception(ERR_MSG2);


                echo "<meta http-equiv='refresh' content='0'>"; //Refresh Page And Avoid Resubmit

              }catch(Exception $e){

                  mysqli_rollback($connection);
                  printErrMsg($e->getMessage());

              }

            break;

      } // End switch



      mysqli_close($connection);
    }


  }

}

?>

<div id="service-form">

  <form class="form-inline" method="POST" onsubmit="return validateActionValue()" action="index.php">
      <div class="form-group" style="width:200px;">
        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Quante azioni?">
      </div>
      <div class="form-group" style="width:100px;">
        <select id="action_type" class="form-control" name="action">
          <option name="acquisto_option" value="ACQUISTO">Acquisto</option>
          <option name="vendita_option" value="VENDITA">Vendita</option>
        </select>
      </div>
      <button type="submit" class="btn btn-default" name="submit">Submit</button>
  </form>

</div>
