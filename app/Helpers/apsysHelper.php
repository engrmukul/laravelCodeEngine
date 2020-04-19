<?php
function __combo($slug = '', $data = array()){
    extract($data);
    $selected_value = isset($selected_value) && !empty($selected_value) ? $selected_value : '';
    $name = isset($name) && !empty($name) ? $name : '';
    $attributes = isset($attributes) && !empty($attributes) ? $attributes : [];
    $sql_query = isset($sql_query) && !empty($sql_query) ? $sql_query : '';
    if (!empty($slug)) {
        $combodata = DB::table('sys_dropdowns')->where('dropdown_slug', $slug)->first();
        if (!empty($combodata)) {
            $sql = $sql_query == '' ? $combodata->sqltext.' '.$combodata->sqlsource.' '.$combodata->sqlcondition : $sql_query;
            $sql = sessionFilter('sys_dropdowns',$slug,$sql);
            $query = DB::select($sql);
            $option_data = array();
            $attr = '';
            $multiple = isset($multiple) ? $multiple : $combodata->multiple;
            $class = $multiple == 1 ? 'form-control multi' : 'form-control';
            $attributes = empty($attributes) ? array('class' => $class, 'id' => $combodata->dropdown_name) : $attributes;
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    $attr .= $key . '="' . $value . '" ';
                }
            }

            if ($multiple == 1) {
                $attr .= 'multiple = "true"';
            }else{
                $option_data['0'] = '--Select an option--';
            }
            if (empty($name)) {
                if ($multiple == 1) {
                    $name = $combodata->dropdown_name.'[]';
                }else{
                    $name = $combodata->dropdown_name;
                }
            }
            foreach ($query as $value) {
                $value_field = $combodata->value_field;
                $option_field = $combodata->option_field;
                $option_data[$value->$value_field] = $value->$option_field;
            }
            return Form::select($name, $option_data, $selected_value, (array)$attr);
        }
    }
}

function getEnumOptions($data){
    $multiple_options = isset($data['multiple']) == 1 ?  'multiple' : '';
    $name = (isset($data['name'])?$data['name']:'');
    $option_id = (isset($data['id'])?$data['id']:'');
    $type = DB::select(DB::raw('SHOW COLUMNS FROM '.(isset($data['table'])?$data['table']:'').' WHERE Field = "'.(isset($data['field'])?$data['field']:'').'"'))[0]->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $values = array();
    foreach(explode(',', $matches[1]) as $value){
        $options = trim($value, "'");
        $values[$options] = $options;
    }
    if(!empty($values)){
        return Form::select($name, $values, (isset($data['selected_value'])?$data['selected_value']:''), ['class' => 'form-control multi enum_dropdown','id'=>$option_id, $multiple_options]);
    }else{
        return array();
    }
}

function fileUpload($filename, $desstination, $request){
    $data = array();
    $desstination = public_path() . '/' . $desstination;
    if ($request->hasfile($filename)) {
        foreach ($request->file($filename) as $file) {
            $name = date('Ymdhis') . $file->getClientOriginalName();
            $file->move($desstination, $name);
            $data[] = $name;
        }
    }
    return implode (", ", $data);
}

function isSuperUser(){
    return true ;
}

function debug($dt = null, $die = false){
    if (defined('DEBUG_REMOTE_ADDR') && $_SERVER['REMOTE_ADDR'] != DEBUG_REMOTE_ADDR) return;
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $file_line = "<strong>" . $caller['file'] . "(line " . $caller['line'] . ")</strong>\n";
    echo "<br/>";
    print_r($file_line);
    echo "<br/>";
    echo "<pre>";
    print_r($dt);
    echo "</pre>";
    if ($die) {
        die("<b>die();</b>");
    }
}
/*********************/

function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function currentDate(){
    date_default_timezone_set('Asia/Dhaka');
    return date('Y-m-d');
}

function currentDateTime(){
    date_default_timezone_set('Asia/Dhaka');
    return date('Y-m-d H:i:s');
}

function toDated($date) {
    if (!empty($date)) {
        return date("j M, Y", strtotime($date));
    } else {
        return date("j M, Y", strtotime(currentDate()));
    }
}

function toDateTimed($date) {
    if (!empty($date)) {
        return date("j M, Y h:i A", strtotime($date));
    }else{
        return date("j M, Y h:i A", strtotime(currentDate()));
    }
}

function toTimed($date) {
    if (!empty($date)) {
        return date("h:i A", strtotime($date));
    }else{
        return date("h:i A", strtotime(currentDate()));
    }
}

/*********************/

function generateId($slug){
    $qresult = DB::select(DB::raw("call generateDynamicUniqueID('".$slug."')"));
    if($qresult) {
        $htm = $qresult[0]->getId;
        return $htm;
    }else{
        generateId($slug);
    }
}

function searchAreaOption($data = array()){
    $all_options = array(
        'zone' => 1,
        'region' => 1,
        'territory' => 1,
        'house' => 1,
        'house_single'=>1,
        'aso' => 1,
        'route' => 1,
        'category' => 1,
        'brand' => 1,
        'sku' => 1,
        'month' => 1,
        'daterange' => 1,
        'package' => 1,
        'year' => 1,
        'datepicker' => 1,
        'dss_report_type' => 1,
        'ranking_report' => 1,
        'view-report'=>1,
        'Ordersalemode'=>1
    );
    $all_options=array_intersect_key($all_options,array_flip($data));
    $options = userWiseOptionRemove($all_options);
    $options['show'] = (in_array('show', $data) ? 1 : 0);
    return $options;
}

function userWiseOptionRemove($options){
    $user_type = Auth::user()->user_type;
    if ($user_type == 'zone') {
        unset($options['zone']);
    } else if ($user_type == 'region') {
        unset($options['zone']);
        unset($options['region']);
    } else if ($user_type == 'territory') {
        unset($options['zone']);
        unset($options['region']);
        unset($options['territory']);
    } else if ($user_type == 'house') {
        unset($options['zone']);
        unset($options['region']);
        unset($options['territory']);
        unset($options['house']);
        unset($options['house_single']);
    }
    return $options;
}

function getOptionValue($key = ''){
    return $option_value = DB::table('sys_system_settings')->where('option_key', '=', $key)->value('option_value');
}

function getOptionStatus($key = ''){
    return $option_value = DB::table('sys_system_settings')->where('option_key', '=', $key)->value('option_status');
}

function number_to_words($number){
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',16 => 'sixteen',
        17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty', 40 => 'fourty', 50 => 'fifty',
        60 => 'sixty', 70 => 'seventy', 80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand', 1000000 => 'million',
        1000000000 => 'billion', 1000000000000 => 'trillion', 1000000000000000 => 'quadrillion', 1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string)$fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return ucwords($string);
}

function documentUpload($request,$uploadData){
    $reference = $uploadData['reference'];
    $reference_id = $uploadData['reference_id'];
    $document_name = $uploadData['document_name'];
    //$destination_folder_name = $uploadData['destination_folder_name'];
    $destination_folder_name = $uploadData['reference'];
    $document = $request->file('select_file');
    $new_name = $reference.rand() . '.' . $document->getClientOriginalExtension();

    $data['reference'] = $reference;
    $data['reference_id'] = $reference_id;
    $data['document_name'] =  $document_name;
    $data['document_path'] = 'documents/'.$destination_folder_name.'/'.$new_name;
    $data['created_at'] = date('Y-m-d');
    $data['created_by'] = Auth::id();

    if (!is_dir(public_path('documents/'.$destination_folder_name))) {
        mkdir(public_path('documents/'.$destination_folder_name), 0777, true);
    }
    $document->move(public_path('documents/'.$destination_folder_name), $new_name);
    if(file_exists(public_path('documents/'.$destination_folder_name.'/'.$new_name))){
        DB::table('attachments')->insert($data);
        $attachments_id = DB::getPdo()->lastInsertId();
        if(!empty($attachments_id)){

            return response()->json([
                'status'=>'success',
                'message'=>'Document upload success'
            ]);
        }else{
            return response()->json([
                'status'=>'failed',
                'message'=>'Document upload fail'
            ]);
        }
    }else{
        return response()->json([
            'status'=>'failed',
            'message'=>'Document upload fail'
        ]);
    }
}

function getAttachmentInfo($reference,$reference_id){
    $sql = DB::table('attachments');
    $sql->where('reference_id',$reference_id);
    $sql->where('reference',$reference);
    $result = $sql->get()->toArray();
    return $result;
}

function getUserInfoFromId($sys_users_id){
    $sql = DB::table('sys_users');
    $sql->select('sys_users.address','sys_users.name');
    $sql->where('id',$sys_users_id);
    $result = $sql->first();
    return $result;
}

function __dropdown_grid($slug = '', $data = array()){
    //    dd($data);
    extract($data);
    $selected_value = isset($selected_value) && !empty($selected_value) ? $selected_value : '';
    $name = isset($name) && !empty($name) ? $name : 'Add';
    $selected_value_tag_id = isset($selected_value_tag_id) && !empty($selected_value_tag_id) ? $selected_value_tag_id : 'selected_value_tag_id';
    $addbuttonid = isset($addbuttonid) && !empty($addbuttonid) ? $addbuttonid : 'add';
    $multiple = isset($multiple) && !empty($multiple) ? $multiple : '';
    $dependent_data = isset($dependent_data) && !empty($dependent_data) ? json_encode($dependent_data) : '';
    $dropdowndata = DB::table('sys_dropdowns')->where('dropdown_slug', $slug)->first();
    if(!empty($dropdowndata)) {
        if ($dropdowndata->dropdown_mode != 'dropdown_grid') {
            return __combo($slug, $data);
        } else {
            if(!array_key_exists('attributes',$data)){
                $attributes = array('id' => $selected_value_tag_id);
                $attributes = array_merge($attributes, array('data-slug' => $slug, 'data-selected_value' => $selected_value, 'data-addbuttonid' => $addbuttonid, 'data-multiple'=>$multiple, 'data-dependent_data' => $dependent_data));
                return Form::hidden('',$selected_value, $attributes);
            }
            $attributes = empty($attributes) ? array('class' => "btn btn-primary", 'id' => $selected_value_tag_id?$selected_value_tag_id:$dropdowndata->dropdown_name) : $attributes;
            $attributes = array_merge($attributes, array('data-slug' => $slug, 'data-selected_value' => $selected_value, 'data-addbuttonid' => $addbuttonid, 'data-multiple'=>$multiple, 'data-dependent_data' => $dependent_data));
            return Form::button($name, $attributes);
        }
    }

}

function __getCustomSearch($slug = '', $searched_value = [], $prefix = false){
    return app('App\Http\Controllers\CustomSearch\CustomSearch')->fetchSearchForm($slug, $searched_value, $prefix);
}
function __getMasterGrid($slug = '', $enable_seach = 1){
    return app('App\Http\Controllers\Master\MasterGridController')->getGridForInternalUse($slug, $enable_seach);
}

function sessionFilter($event_ref, $slug, $sql){
    $user_access = session()->get('USER_ACCESS');
    $group_access = session()->get('GROUP_ACCESS');
    $query = '';
    if(!empty($group_access)){
        $search_options = isset($group_access[$event_ref][$slug])?$group_access[$event_ref][$slug]:'';
        $user_search_options = isset($user_access[$event_ref][$slug])?$user_access[$event_ref][$slug]:'';
        if(!empty($search_options) && empty($user_search_options)){
            $query = '';
            foreach($search_options as $key=>$option){
                $sql_where = trim(trim($option['sql_where_clause']),'AND');
                if(($option['permission'] == 'All' && !empty($option['no_permission']))){
                    $query .= " AND $key NOT IN (".stringToArray($option['no_permission']).")";
                }elseif($option['permission'] == 'NoAccess' && !empty($option['no_permission'])){
                    $query .= " AND $key IN (".stringToArray($option['no_permission']).")";
                }elseif(in_array('NoAccess',explode(',',$option['permission']))){
                    $per = explode(',',$option['permission']);
                    $access = array_diff($per,array('NoAccess'));
                    $access = implode(',',$access);
                    $query .= " AND $key IN (".stringToArray($access).")";
                }elseif(!empty($option['permission'])){
                    $query .= " AND $key IN (".stringToArray($option['permission']).")";
                }elseif(!empty($sql_where)){
                    $query .= " AND ($sql_where)";
                }
            }
        }
    }
    if(!empty($user_access)){
        $search_options = isset($user_access[$event_ref][$slug])?$user_access[$event_ref][$slug]:'';
        if(!empty($search_options)){
            $query = '';
            foreach($search_options as $key=>$option){
                $sql_where = trim(trim($option['sql_where_clause']),'AND');
                if(($option['permission'] == 'All' && !empty($option['no_permission']))){
                    $query .= " AND $key NOT IN (".stringToArray($option['no_permission']).")";
                }elseif($option['permission'] == 'NoAccess' && !empty($option['no_permission'])){
                    $query .= " AND $key IN (".stringToArray($option['no_permission']).")";
                }elseif(in_array('NoAccess',explode(',',$option['permission']))){
                    $per = explode(',',$option['permission']);
                    $access = array_diff($per,array('NoAccess'));
                    $access = implode(',',$access);
                    $query .= " AND $key IN (".stringToArray($access).")";
                }elseif(!empty($option['permission'])){
                    $query .= " AND $key IN (".stringToArray($option['permission']).")";
                }elseif(!empty($sql_where)){

                    $query .= " AND ($sql_where)";
                }
            }
        }
    }
    // prepare where clause conditions
    $sessionFilter = $query;
    if($sessionFilter){
        $sessionFilter = ' WHERE '.trim(trim($sessionFilter),'AND').' AND ';
        $sql = trim(trim($sql),'AND');
        if(strpos(strtolower($sql),'where')){
            $sql = str_replace('where', $sessionFilter, strtolower($sql));
        }else{
            $sessionFilter = trim(trim($sessionFilter),'AND');
            $sql = $sql.' '.$sessionFilter;
        }
    }
    if(empty($sql)){
        $sql = 'WHERE 1';
    }
    return $sql;
}

function stringToArray($string){
    $array = explode(',',$string);
    $new_array = [];
    foreach($array as $item){
        $new_array[] = "'".$item."'";
    }
    return implode(',',$new_array);
}

function getUnreadNotification(){
    $user_id = session()->get('USER_ID');
    return DB::table('sys_notifys')
        ->select('sys_notifys.*')
        ->where('sys_notifys.seen_status', 'Unseen')
        ->where('sys_notifys.notify_to', $user_id)
        ->count();
}

function __lang($key, $replace = [], $locale = null){
    $lang_file = Session::get('MODULE_LANG');
    $val = __($lang_file.'.'.$key, $replace, $locale);
    $newVal = str_replace($lang_file.'.','',$val);
    if($newVal == $key){
        $val = str_replace('_',' ',$newVal);
    }
    return ucwords($val);
}

function getSelectedOptionForMasterGrid($dropdown_slug = '', $selected_value = ''){
    $dropdown_info = DB::table('sys_dropdowns')->select('value_field','option_field')->where('dropdown_slug', $dropdown_slug)->first();
    $option_field = explode('.', $dropdown_info->option_field)[1];
    $value_field = explode('.', $dropdown_info->value_field)[1];
    $table_name = explode('.', $dropdown_info->option_field)[0];
    $query = "SELECT ".$option_field." FROM ".$table_name." WHERE ".$value_field." = '".$selected_value."'";
    return DB::select(DB::raw($query))[0]->$option_field;
}

function apsis_money($amount,$precision=2,$currency=false){
    $currency = $currency!==false?$currency:getOptionValue('default_currency');
    $amount = number_format($amount,$precision);
    return $amount.' '.$currency;
    $currency = $currency!==false?$currency:getOptionValue('default_currency');
    $amount = number_format($amount,$precision);
    return $amount.' '.$currency;
}

function apsis_money_bn($amount,$precision=2,$currency=false){
    $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $currency = $currency!==false?$currency:getOptionValue('default_currency');
    $amount = number_format($amount,$precision);
    $amount = str_replace($en,$bn, $amount);
    return $amount.' '.$currency;
}

function datatable_moneyFormat($value){
    $number = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
    $number = !empty($number)?$number:0;
    $currency = str_replace($number,'',$value);
    return number_format($number,2).' '.$currency;
}

function getAutocompleteData($query = '', $search_key = ''){
    $dropdowndata['suggestions'] = [];
    $format_query = str_replace('SEARCH_KEY', $search_key, $query);
    $results = DB::select(DB::raw($format_query));
    foreach ($results as $key => $result){
        $dropdowndata['suggestions'][$key]['value'] = $result->data_option; // string
        $dropdowndata['suggestions'][$key]['data'] = $result->data_value; // any
    }
    return response()->json($dropdowndata);
}

function getMergedQueryForDashboard($sqlparts){
    $select_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['select_sql']);
    $source_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['source_sql']);
    $condition_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['condition_sql']);
    $having_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['having_sql']);
    $groupby_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['groupby_sql']);
    $orderby_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['orderby_sql']);
    $limit_sql = preg_replace('~[\r\n]+~', ' ', $sqlparts['limit_sql']);
    $sql = $select_sql . ' ' . $source_sql . ' ' . $condition_sql . ' ' . $having_sql . ' ' . $groupby_sql . ' ' . $orderby_sql . ' ' . $limit_sql;
    return $sql;
}
