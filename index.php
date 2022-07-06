<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['logined'])) {
  header('location: login.php');
}
require('./config.php');

try {
  $statement = $db->prepare("SELECT * FROM posts ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  // print_r($result); exit();
} catch (PDOException $e) {
  echo "not connected" . $e->getMessage();
}

$statement = $db->prepare("SELECT name FROM users");
$statement->execute();
$user = $statement->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>News Feed</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./AdminLTE-master/dist/css/adminlte.min.css">
</head>
<style>
  * {
    margin: 0;
    padding: 0;
  }

  body {
    scroll-behavior: smooth;
  }

  ::-webkit-scrollbar {
    width: 2px;
  }

  ::-webkit-scrollbar-track {
    background-color: rgb(255, 255, 255);
  }

  ::-webkit-scrollbar-thumb {
    background: #f00;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: #555;
  }

  .cover_img:hover {
    transform: scale(1.01);
    transition: 1s ease-in-out;
  }

  .loader-page {
    background-color: black;
    z-index: 1;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .display-4 {
    font-family: Arial, Helvetica, sans-serif;
  }
</style>

<body>
  <input type="hidden" value="<?= $_SESSION['user_name'] ?>">
  <!-- <img src="./theearthbottom.jpg" alt="thearth" width="100%" height="30px" object-fit="contain"> -->
  <div class="loader-page">
    <img src="loading_icon.png" alt="loading" width="100px">
    <sapn class="text-success display-5">Wait just a moment!. <pre class="text-success">Loading ......</pre></sapn>
  </div>
  <div class="container-fluid">
    <h1 class="text-center"><small>Social</small><b class="text-primary">Blog</b></h1>
    <div class="row">
      <?php foreach ($result as $x) : ?>
        <div class="col-sm-6 col-lg-3 col-md-3">
          <div class="card">
            <img src="images/<?= $x->image ?>" class="cover_img" alt="photo" width="100%;" height="320vh;" object-fit="contain;">
            <div class="card-body">
              <h5 class="card-title"><?= escape($x->title) ?></h5>
              <p class="card-text"><?= escape(substr($x->body, 0, 70)) ?> ........</p>
              <a href="blogdetail.php?id=<?= $x->id ?>" class="btn btn-danger">More Page...</a>
              <!-- <a href="<?= $x->id ?>" class="fas fa-thumbs-up float-right thumb"></a> -->
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="text-center <?php if ($_SESSION['role_id'] != 1) {
                            echo "d-none";
                          } ?>">
    <a href="admin.php">Admin</a>
  </div>
  <footer class="d-flex justify-content-around">
    <strong>Copyright &copy; 2014-2021.All rights reserved.</strong>
    <div class="d-sm-block">
      <b><a href="logout.php" class="btn btn-secondary">logout</a></b>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    // page loading while data is still fetching....
    $(window).on('load', function() {
      //set time to 1s for loading page......
      setTimeout(() => {
        $(".loader-page").fadeOut("slow");
      }, 800);
    });
  </script>
  <script src="./AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="./AdminLTE-master/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="./AdminLTE-master/dist/js/demo.js"></script> -->
</body>

</html>