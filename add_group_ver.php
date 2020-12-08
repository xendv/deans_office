<?php
    //Подключаем код с функциями
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;

    $response['select_curr_error'] = "";
    $response['select_depart_error'] = "";
    $response['group_name_error'] = "";

    //Фильтруем (убираем лишние пробелы)
    $c_group_name = filtered_input($_POST['group_name']);
    $c_select_curr = filtered_input($_POST['select_curr']);
    $c_select_depart = filtered_input($_POST['select_depart']);

    //Проверка данных
    if(empty($c_group_name)){
        $response['group_name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Я0-9-]+$/u',$c_group_name)){
        $response['group_name_error'] = '* Разрешены только русские буквы, тире и цифры!';
        $response['success'] = false;
    }
    if(empty($c_select_curr) || $c_select_curr==0){
        $response['select_curr_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } 
    if(empty($c_select_depart) || $c_select_depart==0){
        $response['select_depart_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } 

    //Проверяем наличие ошибок
    if($response['success']){
        //Подключаемся к БД
        include_once ("config.php");
        $link = link_to_db("DB_DATABASE");
        //$sql = "SELECT `login` FROM `users` WHERE `login` = '". $c_login ."'";
        echo mysqli_error($connection);
        $sql = "INSERT INTO `groups` ( `id_group`, `id_curriculum`, `name`) VALUES ".
                    "('{$_SESSION['user_id']}','$c_select_curr','$c_group_name')";  
        echo mysqli_error($connection);
    }
    echo json_encode($response);
?>           