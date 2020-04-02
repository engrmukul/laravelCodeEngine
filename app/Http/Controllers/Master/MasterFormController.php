<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use DB;
use URL;
use Auth;
use Redirect;
use Illuminate\Http\Request;


class MasterFormController extends Controller {
    public $data = [];
    public function __construct(){
        $this->middleware('auth');
        //$this->middleware('menu_permission');
    }

    public function buildMasterForm($formName = '') {
        $data = [];
        $data['mainform'] = [];
        $data['subform'] = [];
        $mainform = self::getFormInfos($formName);
        $data['mainform']['mode'] = '';
        $data['mainform']['page_info'] = $mainform;
        if (!empty($mainform)) {
            $form_element = self::getFormElements($mainform[0]->sys_master_entry_name);
            if (!empty($form_element)) {
                $data['mainform']['form_elements'] = $form_element;
            }
            if (!empty($mainform[0]->sub_form_ids)) {
                $sub_form_ids = $mainform[0]->sub_form_ids;
                $subforms = self::getFormInfos($formName = NULL, $sub_form_ids);
                foreach ($subforms as $subform) {
                    $data['subform'][$subform->sys_master_entry_name]['mode'] = '';
                    $data['subform'][$subform->sys_master_entry_name]['page_info'][] = $subform;
                    $subform_names_array[] = $subform->sys_master_entry_name;

                }
                $subform_names = implode("','", $subform_names_array);
                $subform_elements = self::getFormElements($subform_names);
                foreach ($subform_elements as $subform_element) {
                    $data['subform'][$subform_element->sys_master_entry_name]['form_elements'][] = $subform_element;
                }
            }
            return view('Master.buildMasterForm', compact('data'));
        } else {
            return view('errors.404');
        }
    }

    public function buildFormForEntry($formName = '', $table_name = '', $primary_key_field = '', $id = ''){
        $this->data['mainform'] = [];
        $this->data['mainform_data'] = [];
        $this->data['mainform']['mode'] = 'entry';
        $mainform = self::getFormInfos($formName);
        $this->data['mainform']['page_info'] = $mainform;

        if(!empty($id)){
            $this->data['mainform']['mode'] = 'edit';
            $this->data['mainform_data_id'] = $id;
            $view_sql = "SELECT * FROM ".$table_name." WHERE ".$primary_key_field." = '".$id."'";
            $this->data['mainform_data'] = DB::select(DB::raw($view_sql));
        }
        if (!empty($mainform)) {
            $form_element = self::getFormElements($formName);
            if (!empty($form_element)) {
                $this->data['mainform']['form_elements'] = $form_element;
            }
        }
        echo view('Master.entry_form', $this->data);
    }

    public function getFormInfos($formName = '', $form_ids = '') {
        //        DB::enableQueryLog();
        $mainform_sql = "SELECT sys_master_entry.sys_master_entry_id,
            sys_master_entry.sys_master_entry_name,
            sys_master_entry.route_name,
            sys_master_entry.master_entry_title,
            sys_master_entry.sub_form_ids,
            sys_master_entry.form_action_mode,
            sys_master_entry.form_save_mode,
            sys_master_entry.form_action,
            sys_master_entry.method,
            sys_master_entry.form_column,
            sys_master_entry.form_id,
            sys_master_entry.form_class,
            sys_master_entry.form_additional_attr,
            sys_master_entry.form_add_more,
            sys_master_entry.form_view_mode
            FROM `sys_master_entry`
            WHERE sys_master_entry.`status` = 'Active'";
        if (!empty($form_ids)) {
            $mainform_sql .= " AND sys_master_entry.sys_master_entry_id IN (" . $form_ids . ")";
        }
        if (!empty($formName)) {
            $mainform_sql .= " AND sys_master_entry.route_name = '" . $formName . "'";
        }
        $mainform_sql = htmlentities($mainform_sql);
        $mainform = DB::select(DB::raw($mainform_sql));
        //        dd(DB::getQueryLog());
        return $mainform;
    }

    public static function getFormElements($formName) {
        //        DB::enableQueryLog();
        $form_element_sql = "SELECT
            sys_master_entry_details.sys_master_entry_details_id,
            sys_master_entry_details.sys_master_entry_name,
            sys_master_entry_details.table_name,
            sys_master_entry_details.field_name,
            sys_master_entry_details.label_name,
            sys_master_entry_details.label_class,
            sys_master_entry_details.placeholder,
            sys_master_entry_details.input_type,
            sys_master_entry_details.input_id,
            sys_master_entry_details.input_class,
            sys_master_entry_details.required,
            sys_master_entry_details.dropdown_slug,
            sys_master_entry_details.dropdown_view,
            sys_master_entry_details.dropdown_options,
            sys_master_entry_details.autocomplete_query
            FROM `sys_master_entry_details`
            WHERE
            sys_master_entry_details.sys_master_entry_name IN ('" . $formName . "')
            AND sys_master_entry_details.`status` = 'Active'
            ORDER BY sys_master_entry_details.sorting ASC";
        $result = DB::select(DB::raw($form_element_sql));
//        dd(DB::getQueryLog());
        return $result;
    }

    public function masterFormDataStore(Request $request) {
        $request_data = $request->all();
        $post_data = $request->except('tableName', 'route_name', 'submit_method',
            'edit_field_key', 'edit_field_value',
            '_token', 'pkId', 'primaryKey',
            'updated_at','created_at','created_by');
        foreach ($post_data as $field_name => $data){
            $form_num = count($post_data[$field_name]);
            break;
        }
        $insert_data = [];
        for($i=0 ; $i < $form_num ; $i++){
            foreach ($post_data as $field_name => $data){
                $insert_data[$i][$field_name] = $data[$i];
                $insert_data[$i]['created_at'] = currentDate();
                $insert_data[$i]['created_by'] = Auth::id();
            }
        }
       // dd($insert_data);
        if(isset($request_data['edit_field_key']) && isset($request_data['edit_field_value'])){
            DB::table($request->tableName)->where($request_data['edit_field_key'], '=', $request_data['edit_field_value'])->update($insert_data[0]);
            $id = $request_data['edit_field_value'];
        }else{
            DB::table($request->tableName)->insert($insert_data);
            $id = DB::getPdo()->lastInsertId();
        }
        if($request->submit_method == 'ajax'){
            if(!empty($id)){
                $return_data['last_insert_data'] = $id;
                $return_data['mode'] = 'success';
            }
            echo json_encode($return_data);
        }else{
            Session()->flash('success', 'Successfully completed.');
            return redirect()->back();
        }
    }

    public function getAutocompleteQuery($mode = 'search', $master_entry_details_id = '', $id = ''){
        if(!empty($master_entry_details_id)){
            $query = DB::table('sys_master_entry_details')->select("autocomplete_query")->where('sys_master_entry_details_id', $master_entry_details_id)->first()->autocomplete_query;
            //Query format must be
            //SELECT products_id AS data_value, products_name AS data_option FROM products WHERE products_name LIKE %SEARCH_KEY%
            if($mode == 'search'){
                $query_unit = $_GET['query'];
                $dropdowndata['query'] = $query_unit;
                $dropdowndata['suggestions'] = [];
                $format_query = str_replace('SEARCH_KEY', $query_unit, $query);
                $results = DB::select(DB::raw($format_query));
                foreach ($results as $key => $result){
                    $dropdowndata['suggestions'][$key]['value'] = $result->data_option; // string
                    $dropdowndata['suggestions'][$key]['data'] = $result->data_value; // any
                }
                return response()->json($dropdowndata);
            }else{
                $query_arr = explode('where', strtolower($query));
                $condition = "having data_value = ".$id."";
                $format_query = $query_arr[0].$condition;
                //DB::enableQueryLog();
                $results = DB::select(DB::raw($format_query))[0];
                //debug(DB::getQueryLog());
                return response()->json($results);
            }
        }else{
            //else option------
        }
    }
}
