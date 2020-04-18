<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use Auth;
use Session;
use DB;
use Config;

class ModuleController extends Controller {
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('menu_permission');
    }

    public static function getModuleList(){
        $modules = session('USER_MODULES');
        $moduleList = DB::table('sys_modules')->whereIn('sys_modules_id', $modules)->get();
        return $moduleList;
    }

    public function moduleChanger(Request $request, $id,$is_have_default_id = NULL){
        $moduleid = $id;
        $module_lang = self::getModuleLang($moduleid);
        $request->session()->forget('SELECTED_MODULE');
        $request->session()->forget('MODULE_LANG');
        $request->session()->put('SELECTED_MODULE', $moduleid);
        $request->session()->put('MODULE_LANG', $module_lang);

        $selected_module = session('SELECTED_MODULE');
        if($is_have_default_id == "No" && $selected_module == ""){
            $request->session()->put('IS_FIRST_TIME_CHECK_DEFAULT_MODULE', "YES");
            $data['selected_module'] = $id;
            $db_widget = session('DB_WIDGET');
            $data['my_dashboard'] = 0;
            $widgets = [];
            if(isset($db_widget[$data['selected_module']]) && !empty($db_widget[$data['selected_module']])){
                $widgets = $db_widget[$data['selected_module']];
            }
            if(isset($widgets['USERS']) && !empty($widgets['USERS'])){
                $data['my_dashboard'] = 1;
            }else{
                $data['my_dashboard'] = 0;
            }
            return redirect('home-customized');
        }else{
            $request->session()->forget('IS_FIRST_TIME_CHECK_DEFAULT_MODULE');
            $request->session()->put('IS_FIRST_TIME_CHECK_DEFAULT_MODULE', "No");
            return redirect('home');
        }
    }

    public function getModule(){
        $id = Session::get('SELECTED_MODULE') > 0 ? Session::get('SELECTED_MODULE') : Session::get('DEFAULT_MODULE_ID');
        $defaultModule = DB::table('sys_modules')->where('sys_modules_id','=', $id)->value('sys_modules_name');
        if($defaultModule)
            return $defaultModule;
        else
            return 'Not Assign';
    }

    public function moduleList(){
        $userId = Auth::id();
        $moduleList = Module::where('status', 'Active')
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('modules_id')
                    ->from('sys_privilege_modules')
                    ->where('users_id', $userId)
                    ->orWhereIn('user_levels_id', function ($query) use ($userId) {
                        $query->select('user_levels_id')
                            ->from('sys_privilege_levels')
                            ->where('users_id', $userId);
                    });
            })->get();
        return view('module_landing_page', compact('moduleList'));
    }

    public function getModuleLang($module_id){
        $module_lang = DB::table('sys_modules')->where('sys_modules_id',$module_id)->get()->first()->sys_modules_name;
        return $module_lang;
    }
}
