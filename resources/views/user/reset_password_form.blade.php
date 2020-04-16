<div class="messageWrite"></div>
<form data-toggle="validator" method="post" action="" role="form" id="resetPasswordFrm">
    {{csrf_field()}}
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">Old Password</label>
        <div class="col-sm-8">
            <input type="password"
                   name="current-password"
                   placeholder="Enter Old Password"
                   class="form-control"
                   data-error="Old Password Mandatory"
                   required>
            <div style="color:red" class="help-block with-errors"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">Password</label>
        <div class="col-sm-8">
            <input type="password"
                   name="new-password"
                   placeholder="Enter New Password"
                   class="form-control"
                   id="password"
                   pattern="{{$password_conf->password_regEx}}"
                   data-error="{{$password_conf->password_regEx_error_msg}}"
                   required>
            <div style="color:red" class="help-block with-errors"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 col-form-label">Retype Password</label>
        <div class="col-sm-8">
            <input type="password"
                   name="confirm-password"
                   placeholder="Retype Password"
                   class="form-control"
                   data-match="#password"
                   data-match-error="Password don't match"
                   required>
            <div style="color:red" class="help-block with-errors"></div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-4"></div>
        <div class="col-sm-8">
            {{--<input type="submit" class="btn btn-primary btn-rounded" id="changePasswordButton" value="Change Password">--}}
            <button type="submit" class="btn btn-primary float-right btn-sm" id="changePasswordButton">Change Password</button>
        </div>
    </div>
</form>


<script>
    $(document).on('click','#changePasswordButton',function(e){
        e.preventDefault();
        if($("#resetPasswordFrm")[0].checkValidity()) {
            Ladda.bind(this);
            var l = $(this).ladda();
            $.ajax({
                type: "POST",
                url: '{{URL::to("reset-password-submit")}}',
                data: $('#resetPasswordFrm').serialize(),
                beforeSend: function () {
                    l.ladda( 'start' );
                },
                success: function (response) {
                    if(response == true){
                        var htm = '<div class="alert alert-success alert-dismissible">';
                         htm += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                         htm += '<strong>Success!</strong> Password Changed Successfully.';
                         htm += '</div>';
                        $("#resetPasswordFrm")[0].reset();
                        $('.messageWrite').html(htm);
                    }else{
                        var htm = '<div class="alert alert-danger alert-dismissible">';
                         htm += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                         htm += '<strong>Failed!</strong> Old Password Did Not Match.';
                         htm += '</div>';
                        $('.messageWrite').html(htm);
                    }

                    l.ladda('stop');
                }
            });
        }

    });
</script>