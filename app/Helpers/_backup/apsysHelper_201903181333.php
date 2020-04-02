<?php
function common_in_combo($name, $sql, $where, $selected_value, $extra, $id_field, $value_field, $onchange = false){
    $condtion = '';
    if ($where != NULL) {
        $condtion = " WHERE 1";
        foreach ($where as $key => $value) {
            $condtion .= " AND $key='$value'";
        }
    }
    $sql = $sql . $condtion;
    $query = DB::select(DB::raw($sql));
    $data = is_array($extra) && array_key_exists('multiple', $extra) ? array() : array('' => 'Select');
    $extra_str = '';
    if (!empty($extra)) {
        foreach ($extra as $key => $value) {
            $extra_str .= $key . '="' . $value . '"';
        }
    }
    if ($onchange) {
        $extra_str = $extra_str . $onchange;
    }
    foreach ($query->result_array() as $value) {
        $data[$value[$id_field]] = $value[$value_field];
    }
    return Form::select($name, $data, $selected_value, $extra_str);
}
function __combo($slug = '', $data = array()){
    extract($data);
    $selected_value = isset($selected_value) && !empty($selected_value) ? $selected_value : '';
    $name = isset($name) && !empty($name) ? $name : '';
    $attributes = isset($attributes) && !empty($attributes) ? $attributes : [];
    $sql_query = isset($sql_query) && !empty($sql_query) ? $sql_query : '';
    //    $is_option_data_required = isset($option_data) && !empty($option_data) ? $option_data : '';
    if (!empty($slug)) {
        $combodata = DB::table('sys_dropdowns')->where('dropdown_slug', $slug)->first();
        if (!empty($combodata)) {
            $sql = $sql_query == '' ? $combodata->sqltext : $sql_query;
            $query = DB::select($sql);
            $option_data = array();
            $attr = '';
            $attributes = empty($attributes) ? array('class' => 'form-control multi', 'id'=>$combodata->dropdown_name) : $attributes;
            if (!empty($attributes)) {
                foreach ($attributes as $key => $value) {
                    $attr .= $key . '="' . $value . '" ';
                }
            }
            $multiple = isset($multiple) ? $multiple : $combodata->multiple;
            if ($multiple == '1') {
                $attr .= 'multiple = "true"';
            } else {
                    //                if($is_option_data_required != 'No'){
                    $attr .= '';
                    $option_data[''] = "Choose Option";
                    //                }
            }
            if (empty($name)) {
                $name = $combodata->dropdown_name;
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
function debug($dt = null, $true = false){
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
    if ($true) {
        die("<b>die();</b>");
    }
}
function currentDate(){
    return date('Y-m-d');
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
function generateId($slug){
    $qresult = DB::select(DB::raw("call generateDynamicUniqueID('".$slug."')"));
    if($qresult) {
        $htm = $qresult[0]->getId;
        return $htm;
    }else{
        generateId($slug);
    }
}
if (!function_exists('searchAreaOption')) {
    function searchAreaOption($data = array())
    {
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
}
if (!function_exists('userWiseOptionRemove')) {
    function userWiseOptionRemove($options)
    {
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
}
function getOptionValue($key = ''){
    return $option_value = DB::table('sys_system_settings')->where('option_key', '=', $key)->value('option_value');
}
function getOptionStatus($key = ''){
    return $option_value = DB::table('sys_system_settings')->where('option_key', '=', $key)->value('option_status');
}
function getUoMIdBySlug($slug = ''){
    return $uom_id = DB::table('product_uoms')->where('short_name', '=', $slug)->value('product_uoms_id');
}
//function getEnumOptions($table='', $field='', $selected_value='', $multiple=''){
function getEnumOptions($data){

    $multiple_options = isset($data['multiple']) ==1 ?  'multiple' : '';
//    $field_name = $multiple_options != '' ? $field.'[]' : $field;
    $name = (isset($data['name'])?$data['name']:'');
//    debug($data,1);
    $type = DB::select(DB::raw('SHOW COLUMNS FROM '.(isset($data['table'])?$data['table']:'').' WHERE Field = "'.(isset($data['field'])?$data['field']:'').'"'))[0]->Type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $values = array();
    foreach(explode(',', $matches[1]) as $value){
        $options = trim($value, "'");
        $values[$options] = $options;
    }

    if(!empty($values)){
        return Form::select($name, $values, (isset($data['selected_value'])?$data['selected_value']:''), ['class' => 'form-control multi enum_dropdown','id'=>'payment_type', $multiple_options]);
    }else{
        return array();
    }
}
function number_to_words($number){
    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
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
function getBranchAddress(){
    $sql = DB::table('sys_users');
    $sql->select('address');
    $sql->where('id',Auth::user()->id);
    $result = $sql->first();
    return $result->address;
}
function __dropdown_grid($slug = '', $data = array()){
//    dd($data);
    extract($data);
    $selected_value = isset($selected_value) && !empty($selected_value) ? $selected_value : '';
    $name = isset($name) && !empty($name) ? $name : 'Add';
    $addbuttonid = isset($addbuttonid) && !empty($addbuttonid) ? $addbuttonid : 'add';
    $dependent_data = isset($dependent_data) && !empty($dependent_data) ? json_encode($dependent_data) : '';
    $dropdowndata = DB::table('sys_dropdowns')->where('dropdown_slug', $slug)->first();
    if(!empty($dropdowndata)) {
        if ($dropdowndata->dropdown_mode != 'dropdown_grid') {
            return __combo($slug, $data);
        } else {
            $attributes = empty($attributes) ? array('class' => "btn btn-primary", 'id' => $dropdowndata->dropdown_name) : $attributes;
            $attributes = array_merge($attributes, array('data-slug' => $slug, 'data-selected_value' => $selected_value, 'data-addbuttonid' => $addbuttonid, 'data-dependent_data' => $dependent_data));
            return Form::button($name, $attributes);
        }
    }

}


