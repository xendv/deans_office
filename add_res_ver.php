<?php
    //Подключаем код с функциями
    session_start();
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;

    $response['select_term_error'] = "";
    $response['select_discipline_error'] = "";
    $response['select_mark_error'] = "";

    //Фильтруем (убираем лишние пробелы)
    $selected_term = filtered_input($_POST['select_term']);
    $selected_discipline = filtered_input($_POST['select_discipline']);
    $selected_mark = filtered_input($_POST['select_mark']);
    $id_student=$_POST['id_gr'];

    //Подключаемся к БД
    include_once ("config.php");
    $link = link_to_db("deansoff_db");
    echo mysqli_error($link);

    //Проверка данных
    if(empty($selected_term)){
            $response['select_term_error'] = '* Заполните поле!';
            $response['success'] = false;
    } 
    if(empty($selected_discipline)){
        $response['select_discipline_error'] = '* Заполните поле!';
        $response['success'] = false;
    } 
    if(empty($selected_mark)){
        $response['select_mark_error'] = '* Заполните поле!';
        $response['success'] = false;
    } 
    /*Проверяем существует ли у нас
    такой пользователь в БД
    $sql = "SELECT `full_name` FROM `students` WHERE `full_name` = '". $new_st_full_name."'";
    $res =  mysqli_query($link, $sql);
    if(mysqli_num_rows($res)>0){
        $response['new_st_error'] = '* Студент: '. $new_st_full_name .' уже есть в группе!';
        $response['success'] = false;
    }*/
    //Проверяем наличие ошибок
    if($response['success']){
        $sql = "INSERT INTO `results` (`id_student`, `id_discipline`, `mark`) VALUES ".
                    "('".$id_student."','".$selected_discipline."','".$selected_mark."')";  
        echo mysqli_error($link);
        $res=mysqli_query($link,$sql);
        echo mysqli_error($link);
        if(!$sql){
            $response['success'] = false;
        }
    }
    echo json_encode($response);
?>           