<?php
    //Подключаем код с функциями
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;
    $response['pass_error'] = "";
    $response['pass2_error'] = "";
    $response['login_error'] = "";
    $response['f_name_error'] = "";
    $response['name_error']= "";
    $response['m_name_error']= "";
    //Фильтруем логин (убираем лишние пробелы)
    $c_login = filtered_input($_POST['login']);
    $c_f_name = filtered_input($_POST['f_name']);
    $c_name = filtered_input($_POST['name']);
    $c_m_name = filtered_input($_POST['m_name']);
    //Проверка данных
    if(empty($_POST['login'])){
        $response['login_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } else {
        //Проверяем, что ввдена электронная почта
        if(!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $c_login)){
            $response['login_error'] = '* Неправильно введена почта!';
            $response['success'] = false;
        }
    }
    if(empty($_POST['pass'])){
        $response['pass_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (empty(filtered_input($_POST['pass']))){
        $response['pass_error'] = '* В целях безопасности Пароль не может состоять из одних пробелов!';
        $response['success'] = false;
    }
    if(empty($_POST['pass_2'])) {
        $response['pass2_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    }
    if(empty($c_name)){
        $response['name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Яa-zA-Z]+$/u',$c_name)){
        $response['name_error'] = '* Разрешены только русские и латинские буквы!';
        $response['success'] = false;
    }
    if(empty($c_f_name)){
        $response['f_name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Яa-zA-Z]+$/u',$c_f_name)){
        $response['f_name_error'] = '* Разрешены только русские и латинские буквы!';
        $response['success'] = false;
    }
    if(empty($c_m_name)){
        $response['m_name_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } elseif (!preg_match('/^[а-яА-Яa-zA-Z]+$/u',$c_m_name)){
        $response['m_name_error'] = '* Разрешены только русские и латинские буквы!';
        $response['success'] = false;
    }
    /*Продолжаем проверять введеные данные
    Проверяем на совподение пароли*/
    if($_POST['pass'] != $_POST['pass_2']){
        $response['pass2_error'] = '* Пароли не совпадают';
        $response['success'] = false;
    }
    //Подключаемся к БД
    include_once ("config.php");
    //include_once ("bd.php");
    $link = link_to_db(DB_DATABASE);
    /*Проверяем существует ли у нас
    такой пользователь в БД*/
    $sql = "SELECT `login` FROM `users` WHERE `login` = '". $c_login ."'";
    $res =  mysqli_query($link, $sql);
    if(mysqli_num_rows($res)>0){
        $response['login_error'] = '* К сожалению Логин: '. $c_login .' занят!';
        $response['success'] = false;
    }
    //Проверяем наличие ошибок
    if($response['success']){
        //Хешируем пароль
        $pass = md5($_POST['pass']);
        if(isset($_POST['make_t'])) $gr_id=2;
        else $gr_id=3;
        //Вносим данные в БД users
        $sql ="INSERT INTO `users` (`login`, `family_name`, `first_name`, `middle_name`, `password`,`group_id`) VALUES ('". $c_login."','".$c_f_name."','". $c_name."','". $c_m_name."','".$pass."','".$gr_id."')";
        $res =  mysqli_query($link, $sql);
        if(!$sql){
            $response['success'] = false;
        }
        //header('Location:'. 'main.php');
    }
    echo json_encode($response);
?>           