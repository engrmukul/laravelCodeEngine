<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\SysModule;
use Validator;
use URL;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;
use DB;
class MenuManagement extends Controller {
    public $module_id;
    public $userId;
    public $allParentId = array();
    public function __construct(){

    }
    public function menuList(){
        $module_id = Session::get('SELECTED_MODULE') > 0 ? Session::get('SELECTED_MODULE') : Session::get('DEFAULT_MODULE_ID');
        $menus = explode(',', Session::get('MENUS'));
        $menuquery = DB::table('sys_menus');
        $menuquery->select(
            'sys_menus.sys_menus_id',
            'sys_menus.sys_menus_name',
            'sys_menus.menus_description',
            'sys_menus.menus_type',
            'sys_menus.parent_sys_menus_id',
            'sys_menus.sys_modules_id',
            'sys_menus.icon_class',
            'sys_menus.menu_url',
            'sys_menus.sort_number'
        );
        $menuquery->where('sys_menus.sys_modules_id', $module_id);
        $menuquery->whereIn('sys_menus.sys_menus_id', $menus);
        $menuquery->where('sys_menus.status', 'Active');
        $menuquery->orderBy('sys_menus.sort_number', 'ASC');
        $menuList = $menuquery->get();

        $tree = self::buildTree($menuList);
        $menuHtml = self::makeMenuTree($tree);
        return $menuHtml;
    }

    function buildTree($elements, $parentId = 0){
        $branch = array();
        foreach ($elements as $element) {
            if ($element->parent_sys_menus_id == $parentId) {
                $children = $this->buildTree($elements, $element->sys_menus_id);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }

    public function getAllParentId($id){
        $menusId = Menu::where('sys_menus_id', '=', $id)->pluck('parent_sys_menus_id');
        foreach ($menusId as $key => $val) {
            $this->allParentId[$id] = $val;
            if ($val != 0) {
                $this->getAllParentId($val);
            } else {

            }
        }
        return $this->allParentId;
    }

    public function makeMenuTree($tree = [], $level = 2){
        $html = '';
        $level2 = '';
        $level3 = '';
        $menusId = $this->getAllParentId(session('MENU_ID'));
        foreach ($tree as $key => $val) {
            $active = '';
            if (isset($val->children)) {
                if (in_array($val->sys_menus_id, $menusId)) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                $html .= '<li class="' . $active . '">';
                $html .= '<a href="#">';
                $html .= '<i class="' . $val->icon_class . '"></i> <span class="nav-label">' . $val->sys_menus_name . '</span>';
                $html .= '<span class="fa arrow"></span>';
                $html .= '</a>';
                if($level == 2){
                    $level2 = 'nav-second-level';
                }elseif($level == 3){
                    $level3 = 'nav-second-level';
                }else{

                }
                $html .= '<ul class="nav '.$level2.' '.$level3 . ' collapse">';
                if(!empty($val->children)){
                    $html .= $this->makeMenuTree($val->children, 3);
                }

                $html .= '</ul>';
                $html .= '</li>';
                $level++;
            } else {
                if ($val->sys_menus_id == session('MENU_ID')) {
                    $active = 'active';
                }
                $html .= '<li class="' . $active . '">';
                $html .= '<a href="' . URL::to($val->menu_url) . '">';
                $html .= '<i class="' . $val->icon_class . '"></i> <span class="nav-label">' . $val->sys_menus_name . '</span>';
                $html .= '<span class="pull-right-container"></span>';
                $html .= '</a></li>';
            }
        }
        return $html;
    }

    /*=======================================MENU MANAGEMENT====================================*/

    public function menu_list($menu_id = ''){
        if (!empty($menu_id)) {
            $data['menu_data'] = $this->get_all_menu($menu_id);
        }
        $data['modules'] = DB::select(DB::raw("SELECT sys_modules_id FROM `sys_modules`"));
        foreach ($data['modules'] as $module) {
            $data['menu_list'][$module->sys_modules_id] = $this->getParents($module->sys_modules_id);
        }
        $data['current_module'] = 1;//$this->modules_id;
        $data['combo_slug'] = 'modules';
        $data['combo_array'] = array(
            'selected_value' => '',
            'name' => 'modules_id',
            'attributes' => array('class' => 'form-control modules_id', 'id' => 'module_sort', 'required' => 'required'),
            'multiple' => 0);
//        return view('menus.menu_list', compact('data','pageData'));
        //return view('menus.tree_view', $data);
        return view('menus.menu_list', $data);
    }

    public function get_all_menu($menu_id = NULL, $parent_id = null, $module_id = ''){
        $condition = '';
        if ($parent_id != NULL)
            $condition .= " AND sys_menus.parent_sys_menus_id = $parent_id";
        if ($menu_id != NULL)
            $condition .= " AND sys_menus.sys_menus_id = $menu_id";
        if ($module_id != NULL)
            $condition .= " AND sys_menus.sys_modules_id = $module_id";
        $query = DB::select(DB::raw("SELECT sys_menus.*
            FROM sys_menus
            INNER JOIN sys_modules ON sys_modules.id = sys_menus.sys_modules_id
            WHERE 1 $condition
            ORDER BY sys_menus.sort_number ASC"));
        if (!empty($menu_id)) {
            return count($query);
        } else {
            return $query;
        }
    }

    public function getParents($module_id = ''){
        $menu_lists = $this->get_all_menu(null, '0', $module_id);
        $menu_html = '<ul>';
        foreach ($menu_lists as $key => $menu_list) {
            $child = $this->getChildren($menu_list->sys_menus_id, $module_id);
            $menu_html .= '<li data-jstree=\'"type":"html"}\' data-id="' . $menu_list->id . '">';
            $menu_html .= '<span class="fa ' . $menu_list->icon_class . '"></span>&nbsp;&nbsp;' . $menu_list->name;
            $menu_html .= $child;
            $menu_html .= '</li>';
        }
        $menu_html .= '</ul>';
        return $menu_html;
    }

    public function getChildren($parent_id = '', $module_id = ''){
        $menu_lists = $this->get_all_menu(null, $parent_id, $module_id);
        if (!empty($menu_lists)) {
            $menu_html = '<ul class="">';
            foreach ($menu_lists as $key => $menu_list) {
                $menu_html .= '<li data-jstree=\'"type":"html"}\' data-id="' . $menu_list->sys_menus_id . '">';
                $menu_html .= '<span class="fa ' . $menu_list->icon_class . '"></span>&nbsp;&nbsp;' . $menu_list->sys_menus_name;
                $menu_html .= $this->getChildren($menu_list->sys_menus_id, $module_id);
                $menu_html .= '</li>';
            }
            $menu_html .= '</ul>';
            return $menu_html;
        } else {
            return null;
        }
    }

    public function menu_entry(Request $request){
        $rules = [
            'name' => 'required|unique:sys_menus,sys_menus_name,"' . $request->id . '"',
            'modules_id' => 'required',
            'menu_url' => 'required'
        ];
        $validated = Validator::make($request->all(), $rules);
        if ($validated->fails()) {
            return response()->json(['status' => 'error', 'message' => $validated->errors()]);
        }
        try {
            if ($request->id > 0) {
                $menu = Menu::findOrFail($request->id);
                $menu->save($request->all());
            } else {
                $menu = new Menu();
                $menu->name = $request->name;
                $menu->menus_description = $request->menus_description;
                $menu->menus_type = 'Main';
                $menu->parent_sys_menus_id = 0;
                $menu->sys_modules_id = $request->modules_id;
                $menu->icon_class = $request->icon_class;
                $menu->menu_url = $request->menu_url;
                $menu->sort_number = 0;
                $menu->created_by = Auth::Id();
                $menu->save();
                $menuId = DB::getPdo()->lastInsertId();
            }
            return response()->json(['status' => 'success', 'message' => 'Operation Success']);
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    public function saveMenuOrder(Request $request){
        $menus = $request->data;
        if (!empty($menus)) {
            $menu_arr = [];
            foreach (json_decode($menus) as $key => $menu) {
                $menu_arr = [
                    'sort_number' => $key + 1,
                    'parent_sys_menus_id' => 0,
                    'menus_type' => 'Main'
                ];
                Menu::where('id', $menu->id)->update($menu_arr);
                if (isset($menu->children) && !empty($menu->children)) {
                    $this->menuUpdate($menu->children, $menu->sys_menus_id);
                }
            }
        }
        echo 'saved';
    }

    public function menuUpdate($child_menu_arr = [], $parent_id){
        foreach ($child_menu_arr as $key => $menu) {
            $menu_arr = [
                'sort_number' => $key + 1,
                'parent_sys_menus_id' => $parent_id,
                'menus_type' => 'Sub'
            ];
            Menu::where('id', $menu->id)->update($menu_arr);
            if (isset($menu->children) && !empty($menu->children)) {
                $this->menuUpdate($menu->children, $menu->id);
            }
        }
    }

    public function getMenuRaw(Request $request){
        return response()->json(Menu::find($request->id));
    }

    public function menuDelete(Request $request){
        if (!empty($request->id)) {
            DB::table('sys_privilege_menus')->where('menus_id', '=', $request->id)->delete();
            DB::table('sys_menus')->where('sys_menus_id', '=', $request->id)->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Menu successfully Deleted']);
    }

}
