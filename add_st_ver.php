<?php
    //Подключаем код с функциями
    session_start();
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;

    $response['new_st_f_name_error'] = "";
    $response['new_st_name_error'] = "";
    $response['new_st_m_name_error'] = "";
    $response['new_st_error'] ="";

    //Фильтруем (убираем лишние пробелы)
    $new_st_f_name = filtered_input($_POST['new_st_f_name']);
    $new_st_name = filtered_input($_POST['new_st_name']);
    $new_st_m_name = filtered_input($_POST['new_st_m_name']);

    //Подключаемся к БД
    include_once ("config.php");
    $link = link_to_db("deansoff_db");
    echo mysqli_error($link);

    //Проверка данных
    if(empty($new_st_f_name)){
            $response['new_st_f_name_error'] = '* Поле не может быть пустым!';
            $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Я]+$/u',$new_st_f_name)){
            $response['new_st_f_name_error'] = '* Разрешены только русские буквы!';
            $response['success'] = false;
    }
    if(empty($new_st_name)){
        $response['new_st_name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Я]+$/u',$new_st_name)){
            $response['new_st_name_error'] = '* Разрешены только русские буквы!';
            $response['success'] = false;
    }
    if(empty($new_st_m_name)){
        $response['new_st_m_name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Я]+$/u',$new_st_m_name)){
        $response['new_st_m_name_error'] = '* Разрешены только русские буквы!';
        $response['success'] = false;
    }
    /*Проверяем существует ли у нас
    такой пользователь в БД*/
    $new_st_full_name=$new_st_f_name." ".$new_st_name." ".$new_st_m_name;
    $sql = "SELECT `full_name` FROM `students` WHERE `full_name` = '". $new_st_full_name."'";
    $res =  mysqli_query($link, $sql);
    if(mysqli_num_rows($res)>0){
        $response['new_st_error'] = '* Студент: '. $new_st_full_name .' уже есть в группе!';
        $response['success'] = false;
    }
    //Проверяем наличие ошибок
    if($response['success']){
        $sql = "INSERT INTO `students` (`full_name`, `id_group`) VALUES ".
                    "('".$new_st_full_name."','".$_POST['id_gr']."')";  
        echo mysqli_error($link);
        $res=mysqli_query($link,$sql);
        echo mysqli_error($link);
        if(!$sql){
            $response['success'] = false;
        }
    }
    echo json_encode($response);
?>           