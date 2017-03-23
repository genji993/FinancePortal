
    <div class="sidebar-nav">

      <div class="navbar navbar-default" role="navigation">


          <ul class="nav navbar-nav">
            <li><a href="index.php">Homepage</a></li>
            <?php

              if(!isset($_SESSION['user']))
                echo "<li><a href='login.php'>Login</a></li>
                      <li><a href='register.php'>Register</a></li>";
              else
                echo "<li><a href='movements.php'>Movimenti</a></li>
                      <li><a href='shared/logout.php'>Logout</a></li>
                      ";


            ?>
          </ul>

      </div>
    </div>
