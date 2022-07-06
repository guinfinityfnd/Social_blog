<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 session_start();
 require("./config.php");

 if($_POST){
  $email = $_POST["email"];
  $password = $_POST["password"];

  $statement = $db->prepare("SELECT * FROM users WHERE email=:email");
  $statement->execute([':email'=>$email]);

  $user = $statement->fetch(PDO::FETCH_ASSOC);
  if ($user) {
    if(password_verify($password,$user['password'])){
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['logined'] = time();
      $_SESSION['role_id'] = $user['role_id'];
      $_SESSION['photo'] = $user['photo'];

      header('location: index.php');
    }
  }
    echo "<script>alert('email or password is incorrect');</script>";  
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>socialblog | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./AdminLTE-master/dist/css/adminlte.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./AdminLTE-master/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
</head>
<style>
  @keyframes cana {
    from{color: blue;}
    to{color: greenyellow;}
  }
  #cana{
    animation: cana 2s ease-in-out infinite;
  }
</style>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="./login.php"><b>Account</b>login</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in account</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form><br>
      <p class="mb-0">
        <a href="register.php" class="text-center" id="cana"><small>***</small>Create a new account. <small>***</small></a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="./AdminLTE-master/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./AdminLTE-master/dist/js/adminlte.min.js"></script>
</body>
</html>
