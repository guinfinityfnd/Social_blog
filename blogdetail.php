 <?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['logined'])) {
  header('location: login.php');
}
require('./config.php');

$id = $_GET['id'];
try {
    $statement = $db->prepare("SELECT * FROM posts where id=" . $id);
    $statement->execute();
    $result = $statement->fetchAll();
} catch (PDOException $e) {
    echo "not connected" . $e->getMessage();
}

//comments codes//

if (isset($_POST['send'])) {
    $comment = $_POST["comment"];
    $sql = "INSERT INTO comments (content,com_name,author_id,post_id) VALUES (:content,:com_name,:author_id,:post_id)";
    $comment_statement = $db->prepare($sql);
    $cmInsert = $comment_statement->execute([
        ':content' => $comment,
        ':com_name' => $_SESSION['user_name'],
        ':author_id' => $_SESSION['user_id'],
        ':post_id' => $id,
    ]);
}


try {
    $stat = $db->prepare("SELECT * FROM comments WHERE post_id=" . $id);
    $stat->execute();
    $cmResult = $stat->fetchAll();
} catch (PDOException $e) {
    echo "sorry something is wrong" . $e->getMessage();
}

// try {
//     // $roleIdName = $db->prepare("SELECT users.id,users.role_id,comments.author_id FROM `users` INNER JOIN `comments` ON comments.author_id WHERE role_id=1");
//     $roleIdName = $db->prepare("SELECT author_id FROM comments WHERE author_id=1");
//     $roleIdName->execute();
//     $role = $roleIdName->fetchAll();
//     //  print("<pre>");
//     // print_r($role);
//     // exit();
// } catch (PDOException $e) {
//     echo "Errrorrrr" . $e->getMessage();
// }
// $cmId = $cmResult;
// print("<pre>");
// var_dump($cmI);exit();
// $comment_count = $db->prepare("SELECT * FROM users INNER JOIN comments ON users.id=comments.author_id");
// $comment_count->execute();
// $all_comments = $comment_count->fetch();
// print("<pre>");
// print_r($comment_count->name);exit();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>social blog | User Profile</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./AdminLTE-master/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./AdminLTE-master/dist/css/adminlte.min.css">
</head>
<style>
    #user_profile_photo {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .comment_div {
        overflow: auto;
        height: 400px;
    }
</style>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12 d-flex justify-content-between bg-info rounded">
                        <h1 class="display-4"><small>Social</small><b>Blog</b></h1>
                        <div class="img_container">
                            <img src="./registered_photo/<?= $_SESSION['photo']; ?>" alt="user_profile_photo" id="user_profile_photo">
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- /.col -->
                    <?php foreach ($result as $x) : ?>
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
                                                        <?php
                                                        try {
                                                            $stat = $db->prepare("SELECT * FROM users WHERE role_id='1'");
                                                            $stat->execute();
                                                            $admin = $stat->fetch();
                                                        } catch (PDOException $e) {
                                                            echo "sorry something is wrong" . $e->getMessage();
                                                        }
                                                        ?>
                                                        <a href="#"><?= $admin->name; ?></a>
                                                    </span>
                                                    <span class="description">Shared publicly - <?= date("D_j_y", strtotime($x->created_at)); ?></span>
                                                </div>
                                                <!-- /.user-block -->
                                                <div class="header_title">
                                                    <h3><?= escape($x->title) ?></h3>
                                                </div>
                                                <p class="text-bold">
                                                    <?= escape($x->body) ?>
                                                </p>
                                                <hr>
                                                <!-- <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                                        <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a> -->
                                                <div class="float-right">
                                                    <i class="far fa-comments mr-1 text-sm link-black"></i> Comments (<?= count($cmResult) ?>)
                                                </div><br>
                                                <div class="comment_div">
                                                    <?php foreach ($cmResult as $cm) : ?>
                                                            <span class="fa fa-user-circle <?php echo $cm->author_id == 1 ?  "text-danger" :  "text-success" ?>">
                                                                <?= $cm->com_name ?>
                                                            </span>
                                                        <p class="display-5 text-dark">
                                                            <?= escape($cm->content) ?>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div><br>
                                                <form action="blogdetail.php?id=<?= $id ?>" method="post">
                                                    <input class="form-control form-control-sm" name="comment" type="text" placeholder="Type a comment"><br>
                                                    <button type="submit" class="btn btn-primary" name="send">send</button>
                                                    <a href="index.php" class="btn btn-danger">Back..</a>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    <?php endforeach; ?>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!-- /.content-wrapper -->
        <footer class="">
            <div class="d-none d-sm-block">
                <b>SocialBlog</b> version 1.0.0
            </div>
            <strong>Copyright &copy; 2016-2024.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="./AdminLTE-master/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="./AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./AdminLTE-master/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="./AdminLTE-master/dist/js/demo.js"></script> -->
</body>

</html>