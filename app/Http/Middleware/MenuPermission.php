<?php
namespace App\Http\Middleware;
use Closure;
use App\Models\Menu;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;
use Session;

class MenuPermission extends Middleware {
    protected $except = [
        //
    ];
    public function handle($request, Closure $next, $guard = null){
//        debug(Session::all());
        $uri_segments = request()->segments();
        $menu_id = 0;
        $session_menus = explode(',', Session::get('MENUS'));
        for($i = 3; $i > 0; $i--){
            $segment_arr = array_slice($uri_segments, 0, $i);
            $all_segment = implode('/', $segment_arr);
            $menu = Menu::where('menu_url', $all_segment)->first();
            if(!empty($menu)){
                $menu_id = $menu->id;
                break;
            }
        }
//        echo 'aaaaaaa';
//        debug($session_menus);
        if($menu_id != 0){
            if(in_array($menu_id, $session_menus)){
                session()->forget('MENU_ID');
                session()->put('MENU_ID', $menu_id);
                return $next($request);
            }else{
                return redirect()->route('no-permission');
            }
        }else{
            return $next($request);
        }
    }
}
