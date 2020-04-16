<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LARAVEL CODE ENGINE</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>
<body class="gray-bg">
<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox-content">
                <h2><strong>LARAVEL CODE ENGINE</strong></h2>
                <span>GROW WITH EASY</span>
                <form class="m-t" role="form" method="POST" action="{{ route('login') }}" id="loginFrm">
                    {{ csrf_field() }}
                    <div class="input-group m-b">
                        <div class="input-group-prepend">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        <input id="email" type="email" placeholder="username or email"
                               class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                               value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="input-group m-b">
                        <div class="input-group-prepend">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        </div>
                        <input id="password" type="password" placeholder="password"
                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                               required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                        @endif
                    </div>
                    @if(session()->get('multi_log_message'))
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span class="error_message_multi">{{ session()->get('multi_log_message') }}</span>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success multi_log_action" action_type="no"
                                        data-dismiss="modal">No
                                </button>
                                <a class="btn btn-primary btn-ok multi_log_action" action_type="yes">Yes</a>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <a class="forget-pass"
                               href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                        <div class="remember_me col-sm-6 text-right">
                            <input class="custom-check" type="checkbox" tabindex="3" value="remember-me" id="remember_me">
                            <label for="remember_me">Remember Me</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

</html>


<script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script src="{{asset('js/bootstrap.js')}}"></script>
<script>
    $(document).on('click', '.multi_log_action', function (e) {
        e.preventDefault();
        var action_type = $(this).attr('action_type');
        if (action_type == 'no') {
            $('.alert').hide();
        } else {
            $.ajax({
                type: "POST",
                url: '{{URL::to("multi-login-action")}}',
                data: $('#loginFrm').serialize(),
                success: function (response) {
                    if (response == true) {
                        $('#loginFrm').submit();
                    } else {
                        $('.alert').show();
                        $('.error_message_multi').text('You are not this person.');
                    }
                }
            });
        }
    });

    //remember me section
    $(function () {
        if (localStorage.chkbx && localStorage.chkbx != '') {
            $('#remember_me').attr('checked', 'checked');
            $('#email').val(localStorage.usrname);
        } else {
            $('#remember_me').removeAttr('checked');
            $('#loginEmail').val('');
        }

        $('#remember_me').click(function () {
            if ($('#remember_me').is(':checked')) {
                localStorage.usrname = $('#email').val();
                localStorage.chkbx = $('#remember_me').val();
            } else {
                localStorage.usrname = '';
                localStorage.chkbx = '';
            }
        });
    });
</script>
</body>
</html>


