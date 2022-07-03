<?php
session_start();
  $token= $_SESSION["token"];
  $login= $_SESSION["user"]["login"];
?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style/mainStyle.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<header>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
    <li class="active"><a class="navbar-brand active" href="#">Black Foxes</a></li>
    </div>
    <ul class="nav navbar-nav">
    <li class="nav-item">
      <a class="nav-link" " href="#">1</a>
    </li>  
    <li class="nav-item">
      <a class="nav-link" " href="#">1</a>
    </li> 
    <li class="nav-item">
      <a class="nav-link" " href="#">1</a>
    </li> 
    <li class="nav-item">
      <a class="nav-link" " href="#">1</a>
    </li> 
      <li><a href="#">Page 2</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    <?php if(!$token){ ?>
      <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
      <li class="loginBtn" onclick="loginBtn()"><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
    <?php }else{ ?>
    <li class="regist" onclick="regist()"><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $login?></a></li>
    <li class="logout" onclick="navbar_logout()"><a href="#"><span class="glyphicon glyphicon-log-in"></span>logout</a></li>
    <?php } ?>
    </ul>
  </div>
</nav>

<script>
let token = `<?php echo $token;?>`

</script>
<!-- Navbar -->
</header>
