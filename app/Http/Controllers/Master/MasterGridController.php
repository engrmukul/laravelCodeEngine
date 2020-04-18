<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use App\Models\Master\sysMasterGrid;
use DB;
use URL;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use View;

class MasterGridController extends Controller {
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('menu_permission');
    }
    public function getGrid($gridName = ''){
        $data = self::gridPageInfo($gridName);
        return view('Master.master_grid_view', $data);
    }

    public function getGridForInternalUse($gridName, $enable_search){
        $data = self::gridPageInfo($gridName);
        $data['page_data']['enable_search'] = $enable_search;
        return View::make('Master.internal_grid_view', $data)->render();
    }

    public function gridPageInfo($gridName){
        $grid_info = DB::table('sys_master_grid')->where('sys_master_grid_name', '=', $gridName)->first();
        if(empty($grid_info)){
            return  response()->json(array('status'=>'0','message'=>'No DataGrid Found'));
        }
        $sql_data = (array)$grid_info;
        $sqlquery = self::sqlQueryConcat($sql_data);
        $grid_data =  DB::select($sqlquery);
        $header_data['last'] = [];
        if(!empty($grid_data)){
            $columns = array_keys((array)$grid_data[0]);
            foreach ($columns as $key){
                array_push($header_data['last'], array('column' => $key));
            }
        }
        if(!empty($grid_info->hide_col_position)){
            $hide_col_position = implode(',', array_map(function($n){ return $n = $n - 1; }, explode(',', $grid_info->hide_col_position)));
        }else{
            $hide_col_position = '0';
        }
        $data['page_data'] = array(
            'page_title' => $grid_info->grid_title,
            'enable_form' => $grid_info->enable_form,
            'sys_master_entry_name' => $grid_info->sys_master_entry_name,
            'master_entry_url' => $grid_info->master_entry_url,
            'custom_search' => $grid_info->search_panel_slug,
            'custom_search_default_show' => 1,
            'header' => $header_data,
            'action_table' => $grid_info->action_table,
            'primary_key_field' => $grid_info->primary_key_field,
            'column_hide' => $hide_col_position,
            'slug' => $gridName
        );
        return $data;
    }
    public function getGridData(Request $request){
        $req_data = (array)$request->all();
        //dd($req_data);
        /************* Mendatory table field which are in table column *****************/
        $slug = $request->slug;
        $grid_info = DB::table('sys_master_grid')->where('sys_master_grid_name', '=', $slug)->first();
        $sqlcondition = sessionFilter('sys_master_grid', $slug, $grid_info->sqlcondition);
        $sql_data = (array)$grid_info;
        $sqlquery = self::sqlQueryConcat($sql_data);
        $query =  DB::select($sqlquery);
//        $table_header = array_keys((array)$query[0]);
        if(isset($query[0])){
            $table_header = array_keys((array)$query[0]);
        }else{
            $table_header = [];
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            );
            return json_encode($output);
        }
        $start = $req_data['start']; // Mendatory value
        $limit = $req_data['length']; // Mendatory value
        $order = isset($req_data['order'][0]['column'])?$req_data['order'][0]['column']:0; // Mendatory value order by
        $sort = isset($req_data['order'][0]['dir'])?$req_data['order'][0]['dir']:'ASC'; // Mendatory value asc/desc

        /*****************************************/
        $pk = explode('.', $grid_info->primary_key_field);
        $primary_key_field = sizeof($pk) > 1 ? $pk[1] : $pk[0];

        $req_data['grid_sql'] = $grid_info->grid_sql;
        $req_data['sqlsource'] = $grid_info->sqlsource;
        $req_data['sqlcondition'] = $sqlcondition;
        $req_data['sqlgroupby'] = $grid_info->sqlgroupby;
        $req_data['sqlhaving'] = $grid_info->sqlhaving;
        $req_data['sqlorderby'] = $grid_info->sqlorderby;
        $req_data['primary_key_field'] = $grid_info->primary_key_field;
        $req_data['search_columns'] = $grid_info->search_columns;
        $datatable_condition = self::dropDownQueryGenerator($req_data);
        $where_condition = $datatable_condition['where_con'];
        $having_condition = $datatable_condition['having_con'];
        $req_data['where_condition'] = $where_condition;
        $req_data['having_condition'] = $having_condition;
        $datatable_query = self::sqlQueryConcat($req_data);
        $table_data = self::dropDownQueryData($table_header, $req_data, $start, $limit, $order, $sort);
        $rows = [];
        $attribute_fields = explode(',', $grid_info->tr_data_attr);
        foreach ($table_data as $item) {
            $row = [];
            $row[0] = '';
            $with_attr = [];
            $with_attr[0] = [];
            foreach ($item as $name => $td_data) {
                if($name == $primary_key_field){
                    $with_attr[0]['attr'] = $name;
                    $with_attr[0]['value'] = $td_data;
                } else if(in_array($name, $attribute_fields)) {
                    $row[] = $td_data;
                    $with_attr[] = array(
                        'attr' => $name,
                        'value' => $td_data
                    );
                } else if(strpos($name,'date')){
                    $row[] = toDated($td_data);
                }else if(strpos($name,'price')||strpos($name,'amount')||strpos($name,'qty')){
                    $row[] = "<span class='number-format'>".datatable_moneyFormat($td_data)."</span>";
                }else{
                    $row[] = $td_data;
                }

                $row[0] = $with_attr;
            }
            $rows[] = $row;
        }
        $total_rows = count(DB::select($datatable_query));
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $total_rows,
            "recordsFiltered" => $total_rows,
            "data" => $rows,
        );
        return json_encode($output);
    }

/**===================================================================================================**/
    public function sqlQueryConcat($data){
        extract($data);
        $sqltext = $data['grid_sql'] ? $data['grid_sql'] : '';
        $sqlsource = $data['sqlsource'] ? $data['sqlsource'] : '';
        $sqlcondition = $data['sqlcondition'] ? $data['sqlcondition'] : '';
        $sqlgroupby = $data['sqlgroupby'] ? $data['sqlgroupby'] : '';
        $having_condition = isset($data['sqlhaving']) ? $data['sqlhaving'] : '';
        $where_condition = isset($data['where_condition']) ? $data['where_condition'] : $sqlcondition;
        $sql_query = $sqltext.' '.$sqlsource.' '.$where_condition.' '.$sqlgroupby.' '.$having_condition;
        return $sql_query;
    }
    private function dropDownQueryGenerator($req_data){
        $sqlcondition = $req_data['sqlcondition'];
        $custom_search_con = [];
        $custom_search_con['WH'] = [];
        $table_search = $req_data['search']['value'];
        $search_columns = $req_data['search_columns'] ? explode(',', $req_data['search_columns']) : [];
        $search_columns = array_filter($search_columns);
        $custom_search = isset($req_data['search_data']) ? $req_data['search_data'] : '';
        $custom_search_con = self::customSearch($custom_search);

        $custom_search_con_hv = $custom_search_con_wh = [];

        $exceptional_case = [];
        $wherecon = $havingcon = '';
        $between_case_wh = [];
        if(isset($custom_search_con['WH'])){
            $custom_search_con_wh = $custom_search_con['WH'];
            foreach( $custom_search_con_wh as $key_name => $value ) {

                $exceptional_keys = array('_start','_end','_condition');
                foreach($exceptional_keys as $key){
                    if(strpos($key_name,$key)){
                        $item_key = substr($key_name,0,strpos($key_name,$key));
                        $between_case_wh[$item_key][trim($key,'_')] = $value;
                        unset($custom_search_con_wh[$key_name]);
                    }
                }
                if(strpos($key_name,'notable.') === 0){
                    $item_key = trim($key_name,'notable.');
                    $exceptional_case[$item_key] = $value;
                    unset($custom_search_con_wh[$key_name]);
                }
            }
            $wherecon .= self::betweenCondition($between_case_wh);
        }
        $between_case_hv = [];
        if(isset($custom_search_con['HV'])){
            $custom_search_con_hv = $custom_search_con['HV'];
            foreach( $custom_search_con_hv as $key_name => $value ) {
                $exceptional_keys = array('_start','_end','_condition','rangetype-');
                foreach($exceptional_keys as $key){
                    if(strpos($key_name,$key)){
                        $item_key = substr($key_name,0,strpos($key_name,$key));
                        $between_case_hv[$item_key][trim($key,'_')] = $value;
                        unset($custom_search_con_hv[$key_name]);
                    }
                }
            }
            $havingcon .= self::betweenCondition($between_case_hv);
        }

        if(!empty($custom_search_con)){
            $wherecon .= self::customSearchFormatter($custom_search_con_wh);
            $havingcon .= self::customSearchFormatter($custom_search_con_hv);
        }

        if(!empty($table_search) && !empty($search_columns)) {
            foreach ($search_columns as $i => $column) {
                if ($i == 0) {
                    $wherecon .= " AND ($column LIKE '%$table_search%'";
                } else {
                    $wherecon .= " OR $column LIKE '%$table_search%'";
                }
            }
            $wherecon .= ')';
        }

        if(!empty($exceptional_case)){
            $exceptional_con = self::exceptionalDataPrepare($exceptional_case);
            $wherecon .= $exceptional_con;
        }
        if(trim($wherecon)!=''){
            $wherecon = trim(trim($wherecon),'AND');
            $sqlcondition = $sqlcondition.' AND '.$wherecon;
        }
        if(trim($havingcon)!=''){
            $havingcon = trim(trim($havingcon),'AND');
            $havingcon = ' HAVING '.$havingcon;
        }
        $conditions = array(
            'where_con'=>$sqlcondition,
            'having_con'=>$havingcon,
        );
        return $conditions;
    }
    private function exceptionalDataPrepare($exceptional_data){
        $search_con = ' ';
        if(!empty($exceptional_data)) {
            foreach ($exceptional_data as $key=>$data) {
                if(strpos($key,'product_category_id')>=0){
                    $search_con_sub = ' ';
                    foreach ($data as $id){
                        if($id == 1){
                            $search_con_sub .= " OR products.is_rawmaterial=1";
                        }
                        if($id == 2){
                            $search_con_sub .= " OR products.is_finish_goods=1";
                        }
                        if($id == 3){
                            $search_con_sub .= " OR products.is_storeitem=1";
                        }
                        if($id == 5){
                            $search_con_sub .= " OR products.is_service=1";
                        }
                        if($id == 6){
                            $search_con_sub .= " OR products.is_salable=1";
                        }
                    }
                    $search_con_sub = str_replace_first('OR','',trim($search_con_sub));
                    $search_con .= ' AND ('.$search_con_sub.')';
                }

            }
            return $search_con;
        }
    }
    private function customSearchFormatter($custom_search_con){
        $con = '';
        foreach ($custom_search_con as $name=>$value) {
            $cust_src_con = trim(substr($name, 0, 3), '-');
            $col_name = substr($name, 3);
            $col_name = trim($col_name, '[]');
            if(sizeof($value) > 1 || $cust_src_con == 'IN'){
                $value = "'".implode("','" , $value)."'";
                $con .= " AND $col_name IN ($value)";
            }elseif($cust_src_con == 'DR'){
                $value = implode("','" , $value);
                $date_range = explode(' - ', $value);
                if(sizeof($date_range) > 1){
                    $date1 = date('Y-m-d',strtotime($date_range[0]));
                    $date2 = date('Y-m-d',strtotime($date_range[1]));
                    $con .= " AND $col_name BETWEEN '$date1' AND '$date2'";
                }
            }elseif($cust_src_con == 'LK'){
                $value = implode("','" , $value);
                $con .= " AND $col_name LIKE "."'%$value%'";
            }elseif($cust_src_con == 'EQ'){
                $value = "'".implode("','" , $value)."'";
                $con .= " AND $col_name = $value";
            }elseif($cust_src_con == 'RG'){
                $value = "'".implode("','" , $value)."'";
                $con .= " AND $col_name = $value";
            }else{
                $value = "'".implode("','" , $value)."'";
                $con .= " AND $name IN ($value)";
            }

        }
        return $con;
    }
    private function betweenCondition($data){
        $between_condition = '';
        foreach($data as $key =>$item){
            $col_name = substr($key,3);
            $condition = isset($item['condition'][0])?$item['condition'][0]:'';
            $start = isset($item['start'][0])?$item['start'][0]:'';
            $end = isset($item['end'][0])?$item['end'][0]:'';
            if($condition == 'between' && $end != ''){
                $between_condition .= " AND $col_name BETWEEN $start AND $end ";
            }elseif($end != ''){
                $between_condition .= " AND $col_name $condition $end ";
            }

        }
        return $between_condition;
    }
    private function customSearch($custom_search){
        $searchArray = array();
        if(!empty($custom_search)){
            foreach ($custom_search as $cust_src) {
                if(!empty($cust_src['value'])){
                    $cust_src_type = trim(substr($cust_src['name'], 0, 3), '-');
                    $name = trim(substr($cust_src['name'], 3));

                    if(strpos($cust_src['name'], '[]') == true){
                        str_replace($name, '[]', '');
                    }
                    $searchArray[$cust_src_type][$name][] = $cust_src['value'];
                }
            }
        }
        return $searchArray;
    }
    private function dropDownQueryData($table_header, $data, $start=0, $limit=10, $order=0, $sort='ASC'){
        $order_by = $table_header[$order];
        extract($data);
        $grid_sql = $data['grid_sql']?$data['grid_sql']:'';
        $sqlsource = $data['sqlsource']?$data['sqlsource']:'';
        $sqlorderby = $data['sqlorderby']?$data['sqlorderby']:'';
        $sqlgroupby = $data['sqlgroupby']?$data['sqlgroupby']:'';
        $sqlcondition = $data['sqlcondition']?$data['sqlcondition']:'';
        $having_condition = isset($data['sqlhaving'])?$data['sqlhaving']:'';
        $where_condition = isset($data['where_condition'])?$data['where_condition']:$sqlcondition;
        $sqlorderby = $order_by?' ORDER BY '.$order_by:$sqlorderby;
        $sql_query = $grid_sql.' '.$sqlsource.' '.$where_condition.' '.$sqlgroupby.' '.$having_condition;
        $q = DB::select($sql_query.' '.$sqlorderby.' '.$sort.' LIMIT '.$start.', '.$limit);
        return $q;
    }
/**===================================================================================================**/
    public function deleteRecord(Request $request){
        $request_data = $request->all();
        $sql = "DELETE FROM ".$request_data['table_name']
            ." WHERE "
            .$request_data['primary_key_field']
            ." IN ("
            .implode(',', $request_data['deleted_ids']).")";
        $nrd = DB::delete($sql);
        if(!empty($nrd) || $nrd != 0){
            $return = $nrd;
        }else{
            $return = 'failed';
        }
        echo json_encode($return);
    }
}
