function consoleRequest(request,object=null) {
    //ajax request 
    $.ajax({
        type: 'post',
        url: 'base_manager.php',
        dataType: 'html',
        data:request,
            success: function (html) {
                if(object !== null) object.html(html);
            }
    });
};
function dbDataRequest(request,success) {
    //ajax request 
    $.ajax({
        type: 'post',
        url: 'base_manager.php',
        dataType: 'html',
        data:request,
            success: function (html) {
                success(html);
            }
    });
};

$(document).on ("click", ".gr.drop", function (e) {
    e.preventDefault();
    if ($(this).text() === "Показать"){
        //consoleRequest("request=get_students_by_group&id_group="+this.id,$("#students-table"+this.id+".dropdown"));
        consoleRequest("request=get_students_by_group&id_group="+this.id,$("#students_table"+this.id));
        $(this).text("Скрыть");
    } else {
        $(this).text("Показать");
    }
    //$("#"+this.id+".dropdown").slideToggle(200);
    $("#"+this.id+".dropdown").fadeToggle(200);
});

$(document).on ("click", ".st.drop", function (e) {
    e.preventDefault();
    if ($(this).text() === "Показать"){
        consoleRequest("request=get_students_results&id_student="+this.id,$("#results_table"+this.id));
        //consoleRequest("request=get_students_by_group&id_group="+this.id,$("#students_table"+this.id));
        $(this).text("Скрыть");
    } else {
        $(this).text("Показать");
    }
    //$("#"+this.id+".dropdown").slideToggle(200);
    $("#"+this.id+".st.dropdown").fadeToggle(200);
});

function validate(form,url,id_gr="",check=formResult){
    //ajax form validation
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'html',
        data:form.serialize()+"&id_gr="+id_gr,
        success: check
    });
}//

function formResult(html){
    var result = jQuery.parseJSON(html);
        if(result.success){
            alert('Данные сохранены!');
        }else{
            alert('Не все данные введены корректно!');
        }
        $.each(result, function (index, value){
                if (index !== "success"){
                    $("#"+index).text(value); 
                }
            });
}

function updateGroups(html){
    $("#show-data-block").html(html);
    //formPages();
}

function updateGroups(html){
    $("#show-data-block2").html(html);
    //formPages();
}

$(document).ready(function(e) {
    //открыть/закрыть блок с группами
    $(document).on("click",".to_groups",function(e) {
        e.preventDefault();
            $('.show-groups-block').slideToggle(300);   
            consoleRequest("request=get_all_groups",$("#group_table"));
            $('html, body').animate({
                scrollTop: $("#data_block_header").offset().top
            }, 500);
            return false;
    });
    //открыть/закрыть блок с кафедрами 
    $(document).on("click",".to_departments",function(e) {
        e.preventDefault();
        $('.show-deps-block').slideToggle(300); 
        consoleRequest("request=get_all_departments",$("#departments_table"));
        $('html, body').animate({
            scrollTop: $("#show-deps-block").offset().top
        }, 500);
        return false;
    });
    //добавить группу из формы и обновить таблицы
    $(document).on("click","#add_group_btn",function(e) {
        e.preventDefault();
        validate($("#add_group_form"),"add_group_ver.php");
        consoleRequest("request=get_all_groups",$("#group_table"));
    });
    //удалить группу и обновить таблицы
    $(document).on("click",".gr.btn",function(e) {
        e.preventDefault();
        if (confirm('Удалить группу?')){
            consoleRequest("request=delete_group_by_id&id_group="+this.id,null);
            consoleRequest("request=get_all_groups",$("#group_table"));
        }
    });
    //удалить студента и обновить таблицы
    $(document).on("click",".st.btn",function(e) {
        e.preventDefault();
        var id_st=this.id;
        if (confirm('Удалить студента?')){
            dbDataRequest("request=get_student_group_by_id&id_student="+id_st,function (group_id){
                consoleRequest("request=delete_student_by_id&id_student="+id_st,null);
                consoleRequest("request=update_k&id_group="+group_id,$(".td_k"+group_id));
                consoleRequest("request=get_students_by_group&id_group="+group_id,$("#students_table"+group_id));
             });
        }
    });
    //добавить студента из формы
    $(document).on("click",".add_st_btn",function(e) {
        e.preventDefault();
        //alert("1223");
        validate($("#"+this.id+".add_st_form"),"add_st_ver.php",this.id);
        //td_k{$data['id_group']}
        consoleRequest("request=update_k&id_group="+this.id,$(".td_k"+this.id));
        consoleRequest("request=get_students_by_group&id_group="+this.id,$("#students_table"+this.id));
    });
    //удалить оценку, обновить таблицу оценок и средний балл
    $(document).on("click",".res.btn",function(e) {
        e.preventDefault();
        var id_res=this.id;
        if (confirm('Удалить оценку?')){
            dbDataRequest("request=get_student_id_by_result&id_result="+id_res,function (id_student){
                consoleRequest("request=delete_result&id_result="+id_res,null);
                //обновить av_ball
                //вывести список оценок
                consoleRequest("request=update_av_ball&id_student="+id_student,$(".td_av"+id_student));
                consoleRequest("request=get_students_results&id_student="+id_student,$("#results_table"+id_student));
             });
        }
    });
    //добавить оценку из формы, обновить таблицу оценок и средний балл
    $(document).on("click",".add_res_btn",function(e) {
        e.preventDefault();
        //alert("1223");
        var id_student=this.id;
        validate($("#"+this.id+".add_res_form"),"add_res_ver.php",this.id, function (html){
            formResult(html);
            //alert($("#existence_check_error"+id_student).html());
            if ( $("#existence_check_error"+id_student).html()=="* У студента уже есть оценка") {
                if(confirm('Изменить существующую оценку?')){
                    consoleRequest("request=update_result&id_student="+id_student+"&id_discipline="+$("#"+id_student+".select_discipline.to_change").val()+"&mark="+$("#"+id_student+".select_mark.to_change").val()); 
                }   
            }
            consoleRequest("request=update_av_ball&id_student="+id_student,$(".td_av"+id_student));
            consoleRequest("request=get_students_results&id_student="+id_student,$("#results_table"+id_student));
        }); 
    });

    //consoleRequest("request=get_all_groups",$("#group_table"));
    //consoleRequest("request=get_all_departments",$("#department_table"));
});

//показать форму добавления группы
$(document).on("click",".add_group",function(e) {
    e.preventDefault();
    $('.show-add-group-block').slideToggle(300); 
    consoleRequest("request=get_currs_for_options",$("#select_curr"));
    $("#select_curr").change(function () {
        consoleRequest("request=get_deps_for_options&num="+this.value,$("#select_depart"));
    });
    return false;
});
//показать форму добавления студента
$(document).on("click",".add_st",function(e) {
    e.preventDefault();
    $('.show-add-st-block'+this.id).slideToggle(300); 
    //consoleRequest("request=get_currs_for_options",$("#select_curr"));
    //$("#select_curr").change(function () {
    //    consoleRequest("request=get_deps_for_options&num="+this.value,$("#select_depart"));
    //});
    return false;
});

$(document).on("click",".add_res",function(e) {
    e.preventDefault();
    var id_student=this.id;
    dbDataRequest("request=get_student_group_by_id&id_student="+id_student,function(id_group){
        $('.show-add-res-block'+id_student).slideToggle(300); 
        //consoleRequest("request=get_currs_for_options",$("#select_curr"));
        $("#"+id_student+".select_term").change(function () {
            consoleRequest("request=get_dis_for_term&term="+this.value+"&id_group="+id_group,$("#"+id_student+".select_discipline"));
        });
    });
    return false;
});

 