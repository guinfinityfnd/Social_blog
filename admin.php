<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require("./config.php");

if ($_SESSION['role_id'] < 1) {
    header("location: 404.php?Unauthorized=true");
    exit();
}

if ($_POST) {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $authorId = $_POST['author_id'];

    $photo_name = $_FILES['photo']['name'];

    $file = "images/" . ($photo_name);
    $imgType = pathinfo($file, PATHINFO_EXTENSION);

    if ($imgType != "png" && $imgType != "jpg" && $imgType != "jpeg") {
        echo "<script>Please choose correct photo.</script>";
    } else {
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $type = $_FILES['photo']['type'];

        move_uploaded_file($photo_tmp, $file);

        $sql = "INSERT INTO posts (title,body,image,author_id) VALUES (:title,:body,:image,:author_id)";
        $statmt = $db->prepare($sql);
        $statmt->execute([
            ":title" => $title,
            ":body" => $body,
            ":image" => $photo_name,
            ":author_id" => $authorId
        ]);
        echo "<script>alert('data is successfully inserted.');</script>";
    }
}

try {
    $statement = $db->prepare("SELECT * FROM posts ORDER BY id DESC");
    $statement->execute();
    $result = $statement->fetchAll();

    $stam = $db->prepare("SELECT * FROM users");
    $stam->execute();
    $user_records = $stam->fetchAll();
    // print_r($user_records); exit();
} catch (PDOException $e) {
    echo "not connected" . $e->getMessage();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./AdminLTE-master/dist/css/adminlte.min.css">
</head>

<body class="bg bg-dark">
    <div class="container-fluid">
        <h1>Admin Post Publisher</h1>
        <!-- Default box -->
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <div class="card text-dark">
                <div class="card-body row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="inputName">Title</label>
                            <input type="text" name="title" id="inputName" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="inputMessage">Message</label>
                            <textarea id="inputMessage" name="body" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Send message">
                        </div>
                        <div class="form-group">
                            <select name="author_id" id="authorid">
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlFile1">Select your photos!.</label>
                <input type="file" name="photo" class="form-control-file" id="exampleFormControlFile1">
            </div>
        </form>
        <?php foreach ($result as $x) : ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="activity">
                                        <!-- Post -->
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm" src="./AdminLTE-master/dist/img/user1-128x128.jpg" alt="user image">
                                                <span class="username">
                                                    <div class="delete_box float-right">
                                                        <a href="delete.php?id=<?= $x->id ?>" class="btn btn-primary" onclick="return confirm('Are you sure!Admin?');">Delete Post</a>
                                                    </div>
                                                    <a href="#">
                                                        <?php echo $x->title ?>
                                                    </a>
                                                </span>
                                                <span class="description">Shared publicly - <?= date('Y-m-d', strtotime($x->created_at)) ?></span>
                                            </div>
                                            <p class="text-bold">
                                                <?= substr($x->body, 0, 50) . "...." ?>
                                            </p>
                                            <!-- /.post -->
                                        </div>
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                <!-- database table -->
                <!-- database table -->
                <!-- database table -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <a class="btn btn-secondary" href="index.php">Go Home</a>
                                    <div class="card-tools">
                                        <ul class="navbar-nav ml-auto">
                                            <li class="nav-item dropdown">
                                                <a class="nav-link" data-toggle="dropdown" href="">
                                                    <i class="far fa-bell"></i>
                                                    <span class="badge badge-danger navbar-badge"><?= count($user_records) ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0" style="height: 300px;">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr class="text-danger">
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Date</th>
                                                <th>Photo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            <?php foreach ($user_records as $alluser) : ?>
                                                <tr class="text-danger">
                                                    <td><?= $i; ?></td>
                                                    <td><?= $alluser->name ?></td>
                                                    <td><?= $alluser->email ?></td>
                                                    <td><?= $alluser->created_at ?></td>
                                                    <td><img src="registered_photo/<?= $alluser->photo ?>" alt="userphoto" width="50px;" height="50px"></td>
                                                </tr>
                                                <?php $i++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
</body>

</html>