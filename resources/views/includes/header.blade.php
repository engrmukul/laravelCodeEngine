@inject('moduleController', 'App\Http\Controllers\ModuleController')
<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown" >
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="margin-top: 8px !important;">
                    <i class="fa fa-bitbucket"></i>My Requisition<b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li><a href="{{ URL::to('create-stock-requisition') }}"><i class="fa fa-plus-circle"></i> New Requisition </a></li>
                    <li><a href="{{URL::to('stock-requisitions')}}"><i class="fa fa-bars"></i> Requisition List</a> </li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="{{URL::to('get-delegation-list')}}" style="margin-top: 8px !important;">
                    <i class="fa fa-users"></i>
                    Delegation List
                    <span class="label label-danger unread_counter">{{getUnreadNotification()}}</span>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    <li><a href="{{ URL::to('get-approval-modules') }}">
                            <i class="fa fa-th-large"></i> Delegation Modules
                            <span style="padding: 7px;" class="label label-danger pull-right unread_counter">{{getUnreadNotification()}}</span>
                        </a>
                    </li>
                    <li><a href="{{URL::to('get-delegation-list')}}"><i class="fa fa-table"></i> Approved List</a> </li>
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" style="margin-top: 8px !important;">
                    <i class="fa fa-cogs"></i>
                    {!! session::get('MODULE_LANG') !!}<b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-messages">
                    @foreach ($moduleController->getModuleList() as $val)
                        <li>
                            <a href="{{URL::to("/moduleChanger/".$val->sys_modules_id)}}">
                                <i class="{{ $val->modules_icon }}" style="font-size:10px"></i> {{ $val->sys_modules_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    @if(Session::has('USER_NAME'))
                        <div class="feed-element">
                            @if(file_exists(asset('public'.session('USER_IMAGE'))))
                                <img alt="image" class="rounded-circle float-left" style="margin-right: 5px;" src="{{asset('assets'.session('USER_IMAGE'))}}"/>
                            @else
                                <img alt="image" class="rounded-circle float-left" style="margin-right: 5px;" src="{{asset('assets/img/default-user.jpg')}}"/>
                            @endif
                            <div class="media-body">
                                <strong>{{ Session::get('USER_NAME') }}</strong>
                                <br/>
                                <small class="">
                                    @if(Session::has('DESIGNATION_NAME'))
                                        {{ Session::get('DESIGNATION_NAME') }}
                                    @else
                                        User
                                    @endif
                                    <b class="caret"></b>
                                </small>
                            </div>
                        </div>
                    @endif
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{route('get-user-profile')}}">
                            <i class="fa fa-user"></i> My Profile
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li class="text-danger">
                        <a class="" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <b><i class="fa fa-sign-out"></i> {{ __('Logout') }}</b>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
