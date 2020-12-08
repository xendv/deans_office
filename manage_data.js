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

function validate(form,url){
    //ajax form validation
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'html',
        data:form.serialize(),
        success: formResult
    });
}

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
    $(document).on("click",".to_groups",function(e) {
        e.preventDefault();
            $('.show-groups-block').slideToggle(300);   
            consoleRequest("request=get_all_groups",$("#group_table"));
            $('html, body').animate({
                scrollTop: $("#data_block_header").offset().top
            }, 500);
            //document.location.href=document.getElementById('#data_block_header').value;
            return false;
    });
    $(document).on("click",".to_departments",function(e) {
        e.preventDefault();
        $('.show-deps-block').slideToggle(300); 
        consoleRequest("request=get_all_departments",$("#departments_table"));
        $('html, body').animate({
            scrollTop: $("#show-deps-block").offset().top
        }, 500);
        return false;
        //consoleRequest("request=get_all_departments");
    });
    $(document).on("click","#add_group_btn",function(e) {
        e.preventDefault();
        validate($("#add_group_form"),"add_group_ver.php");
    });
    $(document).on("click",".gr.btn",function(e) {
        e.preventDefault();
        if (confirm('Удалить группу?')){
            consoleRequest("request=delete_group_by_id&id_group="+this.id,null);
            consoleRequest("request=get_all_groups",$("#group_table"));
        }
    });

    $(document).on("click",".st.btn",function(e) {
        e.preventDefault();
        var id_st=this.id;
        if (confirm('Удалить студента?')){
            dbDataRequest("request=get_student_group_by_id&id_student="+id_st,function (group_id){
                consoleRequest("request=delete_student_by_id&id_student="+id_st,null);
                consoleRequest("request=get_students_by_group&id_group="+group_id,$("#students_table"+group_id));
             });
        }
    });
    //consoleRequest("request=get_all_groups",$("#group_table"));
    //consoleRequest("request=get_all_departments",$("#department_table"));
});

$(document).on("click",".add_group",function(e) {
    e.preventDefault();
    $('.show-add-group-block').slideToggle(300); 
    consoleRequest("request=get_currs_for_options",$("#select_curr"));
    return false;
});
/*
$(document).on("click","#add_group_btn",function(e) {
    e.preventDefault();
    validate($("#add_group_form"),"add_group_ver.php");
});*/


 /*                   
$(document).ready(function(e) {
    consoleRequest("request=get_progs",updateProgs);
    consoleRequest("request=get_selected_courses",updateSelected);
    consoleRequest("request=get_forms_data",fillForms);
    
     $("#filter_reset").click(function(e){
        e.preventDefault();
        $(".select").each( function( index, element ){
            $(this).val("0");
            $(this).niceSelect('update');
        });
        $("#search_box").val("");
        updateFilters();
    });
    
    
    $('body').progressTracker({
	        linking : true,
	        tooltip : "constant",
	        negativeTolerance : 0,
	        positiveTolerance : 0,
	        displayWhenActive : true,
                tracking: "tracker",
                offset: -110
	    });
    //popups
    initPopups();
    
    $(document).on("click",".toogle",function(e) {
            e.preventDefault();
            setPage(this.id);
        });
        
    //pick progs    
    $(document).on("click",".budget",function(e) {
            e.preventDefault();
            consoleRequest("request=set_selected&id="+this.id+"&type=1",setSelected);
        });
    $(document).on("click",".pay",function(e) {
            e.preventDefault();
            consoleRequest("request=set_selected&id="+this.id+"&type=2",setSelected);
        });
    $(document).on("click",".p_remove",function(e) {
            e.preventDefault();
            consoleRequest("request=unselect&id="+this.id,setSelected);
        });
        
    $(document).on("change","#pl_date",function(e) {
        $("#pl_date").attr("value",$("#pl_date").val());
    });
    $("#pl_date").attr("value",$("#pl_date").val());
});

function validate(form,url){
    //ajax form validation
    $.ajax({
        type: 'post',
        url: url,
        dataType: 'html',
        data:form.serialize(),
        success: formResult
    });
}


function setSelected(html){
    if (html === "false"){
        Snackbar.show({
                text: 'Вы уже выбрали 3 направления!',
                actionText:'Скрыть',
                duration:3000,
                pos:"bottom-center"
            });
    }
    consoleRequest("request=get_progs",updateProgs);
    consoleRequest("request=get_selected_courses",updateSelected);
}

function initPopups(){
  $('#passportPopup').modal({
    show: false,
    backdrop: "static"
});

 $('#docPopup').modal({
    show: false,
    backdrop: "static"
});

$("#year_picker").datetimepicker({
    format: 'YYYY', 
    useCurrent: false,
    showTodayButton: true,
    showClear: true,
    toolbarPlacement: 'bottom',
    sideBySide: true,
    locale:"ru",
    icons: {
        time: "fa fa-clock-o",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-clock-o",
        clear: "fa fa-trash-o"
    }
});
}

function updateProgs(html){
    $("#progs_list_all").html(html);
    formPages();
}

function updateSelected(html){
    $("#selected_progs").html(html);
}

$(".filter").on('change', 'select', function() {
    updateFilters();
});

$('#search_box').on('input',function(e){
    updateFilters();
});

function updateFilters() {
    var request = "request=get_progs";
    if (($("#search_box").val().toString().trim()!== ""))
        request += "&filter_search=" + $("#search_box").val().trim();
    if (($("#select_faculty").val().toString()!== "0"))
        request += "&filter_faculty=" + $("#select_faculty").val();
    if ($("#select_type").val().toString()!== "0")
        request += "&filter_type=" + $("#select_type").val();
    if ($("#select_paid").val().toString()!== "0")
        request += "&filter_paid=" + $("#select_paid").val();
    if ($("#select_degree").val().toString()!== "0")
        request += "&filter_degree=" + $("#select_degree").val();
    consoleRequest(request,updateProgs);
}

var container = $("#progs_list_all"); 
var children_count = 0;
var max_page_elements = 10;
var current_page = 0;
var page_count = 1;

function formPages(){
    current_page = 0;
    page_count = 1;
    children_count = container.children('div').length;
    var iterator = 1;
  
    if (children_count > max_page_elements){
        page_count = Math.ceil(children_count/max_page_elements);
    }
    setPage(0);
    setupToogle();
}

function setPage(number){
    number = parseInt(number) - 1;
    if (number < 0) number = 0;
    if (number > page_count-1) number = page_count-1;
    
    if (number === 0){
        
    } else if (number === page_count - 1){
        
    }
    var start = number*max_page_elements;
    var end = (number+1)*max_page_elements;
    for (i = 0; i < children_count; i++) {
        if (i>= start && i<end){
            container.children('div').eq(i).show();
        } else {
             container.children('div').eq(i).hide();
        }
    }
}

function setupToogle(){
    var html = "<li><a href=''> <i class='ti-angle-left'></i></a></li>";
    for (j = 1; j < page_count + 1; j++){
        html+="<li><a id ='" + j + "' class='toogle' href='#'><span>" + j + "</span></a></li>";
    }
    html+="<li><a href=''> <i class='ti-angle-right'></i></a></li>";
    $('#pages_toogle').html(html);
}

*/