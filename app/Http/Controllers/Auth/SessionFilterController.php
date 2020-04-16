<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Input;
use Redirect;
use Auth;

class SessionFilterController extends Controller
{
    public $data = [];

    public function __construct()
    {
        $this->middleware('auth');
    }

    function setUserFilterArray($debug=false)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $sql = DB::table('sys_privilege_items_users')
            ->select('event_ref', 'event_slug', 'event_slug_key', 'permission', 'no_permission', 'sql_where_clause',
                DB::raw('CONCAT(event_ref,"_",event_slug,"_",event_slug_key) as slug'))
            ->where('reference_userid', $user_id)
            ->get();
        $group = [];

        $data = $sql;
        $user_permission = [];

        foreach ($data as $user_p) {
            $user_permission[] = (array)$user_p;
        }
        //debug($group_premission_all);

        $all_permission = [];
        foreach ($user_permission as $val) {
            $group[$val['event_ref']] = $val['event_ref'];
            $all_permission[$val['event_ref']][$val['event_slug']][$val['event_slug_key']] = array(
                'permission' => $val['permission'],
                'no_permission' => $val['no_permission'],
                'sql_where_clause' => $val['sql_where_clause']
            );
        }
        if($debug){
            debug($all_permission, 1);
        }

        return $all_permission;
    }

    function setPermissionFilterArray($debug=true)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $departments_id = $user->departments_id;
        $branchs_id = $user->branchs_id;
        $designation_id = $user->designations_id;
        //$unit_id = $user->hr_emp_units_id;
        //$section_id = $user->hr_emp_sections_id;

        $user_levels_query = DB::table('sys_privilege_levels');
        $user_levels_query->where('users_id',Auth::user()->id);
        $user_levels_result = $user_levels_query->get()->toArray();
        $user_levels = array_column($user_levels_result,'user_levels_id');

        $raw = "SELECT `event_ref`, `event_slug`, `event_slug_key`,
                    GROUP_CONCAT(permission) as permission,
                    GROUP_CONCAT(no_permission) as no_permission,
                    GROUP_CONCAT(sql_where_clause) as sql_where_clause,
                    CONCAT( event_ref, '_', event_slug, '_', event_slug_key ) AS slug
                FROM `sys_privilege_event`
                INNER JOIN `sys_privilege_items` ON `sys_privilege_event`.`event_id` = `sys_privilege_items`.`event_id`
                WHERE 1";
        /* if ($branchs_id > 0) {
           $raw .= " AND (sys_privilege_event.event_key = 'Branch' AND FIND_IN_SET($branchs_id,sys_privilege_items.reference_value))";
       }
       if ($departments_id > 0) {
           $raw .= " OR (sys_privilege_event.event_key = 'Department' AND FIND_IN_SET($departments_id,sys_privilege_items.reference_value))";
       }

       if ($designation_id > 0) {
           $raw .= " OR (sys_privilege_event.event_key = 'Designation' AND FIND_IN_SET($designation_id,sys_privilege_items.reference_value))";
       }
     if ($unit_id > 0) {
           $raw .= " OR (sys_privilege_event.event_key = 'Unit' AND FIND_IN_SET($unit_id,sys_privilege_items.reference_value))";
       }
       if ($section_id > 0) {
           $raw .= " OR (sys_privilege_event.event_key = 'Section' AND FIND_IN_SET($section_id,sys_privilege_items.reference_value))";
       }
        if (!empty($user_levels)) {
            foreach ($user_levels as $level){
                $raw .= " OR (sys_privilege_event.event_key = 'Level' AND FIND_IN_SET($level,sys_privilege_items.reference_value))";
            }

        }*/
        //$raw .= ' GROUP BY slug';
        //debug($raw,1);
        $sql = DB::select(DB::raw($raw));
        $data = $sql;
        $user_permission = [];

        foreach ($data as $user_p) {
            $user_permission[] = (array)$user_p;
        }
        //debug($group_premission_all);

        $all_permission = [];
        foreach ($user_permission as $val) {
            $group[$val['event_ref']] = $val['event_ref'];
            $all_permission[$val['event_ref']][$val['event_slug']][$val['event_slug_key']] = array(
                'permission' => $val['permission'],
                'no_permission' => $val['no_permission'],
                'sql_where_clause' => $val['sql_where_clause']
            );
        }
        if($debug){
            debug($all_permission, 1);
        }

        return $all_permission;
    }
}
