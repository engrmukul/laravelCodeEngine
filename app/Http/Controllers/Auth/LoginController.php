<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Cache\RateLimiter;
use Illuminate\Filesystem\Cache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use DB;


class LoginController extends Controller {
    use AuthenticatesUsers;
    private $data = [];
    protected $decayMinutes = 1;
    protected $redirectTo = '/home';

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request) {
        $this->setUserSession();
        $user = DB::table('sys_users')->where($this->username(), $request->{$this->username()})->first();
        $user_level_info = self::userLevelInfoQuery($user->id);

        $multi_log = DB::table('sessions')->where('user_id', $user->id)->first();
        if(!$user_level_info->multi_login_allow){
            if($multi_log){
                Auth::logout();
                return redirect()->back()->with('multi_log_message', 'You are logged in another device. Do you want to logged out from other device.');
            }
        }
    }

    public function maxAttempts($user_levels_info) {
        if($user_levels_info){
            $this->decayMinutes = $this->attemptTime($user_levels_info);
            if($user_levels_info->max_wrong_login_attemp){
                return $user_levels_info->max_wrong_login_attemp;
            }else{
                return 5;
            }
        }
        return 5;
    }

    public function attemptTime($user_levels_info) {
        if($user_levels_info){
            if($user_levels_info->wrong_login_attemp == 'No Restriction'){
                return null;
            }else if($user_levels_info->wrong_login_attemp == 'Block for a Period'){
                return $user_levels_info->block_period;
            }else{
                return 1;
            }
        }
        return null;
    }

    public function getUserLevelInfo($request){
        $user_id = $this->getUserId($request);
        return $this->userLevelInfoQuery($user_id);
    }

    public function getUserId($request){
        $query = DB::table('sys_users');
        $query->select('id');
        $query->where('email',$request->all()['email']);
        $result = $query->first();
        return $result ? $result->id:0;
    }

    public function userLevelInfoQuery($user_id){
        $query = DB::table('sys_user_levels');
        $query->where('sys_user_levels_id', function($squery) use ($user_id){
            $squery->select('user_levels_id');
            $squery->from('sys_privilege_levels');
            $squery->where('users_id',$user_id);
            $squery->orderBy('user_levels_id','ASC');
            $squery->first();
        });
        return $query->first();
    }

    protected function credentials(\Illuminate\Http\Request $request){
        $credentials = $request->only($this->username(), 'password');
        return Arr::add($credentials, 'status', 'Active');
    }

    protected function sendFailedLoginResponse(Request $request){
        $errors = [$this->username() => trans('auth.failed')];
        $user = DB::table('sys_users')->where($this->username(), $request->{$this->username()})->first();

        if ($user && \Hash::check($request->password, $user->password) && $user->status == 'Locked') {
            $errors = [$this->username() => 'Your account has been locked. Please contact with Admin.'];
        }

        if ($user && \Hash::check($request->password, $user->password) && $user->status == 'Inactive') {
            $errors = [$this->username() => 'This account has not been activated yet. Please contact with Admin.'];
        }

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    protected function hasTooManyLoginAttempts(Request $request){
        DB::table('sys_users')->where('email',$request->all()['email'])->update(array('wrong_attempts_count'=>DB::raw('wrong_attempts_count+1')));
        $user_levels_info = $this->getUserLevelInfo($request);
        $maxLoginAttempts =$this->maxAttempts($user_levels_info);
        $lockoutTime = 1;

        $result = $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $maxLoginAttempts, $lockoutTime
        );

        if($result){
            if($user_levels_info->wrong_login_attemp == 'Blocked'){
                DB::table('sys_users')->where('email',$request->all()['email'])->update(array('status'=>'Locked'));
            }
        }
        return $result;
    }

    protected function sendLockoutResponse(Request $request){
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        $user = DB::table('sys_users')->where($this->username(), $request->{$this->username()})->first();
        if($user->status=='Active'){
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => [\Lang::get('auth.throttle', ['seconds' => $seconds])],
            ])->status(429);
        }else if($user->status=='Locked'){
            $errors = [$this->username() => 'Your account has been locked. Please contact with Admin.'];
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    private function getUserModule(){
        $user_modules_query = DB::table('sys_privilege_modules');
        $user_modules_query->where('users_id', Auth::user()->id);
        $user_modules_result = $user_modules_query->get()->toArray();
        return array_column($user_modules_result,'modules_id');
    }

    private function getUserInfo(){
        $user_info_query = DB::table('sys_users');
        $user_info_query->select('sys_users.*','designations.designations_name');
        $user_info_query->leftJoin('designations','designations.designations_id','=','sys_users.designations_id');
        $user_info_query->where('id', Auth::user()->id);
        return $user_info_query->first();
    }

    private function getUserLevel(){
        $user_levels_query = DB::table('sys_privilege_levels');
        $user_levels_query->where('users_id', Auth::user()->id);
        $user_levels_result = $user_levels_query->get()->toArray();
        return array_column($user_levels_result,'user_levels_id');
    }

    private function passwordManager(){
        $changedate = strtotime(Auth::user()->password_changed_date);
        $now = strtotime(date('Y-m-d h:i:s'));
        $password_changed_day = ceil(abs($now - $changedate) / 86400);
        $user_level_conf = self::userLevelInfoQuery(Auth::user()->id);
        $password_expiry_notify_day = ($user_level_conf->password_expiry_duration - $user_level_conf->password_expiry_notify);
        $this->data['password_notify'] = 0;
        $this->data['password_expiry'] = 0;
        if(($password_changed_day >= $password_expiry_notify_day) && ($password_changed_day <= $user_level_conf->password_expiry_duration)){
            $this->data['password_notify'] = 1;
        }
        if($password_changed_day >$user_level_conf->password_expiry_duration){
            $this->data['password_expiry'] = 1;
        }
        $this->data['user_level_conf'] = $user_level_conf;
        return $this->data;
    }

    private function getUserDashboards($modules){
        $dashboard_widget = DB::table('sys_dashboard_widget')
            ->select('module_id', DB::raw('GROUP_CONCAT(sys_dashboard_widget_id) AS widgets'))
            ->whereIn('module_id',$modules)
            ->groupBy('module_id')
            ->get();
        $dashboard_widget_users = DB::table('sys_dashboard_widget_users')
            ->select('module_id', DB::raw('GROUP_CONCAT(sys_dashboard_widget_id) AS widgets'))
            ->whereIn('module_id', $modules)
            ->where('user_id', Auth::user()->id)
            ->groupBy('module_id')
            ->get();
        foreach ($dashboard_widget as $dw){
            $this->data[$dw->module_id]['DEFAULT'] = $dw->widgets;
        }
        foreach ($dashboard_widget_users as $dwu){
            $this->data[$dwu->module_id]['USERS'] = $dwu->widgets;
        }
        return $this->data;
    }

    protected function setUserSession(){
        $user_modules = self::getUserModule();
       // $dashboard_widget = self::getUserDashboards($user_modules);
        $user_info = self::getUserInfo();
        $user_levels = self::getUserLevel();
        $menus = self::getMenuPrivList($user_levels);
        $password_info = self::passwordManager();
        $filter = new SessionFilterController();

        DB::table('sys_users')->where('id',Auth::user()->id)->update(array('wrong_attempts_count'=>0));
        session([
            'USER_ID' => Auth::user()->id,
            'USER_NAME' => Auth::user()->name,
            'USER_EMAIL' => Auth::user()->email,
            'USER_PASSWORD_EXPIRE_DAYS' => Auth::user()->password_expire_days,
            'USER_MOBILE' => Auth::user()->mobile,
            'USER_LEVELS' => $user_levels,
            'USER_MODULES' => $user_modules,
           // 'DB_WIDGET' => $dashboard_widget,
            'MENUS' => $menus,
            'DEFAULT_MODULE_ID' => Auth::user()->default_module_id,
            'SELECTED_MODULE' => Auth::user()->default_module_id,
            //'LINE_MANAGER_ID' => Auth::user()->line_manager_id,
            'DESIGNATION_ID' => Auth::user()->designations_id,
            'DESIGNATION_NAME' => $user_info->designations_name,
            'DEPARTMENT_ID' => Auth::user()->departments_id,
            'BRANCH_ID' => Auth::user()->branchs_id,
           // 'UNIT_ID' => Auth::user()->hr_emp_units_id,
            //'SECTION_ID' => Auth::user()->hr_emp_sections_id,
            'USER_IMAGE' => Auth::user()->user_image,
            'PASSWORD_NOTIFY'=>$password_info['password_notify'],
            'PASSWORD_EXPIRY'=>$password_info['password_expiry'],
            'SESSION_FILTER' => [],
            'MODULE_LANG' => isset(Auth::user()->default_module_id) ? self::getModuleLang(Auth::user()->default_module_id) : '',
           // 'USER_ACCESS' => $filter->setUserFilterArray(),
           // 'GROUP_ACCESS' => $filter->setPermissionFilterArray(),
            'PASSWORD_EXPIRY_ACTION' => $password_info['user_level_conf']->password_expiry_action
        ]);
    }

    private function getMenuPrivList($user_levels){
        $menu_user_priv = DB::table('sys_privilege_menu_users')->where('user_id', Auth::user()->id)->first();
        $extr_pr_menu = !empty($menu_user_priv) ? $menu_user_priv->access_menu : '';
        $extr_deny_menu = !empty($menu_user_priv) ? $menu_user_priv->exclude_menu : '';

        $user_level_string = implode(',', $user_levels);
        $selection = "(SELECT sys_menus.sys_menus_id";
        $sql = $selection;
        $sql .= " FROM sys_privilege_menus";
        $sql .= " INNER JOIN sys_menus ON sys_privilege_menus.menus_id = sys_menus.sys_menus_id";
        $sql .= " WHERE 1";
        if(!empty($extr_deny_menu)){
            $sql .= " AND sys_privilege_menus.menus_id NOT IN ( {$extr_deny_menu} )";
        }
        $sql .= " AND sys_privilege_menus.user_levels_id IN ( {$user_level_string} )";
        $sql .= " GROUP BY sys_privilege_menus.menus_id)" ;
        if(!empty($extr_pr_menu)){
            $sql .= " UNION ";
            $sql .= $selection;
            $sql .= " FROM sys_menus";
            $sql .= " WHERE 1";
            $sql .= " AND sys_menus.`sys_menus_id` IN ( {$extr_pr_menu} ))";
        }
        $menuList = DB::select(DB::raw($sql));
        $menus_arr = [];
        foreach ($menuList as $menus){
            $menus_arr[] = $menus->sys_menus_id;
        }
        return implode(',' , $menus_arr);
    }

    function getModuleLang($module_id){
        $module_lang = DB::table('sys_modules')->where('sys_modules_id',$module_id)->get()->first()->sys_modules_name;
        return $module_lang;
    }
}
