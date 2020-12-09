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

    $response['existence_check_error'.$id_student]="";

    //Подключаемся к БД
    include_once ("config.php");
    $link = link_to_db("deansoff_db");
    echo mysqli_error($link);

    //Проверка данных
    if(empty($selected_term)){
            $response['select_term_error'.$id_student] = '* Заполните поле!';
            $response['success'] = false;
    } 
    if(empty($selected_discipline)){
        $response['select_discipline_error'.$id_student] = '* Заполните поле!';
        $response['success'] = false;
    } 
    if(empty($selected_mark)){
        $response['select_mark_error'.$id_student] = '* Заполните поле!';
        $response['success'] = false;
    } 
    /*Проверяем существует ли у нас
    результат по этому предмету в БД*/
    $sql = "SELECT * FROM `results` WHERE `id_student` = '". $id_student."' AND `id_discipline`=".$selected_discipline;
    $res =  mysqli_query($link, $sql);
    if(mysqli_num_rows($res)>0){
        $response['existence_check_error'.$id_student] = '* У студента уже есть результат по этому предмету! Если необходимо, модифицируйте оценку в таблице';
        $response['success'] = false;
    }
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