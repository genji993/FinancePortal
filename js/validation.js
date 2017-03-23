function validateActionValue(){

  var num_azioni = document.getElementById("quantity").value;

  if(isNaN(num_azioni) || (num_azioni == null || num_azioni == "" || num_azioni == 0))
  {
    if(!document.getElementById("err-msg-service")){
      var p = document.createElement("P");
      p.setAttribute("id","err-msg-service");
      p.style.color = "red";
      p.innerHTML="Inserire un valore corretto";
      document.getElementById("service-form").appendChild(p);
    }
    return false;
  }
  return true;
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validateLoginForm(){

    var err   = document.getElementById("errJs");
    err.style.color = "red";


    var email = document.getElementById("email").value;
    var pwd   = document.getElementById("password").value;

    /* Controllo se i campi sono non vuoti */
    if( (email == null || email == "") || (pwd == null || pwd == "") )
    {
      err.innerHTML = "Please fill in all fields<br>"; return false;
    }
    /* Controllo se l'email è valida tramite espressione regolare */
    if(!validateEmail(email)) { err.innerHTML = "Insert a valid email address<br>"; return false; }
    /* Controllo se la password ha lunghezza > 8 */
    if(pwd.length < 2) { err.innerHTML = "Password must be greater than 1 characters<br>"; return false;}
    /* Controllo se la password e la conferma corrispondono */
    if(pwd!=cpwd){ err.innerHTML = "Password and confirmation doesn't matches<br>"; return false; }



    return true;
}



function validateRegForm(){

    var err   = document.getElementById("errJs");
    err.style.color = "red";

    var nome    = document.getElementById("nome").value;
    var cognome = document.getElementById("cognome").value;
    var email   = document.getElementById("email").value;
    var pwd     = document.getElementById("password").value;
    var cpwd    = document.getElementById("cpassword").value;


    /* Controllo se i campi sono non vuoti */
    if( (nome == null || nome == "") || (cognome == null || cognome == "") || (email == null || email == "") || (pwd == null || pwd == "") || (cpwd == null || cpwd == "") )
    {
      err.innerHTML = "Please fill in all fields<br>"; return false;
    }
    /* Controllo se l'email è valida tramite espressione regolare */
    if(!validateEmail(email)) { err.innerHTML = "Insert a valid email address<br>"; return false; return false; }

    if(nome.length < 3 || nome.length>=32) { err.innerHTML = "Name length must be between 3 and 32 characters<br>"; return false;return false; }
    if(cognome.length < 3 || cognome.length>=32) { err.innerHTML = "Surname length must be between 3 and 32 characters<br>"; return false;return false; }
    if(pwd.length < 2) { err.innerHTML = "Password must be greater than 1 characters<br>"; return false;return false; }



    return true;
}
