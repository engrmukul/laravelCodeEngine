<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-logo">
        <a href="{{URL::to('/')}}">
            <img alt="image" class="img-responsive" width="80px"  src="{{asset('assets/img/lce.png')}}"/>
        </a>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            @inject('menu', 'App\Http\Controllers\MenuManagement')
            {!! $menu->menuList() !!}
        </ul>
    </div>
</nav>
