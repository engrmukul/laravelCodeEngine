<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
//use Validator;
use Auth;
use URL;
use DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['except' => ['multiLoginAction']]);
        $this->middleware('menu_permission');
    }

    public function index(Request $request){
        $module = 'User';
        $grid_sql = DB::select(DB::raw("select * from sys_master_entry where sys_master_entry_name = '".strtolower($module)."' "));
        foreach ($grid_sql as $sqlKey => $sql) {
            $gridSql = $sql->grid_sql;
            $primaryKeyHide = $sql->primary_key_hide;
            $grid_title = $sql->grid_title;
        };
        $records = DB::select(DB::raw($gridSql));
        if(count($records) < 1){
            $th = array();
        }else{
            foreach ($records[0] as $key => $record) {
                $th[] = $key;
            };
        }
        $pageData = [
            'modal' => 'includes.apsysmodal',
            'title' => 'User Form',
            'moduleName' => strtolower($module),
            'getRawUrl' => URL::to('getUserRaw'),
            'content' => [
                'new' => 'User.new',
                'view' => 'User.view',
                'search' => 'User.search',
                'sampleImport' => 'User.sampleimport',
            ],
            'action' => [],

            'propsData' => [
                'grid_title' => $grid_title,
                'th' => $th,
                'tdata' =>$records,
                'primaryKeyHide' =>$primaryKeyHide,
                'primaryKey' => 'id',
            ]
        ];
        return view('User.list', compact('pageData'));
    }


    public function multiLoginAction(Request $request){

        $post = $request->all();
//        debug($post,1);
        $user = DB::table('sys_users')->where('email', $post['email'])->first();
        if($user && \Hash::check($post['password'], $user->password)){
            DB::table('sessions')->where('user_id',$user->id)->delete();
            echo true;
        }else{
            echo false;
        }

    }

    public function notifyDismiss(Request $request){
        session()->put('PASSWORD_NOTIFY', 0);
    }

    public function getUserProfile()
    {
        $pageData = [
            'title' => 'User Profile',
            'record' => \App\User::find(Auth::id()),
        ];
        return view('user.profile', compact('pageData'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $record = \App\User::findOrFail($request->id);
            $record->save($request->all());
            return response()->json(['status' => 'success', 'message' => 'Profile Successfully updated']);
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

//    Functions added for Managing User Profile 02-12-18
    public function getUserProfiles(){
        $pageData = [
            'title' => 'User Profile',
            'record' => \App\User::find(Auth::id()),
        ];

        $password_conf = app(LoginController::class)->userLevelInfoQuery(Auth::user()->id);

//        dd($pageData);
        return view('user.profiles', compact('pageData','password_conf'));
    }

    //Password Reset
    public function resetUserPassword() {
        $data['password_conf'] = app(LoginController::class)->userLevelInfoQuery(Auth::user()->id);
        return view('user.reset_password',$data);
    }

    public function resetPasswordSubmit(Request $request){
        $post = $request->all();
        //debug($post,1);
        $user = DB::table('sys_users')->where('id', Auth::user()->id)->first();
        if($user && \Hash::check($post['current-password'], $user->password)){
            $updateArray = array(
                'password'=>\Hash::make($post['new-password']),
                'password_changed_date'=>date('Y-m-d h:i:s')
            );
            DB::table('sys_users')->where('id', Auth::user()->id)->update($updateArray);
            session()->put('PASSWORD_NOTIFY', 0);
            session()->put('PASSWORD_EXPIRY', 0);
            echo true;
        }else{
            echo false;
        }
    }


    //Updating User Profile
    public function updateUserProfile(Request $request) {
      if($request->hasFile('inpFile')){
          $file = $request->file('inpFile');
          $image_name = $file->getClientOriginalName();
          $ext = pathinfo($image_name, PATHINFO_EXTENSION);
          $name = $request->pkid.".".$ext;
          $file->move(public_path().'/img/users/',$name);
          $img_location = '/img/users/'.$name;

      } else {
          $img_location = '/img/users/Avatar.png';
      }
      $user = $request->all();
//      dd($user);
       $id = $user['pkid'];
       $name = $user['name'];
       $email = $user['email'];
       $mobile = $user['mobile'];
       $date_of_birth = $user['date_of_birth'];
       $gender = $user['gender'];
       $religion = $user['religion'];
       $address = $user['address'];

       $updateUser = array(
         'name'=>$name,
         'email'=>$email,
         'mobile'=>$mobile,
         'date_of_birth'=>$date_of_birth,
         'gender'=>$gender,
         'religion'=>$religion,
         'address'=>$address,
         'updated_at'=>date('Y-m-d'),
         'updated_by'=>Auth::id()
       );

       if($img_location !='/img/users/Avatar.png') {
           $updateUser['user_image']=$img_location;
       }
//dd($updateUser);
      $succ_chk=  DB::table('sys_users')
           ->where('id',$id)
           ->update($updateUser);
       if($succ_chk) {
           return redirect()->back()->with('message', 'Profile Updated Successfully');
       } else {
           return redirect()->back()->with('message', 'Update Failed!');
       }
    }


/*
 * User List
 * */

    public function List(Request $request){
        $query =  DB::table('sys_users')
            ->select(
                'sys_users.id',
                'sys_users.name',
                'sys_users.user_code',
                'sys_users.username',
                'sys_users.email',
                'sys_users.mobile',
                'sys_users.status'
            )
            ->where('username','!=', null);
        $userlist = $query->where('sys_users.status','Active')->get();
        $data['userlist'] = $userlist;
        return view('user.list', $data);
    }
    public function entryForm($sys_users_id=null){
        $data = [];
        if($sys_users_id){
            $sql = DB::table('sys_users');
            $sql->select('sys_users.*',
                DB::raw('GROUP_CONCAT(sys_privilege_levels.user_levels_id) as user_levels'),
                DB::raw('GROUP_CONCAT(sys_privilege_modules.modules_id) as user_modules')
            );
            $sql->leftJoin('sys_privilege_levels','sys_privilege_levels.users_id','sys_users.id');
            $sql->leftJoin('sys_privilege_modules','sys_privilege_modules.users_id','sys_users.id');
            $sql->where('id',$sys_users_id);
            $sql->groupBy('sys_users.id');
            $sql->get();
            $data['user'] = $sql->first();

        }
        return view('user.user_entry', $data);
    }

    function storeUser(Request $request){
        $user_arr = array(
            'name'=>$request->name,
            'email'=>$request->email,
            'username'=>$request->username,
            'user_code'=>$request->user_code,
            'default_url'=>$request->default_url,
            'default_module_id'=>$request->default_module_id,
            'password'=>Hash::make(123456),
        );
        if($request->sys_users_id){
            $user_arr['updated_by'] = Auth::id();
            $user_arr['updated_at'] = date('Y-m-d h:i:s');
            DB::table('sys_users')->where('id',$request->sys_users_id)->update($user_arr);
            $sys_users_id = $request->sys_users_id;
            DB::table('sys_privilege_levels')->where('users_id',$sys_users_id)->delete();
            DB::table('sys_privilege_modules')->where('users_id',$sys_users_id)->delete();
        }else{
            $user_arr['created_by'] = Auth::id();
            $user_arr['created_at'] = date('Y-m-d h:i:s');
            DB::table('sys_users')->insert($user_arr);
            $sys_users_id = DB::getPdo()->lastInsertId();
        }
        if(!empty($request->user_levels)){
            $user_levels = [];
            foreach ($request->user_levels as $level){
                $user_levels[] = array(
                    'user_levels_id'=>$level,
                    'users_id'=>$sys_users_id
                );
            }
            DB::table('sys_privilege_levels')->insert($user_levels);
        }
        if(!empty($request->user_modules)){
            $user_modules = [];
            foreach ($request->user_modules as $module){
                $user_modules[] = array(
                    'modules_id'=>$module,
                    'users_id'=>$sys_users_id
                );
            }
            DB::table('sys_privilege_modules')->insert($user_modules);
        }
        if($sys_users_id){
            return response()->json([
                'success' => true,
                'message' => 'Save Successfully'
            ]);

        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to Submit!'
            ]);
        }

    }

}
