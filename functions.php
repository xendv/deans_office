<?php
    require_once("config.php");
    function  filtered_input($data){
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        return $data;
    }
    function  link_to_db($db_name){
        $link = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, $db_name) or die("Не удалось подключиться!");
        if (!$link) { 
           echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error(); 
           exit; 
        } 
        mysqli_set_charset ($link , 'utf8');
        return ($link);
    }
?>