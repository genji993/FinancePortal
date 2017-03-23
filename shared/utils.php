<?php

  define ("EXPIRE_TIME",120);
  define ("SESSION_EXPIRED",440);

  function printErrMsg($str){
    echo "<div class='alert alert-danger'><strong>".$str."</strong></div>";
  }

  /* Clean Input To Avaid SQLi or Others Attacsk*/
  function sanitize(&$input) {
    $input = stripslashes($input);
    $input = htmlentities($input);


  }


  /* Random String Generator */
  function randStrGen($len){
     $result = "";
     $chars = "abcdefghijklmnopqrstuvwxyz$_?!-0123456789";
     $charArray = str_split($chars);
     for($i = 0; $i < $len; $i++){
 	    $randItem = array_rand($charArray);
 	    $result .= "".$charArray[$randItem];
     }
     return $result;
 }




?>
