<?php
define("VENDITA","VENDITA");
define("ACQUISTO","ACQUISTO");
define("ERR_MSG1","Impossibile prelevare informazioni dal database");
define("ERR_MSG2","Impossibile aggiornare il database");

function printErrMsg($str){
  echo "<div class='alert alert-danger'><strong>".$str."</strong></div>";
}

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

try{


  $connection         = dbConnect();
  if(!mysqli_autocommit($connection,false)) throw new Exception(ERR_MSG1);

  $log     = [];
  $count   = 0;
  $quantity_requested = (int) $_POST["quantity"];
  $aux                = $quantity_requested;
  $action_requested   = $_POST["action"];



  /* Take User Info */
  $user_row = mysqli_query($connection,"SELECT * FROM users WHERE email='".$_SESSION['user']."'");
  if(!$user_row) throw new Exception(ERR_MSG1);
  $user_row = mysqli_fetch_array($user_row,MYSQLI_ASSOC);

  $ucredito           = (int) $user_row['credito'];
  $old_credito        = $ucredito;
  $uazioni            = (int) $user_row['azioni'];



  $tvendita = mysqli_query($connection,"SELECT * FROM vendita ORDER BY price ASC FOR UPDATE");
  if(!$tvendita) throw new Exception("Q1: ". ERR_MSG1);


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
      $azioni  += $num_a;
      $credito -= $num_c;
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
  echo $query;
  if(!mysqli_query($connection,$query)) throw new Exception(mysqli_error($connection));

  if(!mysqli_commit($connection)) throw new Exception(ERR_MSG2);


  echo "<meta http-equiv='refresh' content='0'>"; //Refresh Page And Avoid Resubmit

}catch(Exception $e){

    mysqli_rollback($connection);
    printErrMsg($e->getMessage());

}












?>
