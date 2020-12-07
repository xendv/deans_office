$("html").on("click", ".ripple", function(evt) {
  var btn = $(evt.currentTarget);
  var x = evt.pageX - btn.offset().left;
  var y = evt.pageY - btn.offset().top;
  
  var duration = 400;
  var animationFrame, animationStart;
  
  var animationStep = function(timestamp) {
    if (!animationStart) {
      animationStart = timestamp;
    }
   
    var frame = timestamp - animationStart;
    if (frame < duration) {
      var easing = (frame/duration) * (2 - (frame/duration));
      
      var circle = "circle at " + x + "px " + y + "px";
      var color = "rgba(0, 0, 0, " + (0.3 * (1 - easing)) + ")";
      var stop = 90 * easing + "%";

      btn.css({
        "background-image": "radial-gradient(" + circle + ", " + color + " " + stop + ", transparent " + stop + ")"
      });

      animationFrame = window.requestAnimationFrame(animationStep);
    } else {
      $(btn).css({
        "background-image": "none"
      });
      window.cancelAnimationFrame(animationFrame);
    }
    
  };
  
  animationFrame = window.requestAnimationFrame(animationStep);

});

$("html").on("click", ".test_click", function(e) {
    document.location.href = "test_container.html?id="+this.id;
});

$("html").on("click", ".course_click", function(e) {
    document.location.href = "test_list.html?id="+this.id;
});

$(document).on ("click", "#back", function() {
    window.history.back();
});

//Test submit
$(document).on ("click", "#check", function(e) {
    if (confirm("Вы уверены, что хотите завершить тест?")){
        var data = "";
        $('.form').each(function(i, obj) {
            data += ($(this).serialize()+"&");
        });
        //include test id
        data += "id=" + test_id;
        //ajax request 
        $.ajax({
            type: 'post',
            url: 'test_checker.php',
            dataType: 'html',
            data:data,
                success: function (html) {
                    var result = jQuery.parseJSON(html);
                    if (result.success){
                        $("input").prop('disabled', true);
                        $("#check").text("Выйти");
                        $("#check").attr('id', 'back');
                        alert("Your test result: " + result.result + "%");
                        $(".hint").each(function(i, obj) {
                            $(this).slideToggle(400);
                            if (result[this.id+"_success"]){
                                $(this).html('<p class="label-success"><span class="material-icons align-center">check_circle_outline</span> Правильный ответ</p><p>'+result[this.id]+'</p>');
                            } else {
                                $(this).html('<p class="label-fail"><span class="material-icons align-center">highlight_off</span> Неправильный ответ</p><p>'+result[this.id]+'</p>');
                            }
                        });
                    }
                }
        });
          
      }
});

$(document).on ("click", "#check_lesson", function(e) {
        var data = "id=" + test_id;
        //ajax request 
        $.ajax({
            type: 'post',
            url: 'test_checker.php',
            dataType: 'html',
            data:data,
                success: function (html) {
                    var result = jQuery.parseJSON(html);
                    if (result.success){
                       window.history.back();
                    }
                }
        });
});


