<?php
    //Подключаем код с функциями
    session_start();
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;

    $response['select_curr_error'] = "";
    $response['select_depart_error'] = "";
    $response['group_name_error'] = "";

    //Фильтруем (убираем лишние пробелы)
    $c_group_name = filtered_input($_POST['group_name']);
    $c_select_curr_num = filtered_input($_POST['select_curr']);
    $c_select_depart = filtered_input($_POST['select_depart']);

    //Подключаемся к БД
    include_once ("config.php");
    $link = link_to_db("deansoff_db");
    echo mysqli_error($link);
    /*Проверяем существует ли у нас
    такой пользователь в БД*/
    $sql = "SELECT `name` FROM `groups` WHERE `name` = '". $c_group_name ."'";
    $res =  mysqli_query($link, $sql);
    //Проверка данных
    if(empty($c_group_name)){
            $response['group_name_error'] = '* Поле не может быть пустым!';
            $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Я0-9-]+$/u',$c_group_name)){
            $response['group_name_error'] = '* Разрешены только русские буквы, тире и цифры!';
            $response['success'] = false;
    }
    if(empty($c_select_curr_num) || $c_select_curr_num==0){
            $response['select_curr_error'] = '* Поле не может быть пустым!';
            $response['success'] = false;
    } 
    if(empty($c_select_depart) || $c_select_depart==0){
            $response['select_depart_error'] = '* Поле не может быть пустым!';
            $response['success'] = false;
    } 
    
    if(mysqli_num_rows($res)>0){
        $response['group_name_error'] = '* Группа: '. $c_group_name .' уже существует!';
        $response['success'] = false;
    }
    //Проверяем наличие ошибок
    if($response['success']){
        $sql = "SELECT `id_curriculum` FROM `curriculum` WHERE `num` = '". $c_select_curr_num ."'";
        echo mysqli_error($link);
        $row=mysqli_fetch_assoc(mysqli_query($link,$sql));
        $c_select_curr=$row['id_curriculum'];
        $sql = "INSERT INTO `groups` (`id_curriculum`, `name`) VALUES ".
                    "('$c_select_curr','$c_group_name')";  
        echo mysqli_error($link);
        $res=mysqli_query($link,$sql);
        echo mysqli_error($link);
        if(!$sql){
            $response['success'] = false;
        }
    }
    echo json_encode($response);
?>           