<?php


  define("TABLE_DEPTH",5);

  $connection = dbConnect();

  /* Prelevo Info Dalla Tabella acquisto */
  $query  = "SELECT * FROM acquisto ORDER BY price DESC LIMIT ".TABLE_DEPTH;
  $ris    = mysqli_query($connection,$query);
  if($ris){
    $acquisti = array();
    for($i=0;$i<TABLE_DEPTH;$i++) $acquisti[$i] = NULL;
    if( mysqli_num_rows($ris) > 0){
      $i = 0;
      while(($acquisto_rows = mysqli_fetch_array($ris,MYSQLI_ASSOC)))
      {
            $acquisti[$i]=$acquisto_rows;
            $i++;
      }
    }
  }

  /* Prelevo Info Dalla Tabella vendita */
  $query  = "SELECT * FROM vendita ORDER BY price ASC LIMIT ".TABLE_DEPTH;
  $ris    = mysqli_query($connection,$query);
  if($ris){
    $vendite = array();
    for($i=0;$i<TABLE_DEPTH;$i++) $vendite[$i] = NULL;
    if( mysqli_num_rows($ris) > 0){
      $i = 0;
      while(($vendite_rows = mysqli_fetch_array($ris,MYSQLI_ASSOC)))
        {
            $vendite[$i]=$vendite_rows;
            $i++;
        }

    }

  }


  mysqli_close($connection);

 ?>

<table class="table table-striped" id="book">
    <thead>
      <tr>
        <th>Quantità in acquisto</th>
        <th>Prezzo in acquisto</th>
        <th>Prezzo in vendita</th>
        <th>Quantità in vendita</th>
      </tr>
    </thead>
    <tbody>

      <?php


      for($i=0;$i<TABLE_DEPTH;$i++){
          echo "<tr>";

          echo "<td>";
          if($acquisti[$i]!=NULL) echo $acquisti[$i]['quantity'];
          echo"</td><td>";
          if($acquisti[$i]!=NULL) echo $acquisti[$i]['price'];
          echo "</td>";

          echo "<td>";
          if($vendite[$i]!=NULL) echo $vendite[$i]['quantity'];
          echo"</td><td>";
          if($vendite[$i]!=NULL) echo $vendite[$i]['price'];
          echo "</td>";
          echo "</tr>";
      }

      ?>



    </tbody>
  </table>
