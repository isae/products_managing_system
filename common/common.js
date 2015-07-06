/**
 * Created by root on 7/5/15.
 */
$(document).ready(function(e){
    var input1 = $("#formInput1");
    var input2 = $("#formInput2");
    var input3 = $("#formInput3");
    var input4 = $("#formInput4");
    var input5 = $("#formInput5");
    var modalAlertZone = $("#alertZone");

    function make_modal_warning(msg){
        modalAlertZone.html('<div class="alert alert-danger" role="alert">'+msg+'</div>');
        setTimeout(function(e){
            modalAlertZone.html("");
        },1500)

    }

    input4.change(function(e){
       input5.html("<img src='"+input4.val()+"'/>")
   });

    $("#productAddButton").click(function(e){
        var name = input1.val();
        var description = input2.val();
        var cost = input3.val();
        var link = input4.val();
        if(isNaN(parseInt(cost,10))){
            make_modal_warning("Неверная цена");
            return;
        }
        if(!name){
            make_modal_warning("Название не может быть пустым");
            return;
        }
        $.ajax({
            type: "POST",
            url: "db_controller.php",
            data: {
                name:name,
                description: description,
                cost: cost,
                link: link
            },
            success: function(msg){
                alert( "Data Saved: " + msg );
            }
        });
    })
});