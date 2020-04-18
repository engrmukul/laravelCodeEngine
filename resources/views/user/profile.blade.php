@extends('layouts.app')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-md-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Profile Detail</h5>
                    </div>
                    <div>
                        <div class="ibox-content no-padding border-left-right">
                            <img alt="image" class="img-fluid" src="{{asset('assets/img/profile_big.jpg')}}">
                        </div>
                        <div class="ibox-content profile-content">
                            <h4><strong>Monica Smith</strong></h4>
                            <p><i class="fa fa-map-marker"></i> Riviera State 32/106</p>
                            <div class="user-button">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                                class="fa fa-envelope"></i> Choose Image
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default btn-sm btn-block"><i
                                                class="fa fa-coffee"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>User Basic Information</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                </li>
                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <form method="post" action="{{url('updateProfile')}}" id=''>
                            {{csrf_field()}}
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">USERNAME</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" placeholder="Please Enter User Name"
                                           class="form-control username" value="{{$pageData['record']['username']}}" required>
                                    <input type="hidden" name="pkid" value="{{$pageData['record']['id']}}" id="pkid">
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">FIRST NAME</label>
                                <div class="col-sm-9">
                                    <input type="text" name="first_name" placeholder="Please Enter First Name"
                                           class="form-control " value="{{explode(" ",$pageData['record']['name'])[0]}}" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">LAST NAME</label>
                                <div class="col-sm-9">
                                    <input type="text" name="last_name" placeholder="Please Enter Last Name"
                                           class="form-control" value="{{explode(" ",$pageData['record']['name'])[0]}}" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label ">RELIGION</label>
                                <div class="col-sm-9">
                                    <select class="form-control m-b religion" name="religion" required>
                                        <option value="">select</option>
                                        <option value="Muslim" {{ $pageData['record']['religion'] == "Muslim" ? "selected" : "" }}>Muslim</option>
                                        <option value="Hindu" {{ $pageData['record']['religion'] == "Hindu" ? 'selected' : '' }}>Hindu</option>
                                        <option value="Christian" {{ $pageData['record']['religion'] == "Christian" ? 'selected' : '' }}>Christian</option>
                                        <option value="Buddhist" {{ $pageData['record']['religion'] == "Buddhist" ? 'selected' : '' }}>Buddhist</option>
                                    </select>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">GENDER</label>
                                <div class="col-sm-9">
                                    <select class="form-control m-b gender" name="gender" required>
                                        <option value="">select</option>
                                        <option value="Male" {{ $pageData['record']['gender'] == "Male" ? 'selected' : '' }}>MALE</option>
                                        <option value="Female" {{ $pageData['record']['gender'] == "Female" ? 'selected' : '' }}>FEMALE</option>
                                    </select>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ADDRESS</label>
                                <div class="col-sm-9">
                                    <input type="text" name="address" class="form-control address" value="{{$pageData['record']['address']}}" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">CONTACT NUMBER</label>
                                <div class="col-sm-9">
                                    <input type="number" name="mobile" class="form-control mobile" value="{{$pageData['record']['mobile']}}" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 col-sm-offset-2">
                                    <button class="btn btn-primary btn-sm" type="submit">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>Change Password</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#" class="dropdown-item">Config option 1</a>
                                </li>
                                <li><a href="#" class="dropdown-item">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <form method="post" action="{{url('changepassword')}}" id=''>
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">OLD PASSWORD</label>
                                <div class="col-sm-9">
                                    <input type="password" name="ocurrent-password"
                                           placeholder="Please Enter old password" class="form-control " required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">PASSWORD</label>
                                <div class="col-sm-9">
                                    <input type="password" name="new-password" placeholder="Please Enter password"
                                           class="form-control" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">CONFIRM PASSWORD</label>
                                <div class="col-sm-9">
                                    <input type="password" name="confirm_password"
                                           placeholder="Please Enter Confirm password" class="form-control" required>
                                    <div class="help-block with-errors has-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 col-sm-offset-2">
                                    <button class="btn btn-primary btn-sm" type="submit">Change Password</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });

            $("#profile").submit(function (e) {
                e.preventDefault();
                var action = $(this).attr('action');
                var url = action
                var formData = $('form').serialize() + "&id=" + $("#pkid").val();
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
                            swalSuccess('Good job!');
                            /*swal({
                                title: "Good job!",
                                text: data.message,
                                type: "success"
                            });*/
                            location.reload();
                        } else {
                            swalError( "Check your form");
                            /*swal({
                                title: "Bad Luk!",
                                text: "Check your form",
                                type: "error"
                            });*/
                        }
                    }
                });
            });
           /* $("#changePassword").submit(function (e) {
                e.preventDefault();
                var action = $(this).attr('action');
                var url = action
                var formData = $('form').serialize() + "&id=" + $("#pkid").val();
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
            });*/
        });
    </script>
@endsection
