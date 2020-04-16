@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('public/css/plugins/datepicker/datepicker3.css')}}">
    <script src="{{asset('public/js/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <div class="wrapper wrapper-content animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="nav-link active show" data-toggle="tab" href="#tab-1">User Information </a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tab-2">Personal Information</a></li>
                        {{--<li><a class="nav-link" data-toggle="tab" href="#image"><h3>Image</h3></a></li>--}}
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tab-1" class="tab-pane active show">
                            <div class="panel-body">

                                    @if(Session::has('message'))
                                    <div class="alert alert-success alert-dismissible" id="succ_message"  >
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ Session::get('message') }}
                                    </div>
                                    @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        @include('user.reset_password_form')
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                         </div>

                        <div role="tabpanel" id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form class="row" method="post" action="{{url('update-user-profile')}}" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <div class="col-md-3 text-right">
                                                <div id="img_contain">
                                                    <?php
                                                        if($pageData['record']['user_image']) {
                                                     ?>
                                                        <img src="{{asset('public'.$pageData['record']['user_image'])}}" id="select_img" class="select_image img-rounded" name="user_image" alt="Select Image" onclick="_upload()" style="height: 200px;width: 170px;">
                                                     <?php   } else {?>
                                                        <img src="{{asset('public/img/users/Avatar.png')}}" id="select_img" class="select_image img-rounded" alt="Select Image" onclick="_upload()" style="height: 200px;width: 170px;">
                                                     <?php   }

                                                     ?>
                                                </div>
                                                <input type="file" id="file_upload_id" name="inpFile" style="display: none;" >
                                                <button class="btn btn-primary btn-sm mt-2" type="button" onclick="_upload()">Change Image</button>
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text"
                                                               name="name"
                                                               id="name"
                                                               placeholder="Enter Name"
                                                               class="form-control"
                                                               value="{{$pageData['record']['name']}}"
                                                               data-error="Name is Mandatory"
                                                               required>
                                                        <input type="hidden" name="pkid" value="{{$pageData['record']['id']}}" id="pkid">
                                                        <div class="help-block with-errors has-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Email</label>
                                                    <div class="col-sm-8">
                                                        <input type="email"
                                                               name="email"
                                                               id="email"
                                                               placeholder="Enter Email"
                                                               class="form-control"
                                                               value="{{$pageData['record']['email']}}"
                                                               required readonly>
                                                    </div>
                                                    <div class="help-block with-errors has-feedback"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Mobile No.</label>
                                                    <div class="col-sm-8">
                                                        <input type="text"
                                                               name="mobile"
                                                               id="mobile"
                                                               placeholder="Enter Mobile Number"
                                                               class="form-control"
                                                               value="{{$pageData['record']['mobile']}}">
                                                    </div>
                                                    <div class="help-block with-errors has-feedback"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Date of Birth</label>
                                                    <div class="col-sm-8 input-group date">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                        <input type="text"
                                                               name="date_of_birth"
                                                               id="date_of_birth"
                                                               placeholder="Enter Date Of Birth"
                                                               class="form-control"
                                                               value="{{$pageData['record']['date_of_birth']}}">
                                                    </div>
                                                    <div class="help-block with-errors has-feedback"></div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Gender</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b gender"
                                                                name="gender"
                                                                id="gender"
                                                                data-error="Gender is Mandatory"
                                                                required>
                                                            <option value="">Select</option>
                                                            <option value="Male" {{$pageData['record']['gender']=="Male" ? 'selected' : ''}}>Male</option>
                                                            <option value="Female" {{$pageData['record']['gender']=="Female" ? 'selected' : ''}}>Female</option>
                                                        </select>
                                                        <div class="help-block with-errors has-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group  row">
                                                    <label class="col-sm-4 col-form-label ">RELIGION</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control m-b religion" name="religion" required>
                                                            <option value="">select</option>
                                                            <option value="Muslim" {{ $pageData['record']['religion'] == "Muslim" ? "selected" : "" }}>Muslim</option>
                                                            <option value="Hindu" {{ $pageData['record']['religion'] == "Hindu" ? 'selected' : '' }}>Hindu</option>
                                                            <option value="Christian" {{ $pageData['record']['religion'] == "Christian" ? 'selected' : '' }}>Christian</option>
                                                            <option value="Buddhist" {{ $pageData['record']['religion'] == "Buddhist" ? 'selected' : '' }}>Buddhist</option>
{{--                                                            <option value="Others"{{$pageData['record']['religion']=="Others" ? 'selected' : ''}}>others</option>--}}
                                                        </select>
                                                        <div class="help-block with-errors has-feedback"></div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label">Address</label>
                                                    <div class="col-sm-8">
                                                        <input type="textarea"
                                                               class="form-control"
                                                               id="address"
                                                               name="address"
                                                               value="{{$pageData['record']['address']}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-8">
                                                        <button class="btn btn-primary float-right btn-sm" type="submit">Update Profile</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Necessary Jquery Script--}}
    <script type="text/javascript">
        $(document).ready(function(){
            $('#date_of_birth').datepicker({
            format:"yyyy-mm-dd"
            });
        });

        //Image Upload
        function _upload(){
            document.getElementById('file_upload_id').click();
        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#select_img').attr('src', e.target.result);
                    $('#select_img').hide();
                    $('#select_img').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#file_upload_id").change(function() {
            readURL(this);
        });
    </script>
@endsection