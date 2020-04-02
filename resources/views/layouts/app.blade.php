<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LaravelCodeEngine') }} | v.1 </title>

    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

    <!-- Morris -->
    <link href="{{asset('assets/css/plugins/morris/morris-0.4.3.min.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

    <link href="{{asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/plugins/steps/jquery.steps.css')}}" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="{{asset('assets/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <!-- Flot -->
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.tooltip.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.spline.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/jquery.flot.symbol.js')}}"></script>
    <script src="{{asset('assets/js/plugins/flot/curvedLines.js')}}"></script>

    <!-- Peity -->
    <script src="{{asset('assets/js/plugins/peity/jquery.peity.min.js')}}"></script>
    <script src="{{asset('assets/js/demo/peity-demo.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{asset('assets/js/inspinia.js')}}"></script>
    <script src="{{asset('assets/js/plugins/pace/pace.min.js')}}"></script>

    <!-- jQuery UI -->
    <script src="{{asset('assets/js/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

    <!-- Jvectormap -->
    <script src="{{asset('assets/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>

    <!-- Sparkline -->
    <script src="{{asset('assets/js/plugins/sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{asset('assets/js/demo/sparkline-demo.js')}}"></script>

    <!-- ChartJS-->
    <script src="{{asset('assets/js/plugins/chartJs/Chart.min.js')}}"></script>

    <script src="{{asset('assets/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

        <!-- Steps -->
    <script src="{{asset('assets/js/plugins/steps/jquery.steps.min.js')}}"></script>

    <!-- Jquery Validate -->
    <script src="{{asset('assets/js/plugins/validate/jquery.validate.min.js')}}"></script>



    <script src="{{ asset('master/js/dataTables.fixedColumns.min.js') }}"></script>



    <script src="{{asset('assets/js/plugins/ladda/spin.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/ladda/ladda.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/ladda/ladda.jquery.min.js')}}"></script>



    <script src="{{asset('assets/js/plugins/bootstrap-validator/validator.min.js')}}"></script>

    <link href="{{asset('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet"/>

    <script src="{{asset('assets/js/plugins/daterangepicker/daterangepicker.js')}}"></script>


    <link href="{{asset('assets/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <script src="{{asset('assets/js/plugins/iCheck/icheck.min.js')}}"></script>

    <script src="{{asset('assets/js/plugins/sweetalert/sweetalert2.all.min.js')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>



</head>
<body>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <img alt="image" class="rounded-circle" src="{{asset('assets/img/profile_small.jpg')}}"/>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="block m-t-xs font-bold">{{ Auth::user()->name }} <span class="caret"></span></span>
                            <span class="text-muted text-xs block">Art Director <b class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                            <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                            <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="login.html">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        IN+
                    </div>
                </li>
                <li class="active">
                    <a href="<?= url("/home"); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span> <span class="fa arrow"></span></a>
                </li>

                <li>
                    <a href="<?= url("/grid/units"); ?>"><i class="fa fa-diamond"></i> <span class="nav-label">Units</span></a>
                </li>

 		<li>
                    <a href="<?= url("/grid/items"); ?>"><i class="fa fa-diamond"></i> <span class="nav-label">Items</span></a>
                </li>

            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to <i>Laravel Code Engine.</i></span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{asset('assets/img/a7.jpg')}}">
                                </a>
                                <div>
                                    <small class="float-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{asset('assets/img/a4.jpg')}}">
                                </a>
                                <div>
                                    <small class="float-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a class="dropdown-item float-left" href="profile.html">
                                    <img alt="image" class="rounded-circle" src="{{asset('assets/img/profile.jpg')}}">
                                </a>
                                <div>
                                    <small class="float-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html" class="dropdown-item">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="profile.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="float-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="grid_options.html" class="dropdown-item">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="float-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html" class="dropdown-item">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>

                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i>{{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </li>
            </ul>

        </nav>
        </div>


        <!-- MAIN CONTAINER AREA -->
        <div class="wrapper wrapper-content">
            @yield('content')
        </div>


        <div class="footer">
            <div class="float-right">
                <strong>Developed By</strong>Easybd.
            </div>
            <div>
                <strong>Copyright</strong> www.easybd.com. &copy; 2018-<?=date('Y')?>
            </div>
        </div>

        </div>

    </div>










    <script>

    //DATA TABLE
    //  $(document).ready(function(){
    //      $('.dataTables-common').DataTable({
    //          pageLength: 25,
    //          responsive: true,
    //          dom: '<"html5buttons"B>lTfgitp',
    //          buttons: [
    //              { extend: 'copy'},
    //              {extend: 'csv'},
    //              {extend: 'excel', title: 'ExampleFile'},
    //              {extend: 'pdf', title: 'ExampleFile'},

    //              {extend: 'print',
    //               customize: function (win){
    //                      $(win.document.body).addClass('white-bg');
    //                      $(win.document.body).css('font-size', '10px');

    //                      $(win.document.body).find('table')
    //                              .addClass('compact')
    //                              .css('font-size', 'inherit');
    //              }
    //              }
    //          ]

    //      });

    //  });



//CUSTOMER FORM
//      $(document).ready(function(){
//         $("#wizard").steps();
//         $("#form").steps({
//             bodyTag: "fieldset",
//             onStepChanging: function (event, currentIndex, newIndex)
//             {
//                 // Always allow going backward even if the current step contains invalid fields!
//                 if (currentIndex > newIndex)
//                 {
//                     return true;
//                 }

//                 // Forbid suppressing "Warning" step if the user is to young
//                 if (newIndex === 3 && Number($("#age").val()) < 18)
//                 {
//                     return false;
//                 }

//                 var form = $(this);

//                 // Clean up if user went backward before
//                 if (currentIndex < newIndex)
//                 {
//                     // To remove error styles
//                     $(".body:eq(" + newIndex + ") label.error", form).remove();
//                     $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
//                 }

//                 // Disable validation on fields that are disabled or hidden.
//                 form.validate().settings.ignore = ":disabled,:hidden";

//                 // Start validation; Prevent going forward if false
//                 return form.valid();
//             },
//             onStepChanged: function (event, currentIndex, priorIndex)
//             {
//                 // Suppress (skip) "Warning" step if the user is old enough.
//                 if (currentIndex === 2 && Number($("#age").val()) >= 18)
//                 {
//                     $(this).steps("next");
//                 }

//                 // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
//                 if (currentIndex === 2 && priorIndex === 3)
//                 {
//                     $(this).steps("previous");
//                 }
//             },
//             onFinishing: function (event, currentIndex)
//             {
//                 var form = $(this);

//                 // Disable validation on fields that are disabled.
//                 // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
//                 form.validate().settings.ignore = ":disabled";

//                 // Start validation; Prevent form submission if false
//                 return form.valid();
//             },
//             onFinished: function (event, currentIndex)
//             {
//                 var form = $(this);

//                 // Submit form input
//                 form.submit();
//             }
//         }).validate({
//                     errorPlacement: function (error, element)
//                     {
//                         element.before(error);
//                     },
//                     rules: {
//                         confirm: {
//                             equalTo: "#password"
//                         }
//                     }
//                 });
//    });


    </script>

    <script src="{{asset('assets/js/apsisScript.js')}}"></script>
</body>
</html>
