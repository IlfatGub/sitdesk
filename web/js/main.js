$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
    $('.modalButton').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });



    $('#modalFio').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value') + "?search=" + $('#app-fio').val().replace(/\s/ig, '-'));
    });
    $('#modalIp').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value') + "?search=" + $('#app-ip').val());
    });
    $('#modalPhone').click(function(){
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value') + "?search=" + $('#app-phone').val());
    });

    $("[data-toggle='tooltip']").tooltip({html:true});
    $("[data-toggle='popover']").popover();

    $('affix').affix({
        offset: {
            top: 100
            , bottom: function () {
                return (this.bottom = $('.footer').outerHeight(true))
            }
        }
    });
    var hash = window.location.hash;
    if(hash){
        // alert(hash);
        $('#sidebar-wrapper').animate({scrollTop:$(hash).position().top - 200});
        return false;
    }

});


function keyUp(event){
    if(event.keyCode == 13){
        event.preventDefault();
    }
}

function keyDown(e) {
    if (e.keyCode == 17)
        ctrl = true;
    else if (e.keyCode == 13 && ctrl){
        document.getElementById("btnComment").click();
    }else{
        ctrl = false;
    }
}
function keyDownRecal(e) {
    if (e.keyCode == 17)
        ctrl = true;
    else if (e.keyCode == 13 && ctrl){
        document.getElementById("btnRecal").click();
    }else{
        ctrl = false;
    }
}
function show(id)
{
    div = document.getElementById(id);
    if(div.style.display == 'block') {
        div.style.display = 'none';
    }
    else {
        div.style.display = 'block';
    }
}


