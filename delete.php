<?php 
    require("./config.php");

    $statement = $db->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
    $statement->execute();

    header('location: admin.php');
?>