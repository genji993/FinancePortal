<?php


define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
define("PASSWORD", "root"); // Password di accesso al DB.
define("DATABASE", "dp1sep2016"); // Nome del database.

function dbConnect(){

  $connection = mysqli_connect(HOST, USER, PASSWORD,DATABASE);
  if (!$connection){
      die("Database Connection Failed" . mysqli_error($connection));
  }
  return $connection;


}




?>
