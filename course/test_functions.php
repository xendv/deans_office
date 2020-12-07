<?php
    
    function form_test_row($sub_type){
        include_once("../functions.php");
        $connection = link_to_db("nnka_db");
        if (!$connection) { 
            echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error(); 
            exit; 
        }  
        //fix charset
        mysqli_set_charset ($connection , 'utf8');
        $query = mysqli_query($connection,"SELECT * FROM SectionHeaders WHERE SUB_TYPE='".$sub_type."'");
        while($section_data = mysqli_fetch_assoc($query)){
            echo <<<EOF
                <div class="regular-page-area content-padding-40">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-content white-bg">
                                    
                                    <h5>{$section_data['SECTION_HEADER']}</h5>
                                    <h6>{$section_data['SECTION_DESCRIPTION']}</h6>
                                    <table class="table">
                                        <tbody>
            EOF;           
                $test_header_query = mysqli_query($connection,"SELECT * FROM TestHeaders WHERE SECTION_ID='".$section_data['ID']."'");
                $result_db=link_to_db("trainer");
                $result =0;
                while($test_header_data = mysqli_fetch_assoc($test_header_query)){
                    $result_query = mysqli_query($result_db,"SELECT `score` FROM test_results WHERE test_id='{$test_header_data['ID']}' AND user_id='{$_SESSION['user_id']}'");
                    if (mysqli_num_rows($result_query)>0){
                        $result = mysqli_fetch_assoc($result_query)['score'];
                    } else {
                        $result = 0;
                    }
                    echo <<<EOF
                                <tr class="ripple test_click" id ="{$test_header_data['ID']}">
                                    <td class="test-item"><h6>{$test_header_data['TEST_NAME']}</h6>
                                    <p>{$test_header_data['TEST_DESCR']}</p></td>
                                    <td class="align-center result-view"><h6>$result%</h6><td>
                                    <td class="align-center"><span class="material-icons arrow-icon">arrow_forward_ios</span>
                        
                                </tr>
                    EOF;        
                }
            echo <<<EOF
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            EOF;
        }
    }
    
    
    
    function form_test_page($id){
        include_once("../functions.php");
        $connection = link_to_db("nnka_db");
        if (!$connection) { 
            echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error(); 
            exit; 
        }  
        //fix charset
        mysqli_set_charset ($connection , 'utf8');
        $query = mysqli_query($connection,"SELECT * FROM TestData WHERE PARENT_ID='".$id."'");
        $index = 1;
        $main_head="";
        while($task_data = mysqli_fetch_assoc($query)){
            //Set header
            if($index == 1){
                //retrieve test data
                $test_data = mysqli_fetch_assoc(mysqli_query($connection,"SELECT * FROM TestHeaders WHERE ID='".$id."'"));
                //get test count in section
                $test_position = mysqli_fetch_assoc(mysqli_query($connection,"SELECT COUNT(ID) AS pos FROM TestHeaders WHERE id <= '{$id}' AND SECTION_ID = '{$test_data['SECTION_ID']}'"));
                if ($test_data['TEST_TYPE'] == "LESSON"){
                    $button_str='<button type="button" id="check_lesson" class="btn btn-outline-danger">Отметить пройденным</button>';
                    $main_head="<h2 style=\"text-align:center\">Лекция {$test_position['pos']}</h2> <h4 style=\"text-align:center\">{$test_data['TEST_NAME']} <br><small>{$test_data['TEST_DESCR']}</small></h3>";
                } else {
                    $button_str='<button type="button" id="check" class="btn btn-outline-danger">Отправить всё и завершить тест</button>';
                    $main_head="<h2 style=\"text-align:center\">Тест {$test_position['pos']}</h2> <h4 style=\"text-align:center\">{$test_data['TEST_NAME']} <br><small>{$test_data['TEST_DESCR']}</small></h3>";
                }
                echo $main_head;
            }
            switch ($task_data['ANSWER_TYPE']){
                case "LESSON":
                    $head_str="<p style=\"text-align: center;\">{$task_data['QUEST_STRING']}</p>";
                break;
                default:
                    $head_str="<h5>Вопрос {$index}.</h5> <h6>{$task_data['QUEST_STRING']}</h6>";
                break;
            }
            echo <<<EOF
            <div class="regular-page-area content-padding-40">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-content white-bg">
                                        $head_str
                                    
            EOF;
            switch ($task_data['ANSWER_TYPE']){
                case "VARIANT":
                    echo <<<EOF
                                    <form class="form " id={$task_data['ID']} onsubmit="return false;">
                                        <br>
                                        <label class="pure-material-radio">
                                            <input type="radio" name="{$task_data['ID']}" value = "0" >
                                            <span>{$task_data['VAR0']}</span>
                                        </label>
                                        <br/>
                                        <label class="pure-material-radio">
                                            <input type="radio" name="{$task_data['ID']}" value = "1" >
                                            <span>{$task_data['VAR1']}</span>
                                        </label>
                                        <br/>
                                        <label class="pure-material-radio">
                                            <input type="radio" name="{$task_data['ID']}" value = "2" >
                                            <span>{$task_data['VAR2']}</span>
                                        </label>
                                        <br/>
                                        <label class="pure-material-radio">
                                            <input type="radio" name="{$task_data['ID']}" value = "3" >
                                            <span>{$task_data['VAR3']}</span>
                                        </label>
                                    </form>
                    EOF;
                    break;
                case "TEXT":
                    echo <<<EOF
                                        <form class="form" id={$task_data['ID']} onsubmit="return false;">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                  <br>
                                                  <input type="text" name="{$task_data['ID']}" class="form-control material-form" id="answer_{$task_data['ID']}" placeholder="Your answer" novalidate>
                                                </div>
                                            </div>
                                        </form>
                    EOF;
                    break;
                case "LESSON":
                    break;
            }
            
                                    
            echo <<<EOF
                                <div class="hint page-content" id="{$task_data['ID']}" style="display:none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            EOF;
            $index++;
        }
        echo <<<EOF
                <div class="container">
                    <div class="row">
                        <div class="col-12 content-padding-20-40 align-center">
                            {$button_str}
                        </div>
                    </div>
                </div>
        EOF;
    }
    
    function form_course_row(){
        include_once("../functions.php");
        $connection = link_to_db("trainer");
        $query = mysqli_query($connection,"SELECT * FROM courses");
        while($course_data = mysqli_fetch_assoc($query)){
            echo <<<EOF
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="single-popular-course mb-100 wow fadeInUp" data-wow-delay="250ms">
                        <img src="../img/course-img/{$course_data['SUB_TYPE']}.jpg" alt="">
                        <!-- Course Content -->
                        <div class="course-content">
                            <h4>{$course_data['COURSE_NAME']} {$course_data['COURSE_SUB_NAME']}</h4>
                            <div class="meta d-flex align-items-center">
                                <a href="#">NNKA Team</a>
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                                <a href="#">Courses</a>
                            </div>
                                <p>{$course_data['DESCRIPTION']}</p>
                        </div>
                        <!-- Seat Rating Fee -->
                        <div class="seat-rating-fee d-flex justify-content-between">
                            <div class="seat-rating h-100 d-flex align-items-center">
                                <div class="seat">
                                    Завершено: 
            EOF;
            echo get_course_result($course_data['SUB_TYPE'], $_SESSION['user_id'])."%";
            echo <<<EOF
                                
                                </div>
                                </div>
                                <div class="course-fee h-100">
                                    <a id="{$course_data['SUB_TYPE']}" href="#" class="theme-bg ripple align-center course_click">далее<!--<span class="material-icons align-center" style="color: white; font-size:15px;">arrow_forward_ios</span>--></a>
                                </div>
                            </div>
                        </div>
                    </div>
            EOF;
        }
    }
    
    function get_course_data($sub_type){
        include_once("../functions.php");
        $connection = link_to_db("trainer");
        $query = mysqli_query($connection,"SELECT * FROM courses WHERE SUB_TYPE='".$sub_type."'");
        return mysqli_fetch_assoc($query);
    }
    
    function get_course_result($sub_type, $user_id){
        //include_once("../functions.php");
        $connection = link_to_db("nnka_db");
        if (!$connection) { 
            echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error(); 
            exit; 
        }  
        //fix charset
        mysqli_set_charset ($connection , 'utf8');
        $query = mysqli_query($connection,"SELECT * FROM SectionHeaders WHERE SUB_TYPE='".$sub_type."'");
        $result_db=link_to_db("trainer");
        $result =0;
        $total_count = 0;
        $num_test_done = 0;
        while($section_data = mysqli_fetch_assoc($query)){
            $res = mysqli_query($connection,"SELECT COUNT(ID) AS num FROM TestHeaders WHERE SECTION_ID = '{$section_data['ID']}'");
            $total_count += mysqli_fetch_assoc($res)['num'];
            $res = mysqli_query($connection,"SELECT ID FROM TestHeaders WHERE SECTION_ID = '{$section_data['ID']}'");
            while ($test_id = mysqli_fetch_assoc($res)) {
                $result_query = mysqli_query($result_db,"SELECT `score` FROM test_results WHERE test_id='{$test_id['ID']}' AND user_id='{$user_id}'");
                if (mysqli_num_rows($result_query)>0){
                    $result = mysqli_fetch_assoc($result_query)['score'];
                } else {
                    $result = 0;
                }
                $num_test_done += ($result/100);    
            }
        }
        return intval($num_test_done/$total_count*100);
    }

    function show_comment($task_id, $usr_answ){
        $connection = link_to_db("nnka_db");
        if (!$connection) { 
            echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error(); 
            exit; 
        }  
        mysqli_set_charset ($connection , 'utf8');
        $query = mysqli_query($connection,"SELECT * FROM TestData WHERE ID='".$task_id."'");
        $corr_answ= mysqli_fetch_assoc($query)['CORRECT_ANSWER'];
        if ($usr_answ==$corr_answ){
            $head_comment= "Correct answer!";
            $color="green";
        }
        else {
            $head_comment= "Wrong answer!";
            $color="red";
        }

        $comment=mysqli_fetch_assoc($query)['ANSWER_COMMENT'];
        echo '<p style="font-color: ';
        echo $color;
        echo '">';
        echo $head_comment;
        echo '</p>';
        echo'<p>';
        echo $comment;
        echo '</p>';
        
    }
?>