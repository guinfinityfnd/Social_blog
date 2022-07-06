<?php
    $server_name = "root";
    $server_password = "";
    
    $db = new PDO("mysql:host=localhost;dbname=blog",$server_name,$server_password,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ]);

    function escape($data)
    {
       return htmlspecialchars($data,ENT_QUOTES | ENT_SUBSTITUTE,"UTF-8",true);
    }
?>
