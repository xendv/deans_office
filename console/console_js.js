 // make AJAX POST requests to server
 function consoleRequest(request,object = null) {
    //ajax request 
    $.ajax({
        type: 'post',
        url: 'console/console_manager.php',
        dataType: 'html',
        data:request,
        success: function (html) {
            if(object !== null) object.html(html);
        }
    });
};



$(document).ready(function(e) {
    reload_tables();
    $(document).on ("click", ".btn-outline-danger", function (e) {
        e.preventDefault();
        if (confirm("Удалить пользователя "+this.id+"?")){
            consoleRequest("request=remove&user="+this.id);
            reload_tables();
        }
    });
    $(document).on ("click", ".drop", function (e) {
        e.preventDefault();
        if ($(this).text() === "Показать"){
            consoleRequest("request=get_user_result&user_id="+this.id,$("#"+this.id+".dropdown"));
            $(this).text("Скрыть");
        } else {
            $(this).text("Показать");
        }
        //$("#"+this.id+".dropdown").slideToggle(200);
        $("#"+this.id+".dropdown").fadeToggle(200);
    });
});

function reload_tables(){
    consoleRequest("request=get_all_users",$("#user_table"));
}