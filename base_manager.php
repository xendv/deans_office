<?php
session_start();
include_once("functions.php");
//if (isset($_SESSION['user'])){
        if(isset($_POST['request'])){
            // Setup database
            $connection = link_to_db("deansoff_db");
            //fix charset
            mysqli_set_charset ($connection , 'utf8');
            echo mysqli_error($connection);
            $filter_array = array();
            switch ($_POST['request']){
                case "get_all_groups":
                    $sql = "SELECT * FROM `groups`";
                    $query = mysqli_query($connection,$sql);
                    echo <<<EOF
                        <thead>
                            <tr><th>Группа</th> <th>Направление</th> <th>Кафедра</th> <th>Число студентов</th> <th>-</th> <th>Студенты группы</th><th></th></tr>
                        </thead>
                    EOF;
                    while($data = mysqli_fetch_assoc($query)){
                        $sql="SELECT COUNT(*) as count FROM `students` WHERE `id_group`=".$data['id_group'];
                        $query_s = mysqli_query($connection,$sql);
                        $student_count=mysqli_fetch_assoc($query_s)['count'];
                        //$sql="SELECT `num`, `name`, `id_department` FROM `curriculum` WHERE `id_curriculum`=".$data['id_curriculum'];
                        $sql="SELECT curriculum.num, curriculum.name, curriculum.id_department, departments.name as name1 FROM `curriculum`" 
                        ." INNER JOIN departments ON departments.id=curriculum.id_department"
                        ." WHERE `id_curriculum`=".$data['id_curriculum'];
                        
                        $row = mysqli_fetch_assoc(mysqli_query($connection,$sql));
                        echo mysqli_error($connection);

                        echo <<<EOF
                            <tbody>
                            <tr>
                                <td>{$data['name']}</td>
                                <td>{$row['num']} {$row['name']}</td>
                                <td>{$row['name1']}</td>
                                <td>{$student_count}</td>
                                <td><a href="#" class="" id="{$data['id_group']}">Изменить</a></td>
                                <td><a href="#" class="drop gr" id="{$data['id_group']}">Показать</a></td>
                                <td><button class="btn btn-outline-danger gr" style="margin: 0 auto" id="{$data['id_group']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="dropdown" id="{$data['id_group']}" style="display: none;">
                                    <!--<p>ОП</p>-->
                                    <div class="page-content white-bg students-block{$data['id_group']}"  id="students-block{$data['id_group']}">
                                    <table class="table" id="students_table{$data['id_group']}">
                                    </table>
                                    </div>
                                </td>
                            </tr>
                        EOF;
                        echo '</tbody>';
                    }
                break;
                case "get_students_by_group":
                        get_students_by_group($connection,$_POST['id_group']);
                    break;
                case "get_student_group_by_id":
                        $sql = "SELECT id_group FROM `students` WHERE id_student=".$_POST['id_student'];
                        $res=mysqli_fetch_assoc(mysqli_query($connection,$sql));
                        echo mysqli_error($connection);
                        echo $res['id_group'];
                    break;
                case "get_all_departments":
                        /*ЛОХ*/
                        $sql = "SELECT * FROM `users` WHERE `group_id`=3";
                        $query = mysqli_query($connection,$sql);
                        echo mysqli_error($connection);
                        echo <<<EOF
                            <thead>
                                <tr><th>Группа</th> <th>Направление</th> <th>Число студентов</th> <th>-</th> <th>Студенты группы</th><th></th></tr>
                            </thead>
                        EOF;
                        while($data = mysqli_fetch_assoc($query)){
                            echo <<<EOF
                                <tbody>
                                <tr>
                                    <td>{$data['name']}{$data['id_group']}</td>
                                    <td>{$data['id_curriculum']}</td>
                                    <td>N</td>
                                    <td>-</td>
                                    <td><a href="#" class="drop" id="{$data['id_group']}">Показать</a></td>
                                    <td><button class="btn btn-outline-danger" style="margin: 0 auto" id="{$data['login']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="dropdown" id="{$data['id_group']}" style="display: none;">
                                        <p>Опаньки</p>
                                    </td>
                                </tr>
                            EOF;
                            echo '</tbody>';
                        }
                    break;
                case "get_currs_for_options":
                    $query=getUniqueCurrList($connection);
                    echo <<<EOF
                    <option value="0" data-display="Выберите направление">Выберите направление</option>";
                    EOF;
                    while ($row=mysqli_fetch_assoc($query)){
                        echo <<<EOF
                        <option value="{$row['id_curriculum']}">{$row['num']} {$row['name']}</option>
                        EOF;
                    }
                break;
                    case "delete_group_by_id":
                        deleteGroupById($connection,$_POST['id_group']);
                    break;
                case "delete_student_by_id":
                        deleteStudentById($connection,$_POST['id_student']);
                    break;
            default :
                break;
            }
    }
//}

function get_students_by_group($connection,$id){
    $sql = "SELECT * FROM `students` WHERE `id_group`=".$id."";
    $query = mysqli_query($connection,$sql);
    echo mysqli_error($connection);
    echo <<<EOF
        <thead>
            <tr><th>ФИО</th> <th>Средний балл</th> <th>-</th> <th>Оценки</th></tr>
        </thead>
    EOF;
    while($data = mysqli_fetch_assoc($query)){
        echo mysqli_error($connection);
        echo <<<EOF
            <tbody>
            <tr>
                <td>{$data['full_name']}</td>
                <td>{$data['av_ball']}</td>
                <td><a href="#" class="" id="st_{$data['id_student']}">Изменить</a></td>
                <td><a href="#" class="drop" id="st_{$data['id_student']}">Показать</a></td>
                <td><button class="btn btn-outline-danger st" style="margin: 0 auto" id="{$data['id_student']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
            </tr>

        EOF;
        echo '</tbody>';
    }
}


function checkSelected($connection,$id){
    $query = mysqli_query($connection,"SELECT COURSE_ID FROM courses_selected WHERE USER_ID='{$_SESSION['user_id']}' AND COURSE_ID='$id'");
    echo mysqli_error($connection);
    return mysqli_num_rows($query) == 0;
}

//ниту
function addGroup($connection,$name,$id_cur,$id_dep){
    $query = mysqli_query($connection,"SELECT COURSE_ID FROM courses_selected WHERE USER_ID='{$_SESSION['user_id']}' AND COURSE_ID='$id'");
    echo mysqli_error($connection);
    return mysqli_num_rows($query) == 0;
}

function deleteGroupById($connection,$id){
    $query = mysqli_query($connection,"DELETE FROM groups WHERE id_group='{$id}'");
    echo mysqli_error($connection);
    //while ($query = mysqli_query($connection,"DELETE FROM students WHERE id_group='{$id}'")){

    //};
    echo mysqli_error($connection);
    return mysqli_num_rows($query) != 0;
}

function deleteStudentById($connection,$id){
    $query = mysqli_query($connection,"DELETE FROM students WHERE id_student='{$id}'");
    echo mysqli_error($connection);
    $query = mysqli_query($connection,"DELETE FROM results WHERE id_student='{$id}'");
    return mysqli_num_rows($query) != 0;
}

function getUniqueCurrList($connection){
    $query = mysqli_query($connection,"SELECT * FROM curriculum GROUP BY num");
    echo mysqli_error($connection);
    return $query;
}

function getDepsListForCurByNum($connection,$num){
    $sql="SELECT curriculum.id_curriculum, departments.name as name_department, departments.id as id_department FROM `departments`" 
                        ." INNER JOIN curriculum ON curriculum.id_department=departments.id"
                        ." WHERE curriculum.num=".$num;
    $query = mysqli_query($connection,$sql);
    $row=mysqli_fetch_assoc($query);
    echo mysqli_error($connection);

    return mysqli_fetch_assoc($query);
}


/*
function formDataArray($row){
    //form data array
    $response = array();
    $response['login'] = $_SESSION['user_login'];
    $response['f_name'] = $_SESSION['user_f_name'];
    $response['name']= $_SESSION['user_name'];
    $response['m_name']= $_SESSION['user_m_name'];
    $response['gender'] = $row['gender'];
    $response['nation'] = $row['nation'];
    $response['birth_date'] = $row['birth'];
    $response['number'] = $row['phone'];
    $response['adress'] = $row['address'];
    $response['pas_ser'] = $row['pas_ser'];
    $response['pas_num'] = $row['pas_num'];
    $response['pas_who'] = $row['pas_who'];
    $response['pas_code'] = $row['pas_code'];
    $response['pas_date'] = $row['pas_date'];
    $response['edu_type'] = $row['edu_type'];
    $response['edu_who'] = $row['edu_who'];
    $response['edu_num'] = $row['edu_num'];
    $response['edu_year'] = $row['edu_year'];
    return $response;
}*/