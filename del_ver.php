<?php
    //Подключаем код с функциями
    include_once("functions.php");
    //Инициализация переменных проверки ошибок
    $response = array();
    $response['success'] = true;

    $response['del_login_error'] = "";

    //Фильтруем логин (убираем лишние пробелы)
    $c_login = filtered_input($_POST['del_login']);

    //Проверка данных
    if(empty($_POST['del_login'])){
        $response['del_login_error'] = '* Поле не может быть пустым!';
        $response['success'] = false;
    } 
    else{
        //Подключаемся к БД
        include_once ("config.php");
        //include_once ("bd.php");
        $link = link_to_db("DB_DATABASE");
        /*Проверяем существует ли у нас
        такой пользователь в БД*/
        $sql = "SELECT `login` FROM `users` WHERE `login` = '". $c_login ."'";
        $res =  mysqli_query($link, $sql);
        if(mysqli_num_rows($res)<=0){
            $response['del_login_error'] = '* Логин: '. $c_login .' не найден!';
            $response['success'] = false;
        }
    }
    //Проверяем наличие ошибок
    if($response['success']){
        //Удаляем пользователя из БД. Берем из БД users id пользователя  
        $sql ="SELECT `id` FROM `users` WHERE `login` = '". $c_login ."'";
        $res =  mysqli_query($link, $sql);
        if(!$sql){
            $response['success'] = false;
        }
        else{
            //Удаление из БД с результатами тестов
            $id_user = mysqli_fetch_array($res)['id'];
            $sql ="DELETE FROM `test_results` WHERE `user_id` = '".$id_user."'";
            $res =  mysqli_query($link, $sql);
            if(!$sql){
                $response['success'] = false;
            }
            else{
                //Удаление из БД users
                $sql ="DELETE FROM `users` WHERE `id` = '".$id_user."'";
                $res =  mysqli_query($link, $sql);
                if(!$sql){
                    $response['success'] = false;
                }
            }
        }
    }
    echo json_encode($response);
?>           