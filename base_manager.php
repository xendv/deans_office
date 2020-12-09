<?php
session_start();
include_once("functions.php");
if (isset($_SESSION['user'])){
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
                                        <button class="orange_button add_st btn" id="{$data['id_group']}" name="add_st" href="">Добавить студента</button>
                                        <div class="show-add-st-block{$data['id_group']} align-items-center" id="add_st_block{$data['id_group']}" style="margin: 10px;display: none;">
                                            <div class="forms" style="align-items: center">
                                                <form id="{$data['id_group']}" class="add_st_form" action="" method="POST" novalidate>
                                                        <table class="table">
                                                        <p style="text-align: center;">Пожалуйста, заполните данные ниже, чтобы добавить студента в данную группу</p>
                                                        <tr>
                                                            <td><label>Фамилия</label></td>
                                                            <td><input type="text" class="form-control" name="new_st_f_name" size="15"
                                                                    maxlength="15" required>
                                                                <p id="new_st_f_name_error" class="has-error" style="text-align: center;"></p>
                                                            </td>
                                                            <td><label>Имя</label></td>
                                                            <td><input type="text" class="form-control" name="new_st_name" size="15"
                                                                    maxlength="15" required>
                                                                <p id="new_st_name_error" class="has-error" style="text-align: center;"></p>
                                                            </td>
                                                            <td><label>Отчество</label></td>
                                                            <td><input type="text" class="form-control" name="new_st_m_name" size="15"
                                                                    maxlength="15" required>
                                                                <p id="new_st_m_name_error" class="has-error" style="text-align: center;"></p>
                                                            </td>
                                                        </tr>
                                                        
                                                        </table>
                                                            <p id="new_st_error" class="has-error" style="text-align: center;"></p>
                                                        <hr>
                                                    <button class="orange_button add_st_btn btn" id="{$data['id_group']}" name="add_st_btn" href="">Подтвердить</button>
                                                </form>
                                            </div>
                                        </div>
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
                case "get_students_results":
                    get_students_results($connection,$_POST['id_student']);
                break;
                case "get_student_group_by_id":
                    echo get_student_group_by_id($connection,$_POST['id_student']);
                break;
                case "get_student_id_by_result":
                    echo get_student_id_by_result ($connection,$_POST['id_result']);
                break;
                case "update_av_ball":
                    updateAvBall($connection,$_POST['id_student']);
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
                        <option value="{$row['num']}">{$row['num']} {$row['name']}</option>
                        EOF;
                    }
                break;
                case "get_deps_for_options":
                    $query=getDepsListForCurByNum($connection,$_POST['num']);
                    echo <<<EOF
                    <option value="0" data-display="Выберите кафедру для прикрепления">Выберите кафедру для прикрепления</option>";
                    EOF;
                    while ($row=mysqli_fetch_assoc($query)){
                        echo <<<EOF
                        <option value="{$row['id_curriculum']}">{$row['name_department']}</option>
                        EOF;
                    }
                break;
                    case "delete_group_by_id":
                        deleteGroupById($connection,$_POST['id_group']);
                    break;
                case "delete_student_by_id":
                        deleteStudentById($connection,$_POST['id_student']);
                    break;
                case "delete_result":
                        deleteResultById($connection,$_POST['id_result']);
                    break;
            default :
                break;
            }
    }
}

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
                <td class="td_mark" id="{$data['id_student']}">{$data['av_ball']}</td>
                <td><a href="#" class="" id="st_{$data['id_student']}">Изменить</a></td>
                <td><a href="#" class="drop st" id="{$data['id_student']}">Показать</a></td>
                <td><button class="btn btn-outline-danger st" style="margin: 0 auto" id="{$data['id_student']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
            </tr>
            <tr>
                <td colspan="4" class="dropdown st" id="{$data['id_student']}" style="display: none;">
                    <table class="table" id="results_table{$data['id_student']}">
                    </table>
                    <button class="orange_button add_res small btn" id="{$data['id_student']}" name="add_res" href="">Добавить оценку</button>
                </td>
            </tr>

        EOF;
        echo '</tbody>';
    }
}

function get_student_group_by_id($connection,$id_student){
    $sql = "SELECT id_group FROM `students` WHERE id_student=".$id_student;
    $res=mysqli_fetch_assoc(mysqli_query($connection,$sql));
    echo mysqli_error($connection);
    return $res['id_group'];
    ///был echo
}

function get_student_id_by_result($connection,$id_result){
    $sql = "SELECT id_student FROM `results` WHERE id_result=".$id_result;
    $res=mysqli_fetch_assoc(mysqli_query($connection,$sql));
    echo mysqli_error($connection);
    return $res['id_student'];
}

function get_students_results($connection,$id_student){
    $id_group=get_student_group_by_id($connection,$id_student);
    //$sql = "SELECT * FROM `results` WHERE `id_student`=".$id_student."";
    $sql = "SELECT results.id_result, results.id_student, results.id_discipline, results.mark, "
    ."disciplines.name_discipline, disciplines.id_curriculum,  disciplines.id_term, disciplines.type_att  FROM `results` "
    ."INNER JOIN disciplines ON disciplines.id_discipline=results.id_discipline "
    ."WHERE results.id_student=".$id_student." ORDER BY disciplines.id_term";
    $query = mysqli_query($connection,$sql);
    echo mysqli_error($connection);
    echo <<<EOF
        <thead>
        <tr> <th>   </th> <th>Семестр</th> <th>Предмет</th> <th>Тип аттестации</th> <th>Оценка</th> <th></th></tr>
        </thead>
    EOF;
    while($data = mysqli_fetch_assoc($query)){
        echo mysqli_error($connection);
        echo <<<EOF
            <tbody>
            <tr>
                <td>    </td>
                <td>{$data['id_term']}</td>
                <td>{$data['name_discipline']}</td>
                <td>{$data['type_att']}</td>
                <td>{$data['mark']}</td>
                <td><a href="#" class="res" id="res{$data['id_result']}">Изменить</a></td>
                <td><button class="btn btn-outline-danger res" style="margin: 0 auto" id="{$data['id_result']}" href="#"><span class="material-icons arrow-icon">delete_outline</span></button></td>
            </tr>
        EOF;
        echo '</tbody>';
    }
}

/*
function checkSelected($connection,$id){
    $query = mysqli_query($connection,"SELECT COURSE_ID FROM courses_selected WHERE USER_ID='{$_SESSION['user_id']}' AND COURSE_ID='$id'");
    echo mysqli_error($connection);
    return mysqli_num_rows($query) == 0;
}

function addGroup($connection,$name,$id_cur,$id_dep){
    $query = mysqli_query($connection,"SELECT COURSE_ID FROM courses_selected WHERE USER_ID='{$_SESSION['user_id']}' AND COURSE_ID='$id'");
    echo mysqli_error($connection);
    return mysqli_num_rows($query) == 0;
}*/

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

function updateAvBall($connection,$id_student){
    $av_ball=0;
    $n=0;
    $sql="SELECT * FROM `results` INNER JOIN disciplines ON disciplines.id_discipline=results.id_discipline WHERE results.id_student=".$id_student."";
    echo mysqli_error($connection);
    $query = mysqli_query($connection, $sql);
    echo mysqli_error($connection);
    while($row=mysqli_fetch_assoc($query)){
        if($row['type_att']=="экзамен"){
            $n++;
            $av_ball+=$row['mark'];
        }
    }
    if($n!=0){
        $av_ball/=$n;
    }
    $sql ="UPDATE `students` SET `av_ball`='".$av_ball."' WHERE `id_student`=".$id_student."";
    $res =  mysqli_query($connection, $sql);
    echo mysqli_error($connection);
}

function deleteResultById($connection,$id){
    //update av_ball
    /*$sql = "SELECT mark FROM `results` WHERE id_result=".$id;
    $res=mysqli_fetch_assoc(mysqli_query($connection,$sql));
    echo mysqli_error($connection);
    $mark=$res['mark']; */
    $sql="DELETE FROM results WHERE id_result='{$id}'";
    $query = mysqli_query($connection,$sql);
    echo mysqli_error($connection);
    updateAvBall($connection,get_student_id_by_result($connection,$id));
    //$query = mysqli_query($connection,"DELETE FROM results WHERE id_student='{$id}'");
    return mysqli_error($connection);//mysqli_num_rows($query) != 0;
}

function getUniqueCurrList($connection){
    $query = mysqli_query($connection,"SELECT * FROM curriculum GROUP BY num");
    echo mysqli_error($connection);
    return $query;
}

function getDepsListForCurByNum($connection,$num){
    $sql="SELECT curriculum.id_curriculum, departments.name as name_department, departments.id as id_department FROM `departments`" 
                        ." INNER JOIN curriculum ON curriculum.id_department=departments.id"
                        ." WHERE curriculum.num='".$num."'";
    $query = mysqli_query($connection,$sql);
    echo mysqli_error($connection);
    return $query;
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