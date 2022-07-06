<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "./config.php";

if ($_POST) {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $rePassword = $_POST["re_password"];
  $photo = $_FILES["photo"];
  $photo_name = $_FILES["photo"]['name'];
  $tmp = $_FILES["photo"]['tmp_name'];

  $file = "registered_photo/" . $photo_name;
  $type = pathinfo($file, PATHINFO_EXTENSION);

  $statement = $db->prepare("SELECT * FROM users WHERE email=:email");
  $statement->execute([':email' => $email]);

  $user = $statement->fetch(PDO::FETCH_ASSOC);
    // print_r("<pre>");
    // print_r($user); exit();
    if (!empty($email) && !empty($email) && !empty($password)) {
      if ($password != $rePassword) {
        echo "<script>alert('ooh!password does not match!.');</script>";
      } else {
        if ($user['email'] == $email) {
          echo "<script>alert('This email is already exits!')</script>";
        } else {        
        //insert into database      
        if ($type != 'jpg' && $type != 'jpeg' && $type != 'png') {
          echo "<script>alert('Please choose png,jpeg,jpg!.');</script>";
        } else {
          move_uploaded_file($tmp, $file);

          $statmt = $db->prepare("INSERT INTO users (name,email,password,photo) VALUES (:name,:email,:password,:photo)");
          $statmt->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => password_hash($password,PASSWORD_DEFAULT),
            ":photo" => $photo_name,
          ]);
          header("location: login.php?registered=true");
        }
      }
    }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>socalblog | Registration Page</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./AdminLTE-master/dist/css/adminlte.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./AdminLTE-master/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
</head>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a class="h1"><b>Social</b>Blog</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg text-success">Register a new membership</p>

        <form action="register.php" method="post" enctype="multipart/form-data">
          <div class="input-group mb-3">
            <input type="text" name="name" class="form-control <?php if (isset($_POST['name']) == " ") {
                                                                  echo "is-invalid";
                                                                } ?>" placeholder="Full name" value="<?php if (isset($name)) {
                                                                                                        echo "$name";
                                                                                                      } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control <?php if (isset($_POST['email']) == " ") {
                                                                    echo "is-invalid";
                                                                  } ?>" placeholder="Email" value="<?php if (isset($email)) {
                                                                                                      echo "$email";
                                                                                                    } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control <?php if (isset($_POST['password']) == " ") {
                                                                          echo "is-invalid";
                                                                        } ?>" placeholder="Password" value="<?php if (isset($password)) {
                                                                                                              echo "$password";
                                                                                                            } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="re_password" class="form-control" placeholder="Retype password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <!-- <span class="text-danger"><?php if (isset($_GET['notmatch'])) {
                                            echo "password does not match!.";
                                          } ?></span> -->
          <div class="mb-3">
            <label for="formFileSm" class="form-label">Choose your profile photo</label>
            <input class="form-control-sm" name="photo" id="formFileSm" type="file">
          </div>
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </form><br>
        <a href="login.php" class="text-center">I already have an account.</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery -->
<script src="./AdminLTE-master/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./AdminLTE-master/dist/js/adminlte.min.js"></script>
</body>

</html>