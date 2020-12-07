<?php
    session_start();
    require_once("functions.php");
    $response = array();
    $response['success'] = true;
    $response['pass_error'] = "";
    $response['login_error'] = "";
    $c_login = filtered_input($_POST['email']);

    if(empty($_POST['email']))
    {
        $response['login_error'] = '* Не введен Логин';
        $response['success'] = false;
    } 
               
    if(empty($_POST['passwd'])){
        $response['pass_error'] = '* Не введен Пароль';
        $response['success'] = false;
    }    
     //Проверяем наличие ошибок
     if($response['success']){
        //include_once ("config.php");
        //Подключаемся к БД users
        $link = link_to_db('deansoff_db');
        /*Создаем запрос на выборку из базы 
        данных для проверки подлиности пользователя*/
        $sql = "SELECT * FROM `users` WHERE `login` = '". $_POST['email'] ."'";
        $res = mysqli_query($link, $sql);
        if(mysqli_num_rows($res)<=0){
            $response['login_error'] = '* Логин: '. $c_login .' не найден!';
            $response['success'] = false;
        }
        else{
            //Если логин совпадает, проверяем пароль
            if(mysqli_num_rows($res) > 0){
                //Получаем данные из таблицы
                $row = mysqli_fetch_assoc($res);

                if(md5($_POST['passwd']) == $row['password']){	        
                    session_destroy();
                    session_id($row['id']);
                    session_start();

                    $_SESSION['user'] = true;
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_login']=$row['login'];
                }
                else{
                    $response['pass_error'] = '* Неверный пароль!';
                    $response['success'] = false;
                }
            }
        }
    }

    echo json_encode($response);
        
?>