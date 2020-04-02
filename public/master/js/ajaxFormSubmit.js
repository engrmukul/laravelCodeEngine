$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });
    $("form").submit(function (e) {
        e.preventDefault();
        var action = $(this).attr('action');
        var url = action
        var formData = $(this).serialize() + "&id=" + $("#pkid").val();
        console.log(formData);
        var data = formData;
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: "json",
            url: url,
            data: data,
            success: function (data) {
                console.log(data.status);
                if (data.status == 'success') {
                    $("form").trigger('reset');
                    swal({
                        title: "Good job!",
                        text: data.message,
                        type: "success"
                    });
                    location.reload();
                } else {
                    swal({
                        title: "Bad Luk!",
                        text: "Check your form",
                        type: "error"
                    });
                }
            }
        });
    });

    $('input').on('ifChecked', function(event){
        $("input[name=pkid]").val($(this).val());
    });
    $("#new").on('click', function (e) {
        $("form").trigger('reset');
    });

    $("#edit").on('click', function (e) {
        e.preventDefault();
        $("form").trigger('reset');
        var id =  $("#pkid").val();
        var url = $("#getRawUrl").val();
        var data = {'id': id};
        console.log(id);
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: "json",
            url: url,
            data: data,
            success: function (data) {
                var obj = jQuery.parseJSON(JSON.stringify(data));
                console.log(obj);
                $.each(obj, function (key, value) {
                    console.log(key);
                    var className = '.' + key;
                    $(className).val(value);
                });
            }
        });
    });

    $("#view").on('click', function (e) {
        e.preventDefault();
        var id =  $("#pkid").val();
        var url = $("#getRawUrl").val();
        var data = {'id': id};
        console.log(id);
        $.ajax({
            type: 'POST',
            cache: false,
            dataType: "json",
            url: url,
            data: data,
            success: function (data) {
                var obj = jQuery.parseJSON(JSON.stringify(data));
                console.log(obj);
                var a = '';
                $.each(obj, function (key, value) {
                    var className = '.' + key;
                    $(className).html(value);
                    a=a+'<div class="form-group  row"><label class="col-sm-2 col-form-label">'+jsUcfirst(key)+'</label>';
                    a=a+'<div class="col-sm-10"><span class="form-control description">'+value+'</span></div></div>';
                });
                $("#result").html(a);
            }
        });
    });
});

function jsUcfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
