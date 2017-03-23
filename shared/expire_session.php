<?php

  require_once("utils.php");

  if(isset($_SESSION['expire'])){

    if(time()>=$_SESSION['expire']) {

      if(session_destroy()) {

         header('HTTP/1.1 307 temporary redirect');
         header("location: index.php?c=440");
      }

      exit;

    }else{

      $_SESSION['expire'] = time() + EXPIRE_TIME;
    }

  }
?>
