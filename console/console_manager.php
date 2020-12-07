<?php
session_start();
include_once("../functions.php");
include_once("../course/test_functions.php");
if (isset($_SESSION['user_group_id'])){
    if($_SESSION['user_group_id']=="1"){
        if(isset($_POST['request'])){
            // Setup database
            $connection = link_to_db("trainer");
            switch ($_POST['request']){
            case "get_all_users":
                $sql = "SELECT * FROM `users` WHERE `group_id`=3";
                $query = mysqli_query($connection,$sql);
                echo <<<EOF
                    <thead>
                        <tr><th>Фамилия</th> <th>Имя</th> <th>Отчество</th> <th>Логин</th> <th>Результаты</th><th></th></tr>
                    </thead>
                EOF;
                while($data = mysqli_fetch_assoc($query)){
                    echo <<<EOF
                        <tbody>
                        <tr>
                            <td>{$data['family_name']}</td>
                            <td>{$data['first_name']}</td>
                            <td>{$data['middle_name']}</td>
                            <td>{$data['login']}</td>
                            <td><a href="#" class="drop" id="{$data['id']}">Показать</a></td>
                            <td><button class="btn btn-outline-danger" style="margin: 0 auto" id="{$data['login']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="dropdown" id="{$data['id']}" style="display: none;">
                                <p>Опаньки</p>
                            </td>
                        </tr>
                    EOF;
                    echo '</tbody>';
                }
                break;
            case "get_user_result":
                if(isset($_POST['user_id'])){
                    $task_db = link_to_db("nnka_db");
                    echo <<<EOF
                    <div class="row">
                        <div class="col-6">
                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" colspan='2'># Прогресс по курсам</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Курс</th>
                                            <th scope="col">% выполнения</th>
                                        </tr>
                                    </thead>
                    EOF;
                    $query = mysqli_query($connection,"SELECT * FROM courses");
                    while($data = mysqli_fetch_assoc($query)){
                        echo <<<EOF
                        <tr>
                            <td>{$data['COURSE_NAME']} {$data['COURSE_SUB_NAME']}</td>
                            <td><b>
                        EOF;
                        echo get_course_result($data['SUB_TYPE'], $_POST['user_id']);
                        echo "%</b></td></tr>";
                    }
                    echo '</table></div>';
                    echo <<<EOF
                        <div class="col-6">
                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" colspan="3"># Выполненные тесты</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Название</th>
                                            <th scope="col">Результат</th>
                                        </tr>
                                    </thead>
                    EOF;
                    $result_query = mysqli_query($connection,"SELECT * FROM test_results WHERE user_id='{$_POST['user_id']}' ORDER BY test_id");
                    while($result_data = mysqli_fetch_assoc($result_query)){
                        echo <<<EOF
                        <tr>
                            <th scope = 'row'><b>{$result_data['test_id']}</b></th>
                        EOF;
                        $test_query = mysqli_query($task_db,"SELECT * FROM TestHeaders WHERE ID='".$result_data['test_id']."'");
                        $test_data = mysqli_fetch_assoc($test_query);
                        echo <<<EOF
                        <td>{$test_data['TEST_NAME']}</td>
                        <td><b>{$result_data['score']}</b></td>
                        </tr>
                        EOF;
                    }
                    echo '</table></div></div>';
                }
                break;
            case "test":
                echo "<tr><th scope='row'>test</th></tr>";
                break;
            case "remove":
                if(isset($_POST['user'])){
                    //Удаляем пользователя из БД. Берем из БД users id пользователя  
                    $sql ="SELECT `id` FROM `users` WHERE `login` = '". $_POST['user'] ."'";
                    $res =  mysqli_query($connection, $sql);
                    if($sql){
                        //Удаление из БД с результатами тестов
                        $id_user = mysqli_fetch_array($res)['id'];
                        $sql ="DELETE FROM `test_results` WHERE `user_id` = '".$id_user."'";
                        $res =  mysqli_query($connection, $sql);
                        if($sql){
                            //Удаление из БД users
                            $sql ="DELETE FROM `users` WHERE `id` = '".$id_user."'";
                            $res =  mysqli_query($connection, $sql);
                        }
                    }
                }
                break;
            default :
                break;
            }
            mysqli_close($connection);
        }
    }
}
