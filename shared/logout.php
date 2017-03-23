<?php
   session_start();

   if(session_destroy()) {
      header('HTTP/1.1 307 temporary redirect');
      header("Location: ../index.php");
   }
?>
