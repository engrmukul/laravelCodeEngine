<?php
/**
 * @param $voucher_line_id
 * @return string
 */
function getVoucherTagStory($voucher_line_id){
    $accounts_head_tag_details = DB::table('acc_voucher_tag_details')
        ->select('acc_voucher_tag_details.*')
        ->where('acc_voucher_tag_details.voucher_line_id', '=', $voucher_line_id)
        ->get()->toArray();
    $html ='';
    $tag_name ='';
    if(!empty($accounts_head_tag_details)){
        $html .= '( ';
        foreach ($accounts_head_tag_details as $value){
            $tag_info = DB::table('acc_tags')
                ->select('acc_tags.acc_tag_name','acc_tags.track_name','acc_tags.field_id','acc_tags.field_name',
                    'acc_tags.table_name','acc_tags.voucher_ref_field','acc_tags.acc_tags_id')
                ->where('acc_tags.voucher_ref_field', '=', $value->voucher_reference_name)
                ->get()->toArray();
            if(!empty($tag_info)){
                foreach ($tag_info as $v){
                    $tag_name = $v->acc_tag_name;
                    $queryInner  = DB::table($v->table_name)
                        ->select(DB::raw($v->field_name.' as name'))
                        ->where($v->field_id, '=', $value->voucher_reference_id)
                        ->get()->first();
                    $html .= $tag_name.' = '.$queryInner->name.' ,';
                }
            }
        }
        $html .= ' )';
    }
    return $html;
}
/**
 * @param $product
 * @param $event
 * @param $reference_table
 * @param $reference_id
 * @param $amount
 * @param null $voucherno
 * @param null $reference
 * @param null $voucherdate
 * @param null $is_reverse
 * @return bool
 */
function autoVoucherProcess($product,$event,$reference_table,$reference_id,$amount,$voucherno = NULL,$reference = NULL,$voucherdate = NULL,$is_reverse = NULL){
    if(empty($voucherno)){
        $actualId = generateId('vr_code');
    }else{
        $actualId = $voucherno;
    }
    $reference_tag_details = referenceTagDetails($reference_table,$reference_id);
    $autouvoucher_account_head_def_details = autovoucherDefDetails($product,$event);
    $sl = 1;
    $vouchermain_amount = 0;
    if(is_null($voucherdate)){
        $voucherdate =date("Y-m-d h:i:s");
    }else{
        $voucherdate = $voucherdate;
    }
//dd($autouvoucher_account_head_def_details);
    foreach ($autouvoucher_account_head_def_details as $k => $v){
        $prepare_acc_voucherdetails = array();
        if($v['payment_caption'] != 'Amt'){
            $amount = paymentCaptionDetails($v['payment_caption_query'],$reference_id);
        }else{
            $amount = $amount;
        }
        $vouchermain_amount = $amount?$amount:0;
        if(is_numeric($amount) && $amount > 0){
            $voucher_line_id = $actualId . '-' . $sl;
            $prepare_acc_voucherdetails = array(
                'voucher_line_id' => $voucher_line_id,
                'voucher_no' => $actualId,
                'sl_no' => $sl,
                'branch_code' => NULL,
                'account_name' => $v['account_name'],
                'isdishonoured' => 0,
                'currency' => 'BDT',
                'rate' => 1,
                'reference'=> $reference,
                'user_id' => Auth::user()->id,
                'effective_date' => $voucherdate
            );
            if($v['dr_cr'] == "C"){
                $prepare_acc_voucherdetails['credit'] = $amount;
                $prepare_acc_voucherdetails['debit'] = 0;
            }else{
                $prepare_acc_voucherdetails['credit'] = 0;
                $prepare_acc_voucherdetails['debit'] = $amount;
            }
            $is_insert_into_acc_voucher_details = DB::table('acc_voucher_details')->insert($prepare_acc_voucherdetails);
            $sl++;
            if($is_insert_into_acc_voucher_details > 0){
                if (!empty($reference_tag_details)) {
                    $tag_array = [];
                    foreach ($reference_tag_details as $key=>$value) {
                        $prepare_tag_array = [];
                        $prepare_tag_array['voucher_line_id'] = $voucher_line_id;
                        $prepare_tag_array['voucher_no'] = $actualId;
                        $prepare_tag_array['voucher_reference_name'] = $key;
                        $prepare_tag_array['voucher_reference_id'] = $value;
                        array_push($tag_array,$prepare_tag_array);
                    }
                    DB::table('acc_voucher_tag_details')->insert($tag_array);
                }
            }
        }
    }
    $acc_voucher_main_insert_data = array(
        "voucher_no"=> $actualId,
        "voucher_type"=> 'Auto Voucher',
        "voucher_date"=> $voucherdate,
        "entry_date"=>date("Y-m-d h:i:s"),
        "total_amount"=> $vouchermain_amount,
        "description"=> $reference,
        "user_id"=> Auth::user()->id,
        "is_reversed"=> $is_reverse,
        "version"=>1,
        "voucher_event"=>$event,
        "application_status"=>"New"
    );
    DB::table('acc_voucher_main')->insert($acc_voucher_main_insert_data);
    return true;
}
/**
 * @param $reference_table
 * @param $reference_id
 * @return array
 */
function referenceTagDetails($reference_table, $reference_id){
    $tag_details_array = array();
    $reference_table_name = $reference_table;
    $autoVoucherReferenceSettingsQuery  = DB::table('acc_autovoucher_reference_settings')
        ->select('acc_autovoucher_reference_settings.reference_table_tag_column','acc_autovoucher_reference_settings.reference_id_column','acc_autovoucher_reference_settings.voucher_reference_field')
        ->where('acc_autovoucher_reference_settings.reference_table', '=', $reference_table)
        ->get()->toArray();
    $tags = array();
    foreach ($autoVoucherReferenceSettingsQuery as $reference){
        $tag_data  = DB::table($reference_table)
            ->select($reference->reference_table_tag_column .' as voucher_reference_field_value')
            ->where($reference->reference_id_column, '=', $reference_id)
            ->get()->first();
        $tags[$reference->voucher_reference_field] = $tag_data->voucher_reference_field_value;
    }
    return $tags;
}
/**
 * @param $product
 * @param $event
 * @return array
 */
function autovoucherDefDetails($product,$event){
    $autovoucher_def_details = array();
    $autoVoucher_def_details  = DB::table('acc_autovoucherdefs')
        ->select('acc_autovoucherdefs.*')
        ->where('acc_autovoucherdefs.product', $product)
        ->where('acc_autovoucherdefs.event', $event)
        ->where('acc_autovoucherdefs.status', 'Active')
        ->get()->toArray();
    foreach ($autoVoucher_def_details as $k=>$data){
        $autovoucher_def_details[$k] = array(
            'dr_cr' => $data->dr_cr,
            'product' => $data->product,
            'payment_caption' => $data->payment_caption,
            'payment_caption_query' =>$data->payment_caption_query,
            'account_name' => $data->account_name
        );
    }
    return $autovoucher_def_details;
}
function paymentCaptionDetails($query,$reference_id){
    $amount = 0;
    //Need to test whether payment caption is working or not.
    $string = str_replace("@refid", "'$reference_id'", $query);
    $string = str_replace("set @Amount=(", "", $string);
    $actual_query = str_replace(")", "", $string);
    $final_query_data = DB::select(DB::raw($actual_query));
    foreach ($final_query_data[0] as $key=>$value){
        $amount = (float)$value;
    }
    return $amount;
}

//function getTaggedAccounts($account_code,$column,$is_have_edit_id = NULL,$is_only_selected = NULL){
//    $html = "";
//    $accounts_head_tag_details  = DB::table('acc_head_tag_details')
//        ->select('acc_head_tag_details.*','acc_tags.acc_tag_name','acc_tags.track_name','acc_tags.field_id','acc_tags.field_name',
//            'acc_tags.table_name','acc_tags.voucher_ref_field','acc_tags.acc_tags_id')
//        ->leftJoin('acc_tags', 'acc_head_tag_details.acc_tag_id', '=', 'acc_tags.acc_tags_id')
//        ->where('acc_tags.status', '=', 'Active')
//        ->where('acc_head_tag_details.acc_head_id', '=', $account_code)
//        ->get()->toArray();
//    $html .='';
//    if ($is_have_edit_id == null && ($is_only_selected == '') && ($is_only_selected == NULL)) {
//
//        foreach ($accounts_head_tag_details as $value) {
//            $queryInner  = DB::table($value->table_name)
//                ->select(DB::raw($value->field_id))
//                ->addSelect(DB::raw($value->field_name))
//                ->get()->toArray();
//            $html .='<div class="form-group col-lg-'.$column.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
//            $html .='<select name="tagged_account[]" class="tagged_account form-control '.$value->voucher_ref_field.'" style="padding:0px !important;width:100%;">';
//            $html .='<option value="">Select One</option>';
//
//            foreach ($queryInner as $innerVal) {
//                $field_name = $value->field_name;
//                $field_id = $value->field_id;
//                if($innerVal->$field_name)
//                {
//                    $html .='<option value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';
//                }
//            }
//            $html .='</select></div>';
//        }
//    }else{
//        $selected_val  = DB::table('acc_voucher_tag_details')
//            ->select('acc_voucher_tag_details.*')
//            ->where('acc_voucher_tag_details.voucher_line_id', '=', $is_have_edit_id)
//            ->get()->toArray();
//        if(empty($selected_val)){
//            foreach ($accounts_head_tag_details as $value) {
//                $queryInner  = DB::table($value->table_name)
//                    ->select(DB::raw($value->field_id))
//                    ->addSelect(DB::raw($value->field_name))
//                    ->get()->toArray();
//                $html .='<div class="form-group col-lg-'.$column.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
//                $html .='<select name="tagged_account[]" class="tagged_account form-control" style="padding:0px !important;width:100%;">';
//                $html .='<option value="">Select One</option>';
//
//                foreach ($queryInner as $innerVal) {
//                    $field_name = $value->field_name;
//                    $field_id = $value->field_id;
//                    if($innerVal->$field_name)
//                    {
//                        $html .='<option value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';
//                    }
//                }
//                $html .='</select></div>';
//            }
//        }else{
//            if($is_only_selected == 'yes'){
//                $accounts_head_tag_details  = DB::table('acc_head_tag_details')
//                    ->select('acc_head_tag_details.*','acc_tags.acc_tag_name','acc_tags.track_name','acc_tags.field_id','acc_tags.field_name',
//                        'acc_tags.table_name','acc_tags.voucher_ref_field','acc_tags.acc_tags_id')
//                    ->leftJoin('acc_tags', 'acc_head_tag_details.acc_tag_id', '=', 'acc_tags.acc_tags_id')
//                    ->leftJoin('acc_voucher_tag_details', 'acc_tags.voucher_ref_field', '=', 'acc_voucher_tag_details.voucher_reference_name')
//                    ->where('acc_tags.status', '=', 'Active')
//                    ->where('acc_voucher_tag_details.voucher_line_id', '=', $is_have_edit_id)
//                    ->where('acc_head_tag_details.acc_head_id', '=', $account_code)
//                    ->get()->toArray();
//
//                foreach ($accounts_head_tag_details as $value) {
//                    $queryInner  = DB::table($value->table_name)
//                        ->select($value->field_id,$value->field_name)
//                        ->get()->toArray();
//                    $html .='<div class="form-group col-lg-'.$column.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
//                    $html .='<select name="tagged_account[]" class="tagged_account form-control" style="padding:0px !important;width:100%;">';
//                    $html .='<option value="">Select One</option>';
//                    foreach ($queryInner as $k=>$innerVal) {
//                        $field_name = $value->field_name;
//                        $field_id = $value->field_id;
//                        foreach ($selected_val as $key=>$select_val){
//                            if($innerVal->$field_name)
//                            {
//                                $html .='<option ' . ($selected_val[$key]->voucher_reference_name.'-'.$selected_val[$key]->voucher_reference_id == $value->voucher_ref_field . '-' . $innerVal->$field_id ? "selected" : "") . ' value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';
//                            }
//                        }
//                    }
//                    $html .='</select></div>';
//                }
//
//            }else{
//                $selected_val  = DB::table('acc_voucher_tag_details')
//                    ->select('acc_voucher_tag_details.voucher_reference_id')
//                    ->addSelect('acc_tags.field_id')
//                    ->leftJoin('acc_tags','acc_voucher_tag_details.voucher_reference_name', '=',  'acc_tags.voucher_ref_field')
//                    ->where('acc_voucher_tag_details.voucher_line_id', '=', $is_have_edit_id)
//                    ->get()->toArray();
//
//                $make_array = array();
//
//                foreach ($selected_val as $v){
//                    $make_array[$v->field_id] = $v->voucher_reference_id;
//                }
//                foreach ($accounts_head_tag_details as $value) {
//                    $queryInner  = DB::table($value->table_name)
//                        ->select($value->field_id,$value->field_name)
//                        ->get()->toArray();
//                    $html .='<div class="form-group col-lg-'.$column.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
//                    $html .='<select name="tagged_account[]" class="tagged_account form-control" style="padding:0px !important;width:100%;">';
//                    $html .='<option value="">Select One</option>';
//
//                    foreach ($queryInner as $k=>$innerVal) {
//
//                        $field_name = $value->field_name;
//                        $field_id = $value->field_id;
//
//                        if(isset($make_array[$field_id])){
//                            $html .='<option '.(($innerVal->$field_id == $make_array[$field_id])?'selected':'').' value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';
//
//                        }else{
//                            $html .='<option value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';
//
//                        }
//                    }
//                    $html .='</select></div>';
//                }
//            }
//        }
//    }
//    return $html;
//}




function getTaggedAccounts($account_code,$column,$is_have_edit_id = NULL,$selected = array()){
    $html = "";
    $accounts_head_tag_details  = DB::table('acc_head_tag_details')
        ->select(
            'acc_head_tag_details.*',
            'acc_tags.acc_tag_name',
            'acc_tags.track_name',
            'acc_tags.field_id',
            'acc_tags.field_name',
            'acc_tags.table_name',
            'acc_tags.voucher_ref_field',
            'acc_tags.acc_tags_id'
        )
        ->leftJoin('acc_tags', 'acc_head_tag_details.acc_tag_id', '=', 'acc_tags.acc_tags_id')
        ->where('acc_tags.status', '=', 'Active')
        ->where('acc_head_tag_details.acc_head_id', '=', $account_code)
        ->get()->toArray();

    $html .='';

    $make_array = array();
    if($is_have_edit_id){
        $selected_sql  = DB::table('acc_voucher_tag_details');
        $selected_sql->select('acc_voucher_tag_details.*');
        $selected_sql->addSelect('acc_tags.field_id');
        $selected_sql->leftJoin('acc_tags','acc_voucher_tag_details.voucher_reference_name', '=',  'acc_tags.voucher_ref_field');
        $selected_sql->where('acc_voucher_tag_details.voucher_line_id', '=', $is_have_edit_id);
        $selected_val = $selected_sql->get()->toArray();

        foreach ($selected_val as $v){
            $make_array[$v->field_id] = $v->voucher_reference_id;
        }
    }

    if($selected){
        foreach($selected as $selected_tag){
            $st_array = explode('-',$selected_tag);
            $selected_sql  = DB::table('acc_tags');
            $selected_sql->where('acc_tags.voucher_ref_field', $st_array[0]);
            $selected_val = $selected_sql->first();
            $make_array[$selected_val->field_id] = $st_array[1];
        }
    }
    foreach ($accounts_head_tag_details as $value) {
        $queryInner  = DB::table($value->table_name)
            ->select($value->field_id,$value->field_name)
            ->get()->toArray();
        $html .='<div class="form-group col-lg-'.$column.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
        $html .='<select name="tagged_account[]" class="tagged_account form-control" style="padding:0px !important;width:100%;">';
        $html .='<option value="">Select One</option>';
//                        debug($queryInner);
        foreach ($queryInner as $k=>$innerVal) {
//                            debug($innerVal);
            $field_name = $value->field_name;
            $field_id = $value->field_id;

            if(isset($make_array[$field_id])){
                $html .='<option '.(($innerVal->$field_id == $make_array[$field_id])?'selected':'').' value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';

            }else{
                $html .='<option value="' . $value->voucher_ref_field . '-' . $innerVal->$field_id . '">' . $innerVal->$field_name . '</option>';

            }
        }
        $html .='</select></div>';
    }



    return $html;
}





/*
 * @pram1 will gross salary of a employee
 * @pram2 will convince bill = medical+food+tada
 *
 * */
function salary_calculation($gross_salary,$convince_bill){
    $salary = [];
    if($gross_salary>0){
        $sub_salary = $gross_salary-$convince_bill;
        $salary['basic_salary'] = ($sub_salary/1.5);
        $salary['house_rent'] = 50;// 50 for 50%
        $salary['house_rent_amount'] = (($salary['basic_salary']*$salary['house_rent'])/100);
    }
    return $salary;
}
function customerDueCreditDate($cptn=''){
    $date = new DateTime('now');
    switch($cptn){
        case "Due end of the month":
            $date->modify('last day of this month');
            return $date->format('Y-m-d');
            break;
        case "Due end of next month":
            $date->modify('last day of next month');
            return $date->format('Y-m-d');
            break;
        case "Due on Receipt":
            return $date->format('Y-m-d');
            break;
        case "Net 15":
            $date->modify('+15 day');
            return $date->format('Y-m-d');
            break;
        case "Net 30":
            $date->modify('+30 day');
            return $date->format('Y-m-d');
            break;
        case "Net 45":
            $date->modify('+45 day');
            return $date->format('Y-m-d');
            break;
        case "Net 60":
            $date->modify('+60 day');
            return $date->format('Y-m-d');
            break;
        case "Net 90":
            $date->modify('+90 day');
            return $date->format('Y-m-d');
            break;
        default:
            return date('Y-m-d');
            break;
    }
}

function productNameFormat($data){
    extract((array) $data);
    $products_name = isset($products_name)?$products_name:'';
    $products_name.= isset($product_brands_name)&&$product_brands_name!=''?'-'.$product_brands_name:'';
    $products_name.= isset($product_models_name)&&$product_models_name!=''?'-'.$product_models_name:'';
    return $products_name;
}

function yearEarnLeaveEnjoy($emp_id){
    $leave_days = DB::table('hr_leave_records')
        ->selectRaw('sum(leave_days) as leave_days')
        ->selectRaw('YEAR(start_date) as year')
        ->join('hr_yearly_leave_policys','hr_yearly_leave_policys.hr_yearly_leave_policys_name','=','hr_leave_records.leave_types')
        ->where('sys_users_id',$emp_id)
        ->where('is_earn_leave','=','1')
        ->where('hr_leave_records.status','=','Active')
        ->groupBy(DB::raw('YEAR(start_date)'))
        ->get();
    if(!empty($leave_days)){
        $yearly_leave = [];
        foreach ($leave_days as $year){
            $yearly_leave[$year->year] = $year->leave_days;
        }
    }
    return $yearly_leave;
}

function year_earn_leave_encash($emp_id){
    $leave_encashments = DB::table('hr_leave_encashments')
        ->selectRaw('sum(encashment_days) as encashment_days')
        ->selectRaw('YEAR(encashment_date) as year')
        ->where('sys_users_id',$emp_id)
        ->where('status','=','Active')
        ->groupBy(DB::raw('YEAR(encashment_date)'))
        ->get();

    if(!empty($leave_encashments)){
        $yearly_leave_encash = [];
        foreach ($leave_encashments as $year){
            $yearly_leave_encash[$year->year] = $year->encashment_days;
        }
    }
    return $yearly_leave_encash;
}

function employeeInfo($user_id,$pdf=false){
    $data['emp_log'] = DB::table('sys_users')
        ->leftJoin('departments','departments.departments_id','=','sys_users.departments_id')
        ->leftJoin('designations','designations.designations_id','=','sys_users.designations_id')
        ->leftJoin('hr_emp_sections','hr_emp_sections.hr_emp_sections_id','=','sys_users.hr_emp_sections_id')
        ->leftJoin('hr_emp_units','hr_emp_units.hr_emp_units_id','=','sys_users.hr_emp_units_id')
        ->leftJoin('hr_emp_categorys','hr_emp_categorys.hr_emp_categorys_id','=','sys_users.hr_emp_categorys_id')
        ->where('id', $user_id)
        ->first();
    if($pdf){
        return view('HR.emp_info_pdf', $data);
    }else{
        return view('HR.emp_info', $data);
    }
}
function tdFormatter($key){
    if(strpos($key,'right_')!==false){
        return trim($key,'right_');
    }else if(strpos($key,'right_')!==false){
        return trim($key,'center_');
    }else{
        return $key;
    }
}
function tdDataFormatter($key,$val){
    if(strpos($key,'right_')!==false){
        return "<td align='right'>$val</td>";
    }else if(strpos($key,'center_')!==false){
        return "<td align='center'>$val</td>";
    }else{
        return "<td>$val</td>";
    }
}

function bonus_policy($emp_info,$eligible_date){
    $policy = DB::table('hr_emp_bonus_policys')
        ->where('hr_emp_categorys_id',$emp_info->hr_emp_categorys_id)
        ->orderBy('number_of_month','DESC')
        ->get();
    $date_of_join = new DateTime($emp_info->date_of_join);
    $date_of_confirmation = new DateTime($emp_info->date_of_confirmation);
    $eligible_date = new DateTime($eligible_date);

    $joining_diff = $eligible_date->diff($date_of_join);
    $confirm_diff = $eligible_date->diff($date_of_confirmation);
    $joining_diff_m = ($joining_diff->y*12)+$joining_diff->m;
    $confirm_diff_m = ($confirm_diff->y*12)+$confirm_diff->m;
    $bonus_base_on = 0;
    $bonus_amount = 0;
    foreach($policy as $p){
        if($p->bonus_based_on == 'basic'){
            $bonus_base_on = $emp_info->basic_salary;
        }else{
            $bonus_base_on = $emp_info->min_gross;
        }
        if($p->bonus_eligible_based_on == 'date_of_join'){

            if($joining_diff_m>=$p->number_of_month){

                return array(
                    'bonus_policys_id'=>$p->hr_emp_bonus_policys_id,
                    'bonus_policy'=>ucwords($p->bonus_based_on).'*'.$p->bonus_ratio.'%',
                    'bonus_amount'=>($p->bonus_ratio*$bonus_base_on)/100,
                    'bonus_eligible_based_on'=>$p->bonus_eligible_based_on,
                    'bonus_based_on'=>$p->bonus_based_on,
                    'total_month'=>$joining_diff_m,
                );
            }
        }else{
            if($confirm_diff_m>=$p->number_of_month){
                return array(
                    'bonus_policys_id'=>$p->hr_emp_bonus_policys_id,
                    'bonus_policy'=>ucwords($p->bonus_based_on).'*'.$p->bonus_ratio.'%',
                    'bonus_amount'=>($p->bonus_ratio*$bonus_base_on)/100,
                    'bonus_eligible_based_on'=>$p->bonus_eligible_based_on,
                    'bonus_based_on'=>$p->bonus_based_on,
                    'total_month'=>$joining_diff_m,
                );
            }
        }

    }
    return $bonus_amount;
}

function bonus_policy_manual($emp_info,$eligible_date,$manual_data=[]){
    $date_of_join = new DateTime($emp_info->date_of_join);
    $date_of_confirmation = new DateTime($emp_info->date_of_confirmation);
    $eligible_date = new DateTime($eligible_date);

    $joining_diff = $eligible_date->diff($date_of_join);
    $confirm_diff = $eligible_date->diff($date_of_confirmation);
    $joining_diff_m = ($joining_diff->y*12)+$joining_diff->m;
    $confirm_diff_m = ($confirm_diff->y*12)+$confirm_diff->m;

    if($manual_data['bonus_eligible_based_on'] == 'date_of_join'){
        $number_of_month = $joining_diff_m;
    }else{
        $number_of_month = $confirm_diff_m;
    }
    $q = DB::table('hr_emp_bonus_policys')
        ->selectRaw('ifnull(MAX(bonus_ratio),0) as bonus_ratio')
        ->where('hr_emp_categorys_id',$emp_info->hr_emp_categorys_id)
        ->where('number_of_month','<=',$number_of_month)
        ->get()->first();
    $policy_ratio = $q->bonus_ratio;
    $bonus_base_on = 0;
    $bonus_amount = 0;
    if($manual_data['bonus_based_on'] == 'basic'){
        $bonus_base_on = $emp_info->basic_salary;
    }else{
        $bonus_base_on = $emp_info->min_gross;
    }
    return array(
        'bonus_policys_id'=>null,
        'bonus_policy'=>ucwords($manual_data['bonus_based_on']).'*'.$policy_ratio.'%',
        'bonus_amount'=>($policy_ratio*$bonus_base_on)/100,
        'bonus_eligible_based_on'=>$manual_data['bonus_eligible_based_on'],
        'bonus_based_on'=>$manual_data['bonus_based_on'],
        'total_month'=>$number_of_month,
    );

}

function draft_limit_check($table,$status_col,$draft_id){
    $draft_limit = getOptionValue($table);
    $draft_limit = $draft_limit?$draft_limit:3;
    $draft_exists = DB::table($table)
        ->selectRaw('count(*) as total_draft')
        ->where('created_by',Auth::id())
        ->where($status_col,$draft_id)
        ->where('status','Active')
        ->get()
        ->first()->total_draft;
    if($draft_exists>=$draft_limit){
        return false;
    }

    return true;
}
function getUoMIdBySlug($slug = ''){
    return $uom_id = DB::table('product_uoms')->where('short_name', '=', $slug)->value('product_uoms_id');
}
function getUoMShortNameFromId($ids){
    $sql = DB::table('product_uoms')->select('short_name');
    if(is_array($ids)){
        $sql->whereIn('product_uoms_id',$ids);
        $sql_result = $sql->get()->toArray();
        $array_result = array_column($sql_result,'short_name');
        $result = $array_result;
    }else{
        $sql->where('product_uoms_id',$ids);
        $result = $sql->first()->short_name;
    }
    return $result;
}

//Get User Information by User Code
function getUserInfoByCode($user_code){
    return DB::table('sys_users')
        ->select(
            'sys_users.username',
            'sys_users.name',
            'sys_users.name_bangla',
            'sys_users.mobile',
            'sys_users.user_image',
            'sys_users.user_sign',
            'sys_users.email'
        )
        ->where('user_code',$user_code)
        ->first();
}

function stock_out_record_entry($ch_details_id,$product_id,$wh_id,$qty,$type,$reference){
    $record_type = '';
    $product_type = '';
    $out_qty = 0;
    $product = DB::table('products')->select('stock_out_type')->where('products_id',$product_id)->get()->first();
    $product_type = $product->stock_out_type;
    if($product_type == 'Custom'){
        trackable_item_out_record_entry($ch_details_id,$type,$reference);
        // call custom stock out process function

    }else {
        $unpacked_record = DB::table('products_received')
            ->selectRaw('products_received_id,ifnull(stock_out_qty,0) as stock_out_qty,(received_qty-IFNULL(stock_out_qty,0)) as remaining_qty')
            ->where('products_id', $product_id)
            ->where('warehouses_id', $wh_id)
            ->where('stock_out_status', 'Unpacked')
            ->get()
            ->first();

        if (!empty($unpacked_record) && $unpacked_record->remaining_qty >= 0) {
            if ($unpacked_record->remaining_qty == $qty) {
                $out_qty = $qty;
                DB::table('products_received')
                    ->where('products_received_id', $unpacked_record->products_received_id)
                    ->update(['stock_out_qty' => ($unpacked_record->stock_out_qty + $qty), 'stock_out_status' => 'Stock Out']);
            } elseif ($unpacked_record->remaining_qty > $qty) {
                $out_qty = $qty;
                DB::table('products_received')
                    ->where('products_received_id', $unpacked_record->products_received_id)
                    ->increment('stock_out_qty', $qty);
            } else {
                $out_qty = $unpacked_record->remaining_qty;
                DB::table('products_received')
                    ->where('products_received_id', $unpacked_record->products_received_id)
                    ->increment('stock_out_qty', $unpacked_record->remaining_qty);
            }
        } else {
            if ($product_type == 'FIFO') {
                DB::select("UPDATE products_received set stock_out_status='Unpacked',stock_out_qty=0 where products_id=$product_id and warehouses_id=$wh_id and stock_out_status='Packed' order by stock_in_date asc limit 1;");
            } else {
                DB::select("UPDATE products_received set stock_out_status='Unpacked',stock_out_qty=0 where products_id=$product_id and warehouses_id=$wh_id and stock_out_status='Packed' order by stock_in_date desc limit 1;");
            }
            stock_out_record_entry($ch_details_id, $product_id, $wh_id, $qty, $type, $reference);
            return;
        }
        $used_qty = $transfer_qty = $sales_qty = 0;
        if ($type == 'Transfer') {
            $record_type = 'Transfer';
            $transfer_qty = $out_qty;
        } elseif ($type == 'Office use') {
            $record_type = 'Office use';
            $used_qty = $out_qty;
        } else {
            $record_type = 'Sales';
            $sales_qty = $out_qty;
        }
        DB::table('stock_out_record')->insert([
            'products_received_id' => $unpacked_record->products_received_id,
            'challan_details_id' => $ch_details_id,
            'stock_out_type' => $record_type,
            'stock_out_ref_id' => $reference,
            'stock_out_date' => date('Y-m-d'),
            'warehouses_id' => $wh_id,
            'products_id' => $product_id,
            'transfer_qty' => $transfer_qty,
            'sold_qty' => $sales_qty,
            'used_qty' => $used_qty,
        ]);

    }
    return true;
}

function trackable_item_out_record_entry($ch_details_id,$type,$reference){

        $items_info = DB::table('items_assign_log')
            ->selectRaw('items.products_id, count(*) as total_items,
            items.products_received_id,
            products_received.products_id,
            products_received.warehouses_id,
            ifnull(products_received.stock_out_qty,0) as stock_out_qty,
            (ifnull(products_received.received_qty,0)-ifnull(products_received.stock_out_qty,0)) as remaining_qty')
            ->join('items', 'items.items_id', '=', 'items_assign_log.items_id')
            ->join('products_received', 'items.products_received_id', '=', 'products_received.products_received_id')
            ->whereRaw(("items_assign_log.allocated_reference='challan_details'"))
            ->where('items_assign_log.allocated_reference_id', $ch_details_id)
            ->groupBy('items.products_received_id')
            ->get();
        if (!empty($items_info)) {
                foreach ($items_info as $item) {
                    if ($item->remaining_qty > $item->total_items) {
                        DB::table('products_received')
                            ->where('products_received_id', $item->products_received_id)
                            ->update(['stock_out_status' => 'Unpacked', 'stock_out_qty' => ($item->stock_out_qty + $item->total_items)]);
                    } else {
                        DB::table('products_received')
                            ->where('products_received_id', $item->products_received_id)
                            ->update(['stock_out_status' => 'Stock Out', 'stock_out_qty'=> ($item->stock_out_qty + $item->total_items)]);
                    }
                    $used_qty = $transfer_qty = $sales_qty = 0;
                    if ($type == 'Transfer') {
                        $record_type = 'Transfer';
                        $transfer_qty = $item->total_items;
                    } elseif ($type == 'Office use') {
                        $record_type = 'Office use';
                        $used_qty = $item->total_items;
                    } else {
                        $record_type = 'Sales';
                        $sales_qty = $item->total_items;
                    }
                    DB::table('stock_out_record')->insert([
                        'products_received_id' => $item->products_received_id,
                        'challan_details_id' => $ch_details_id,
                        'stock_out_type' => $record_type,
                        'stock_out_ref_id' => $reference,
                        'stock_out_date' => date('Y-m-d'),
                        'warehouses_id' => $item->warehouses_id,
                        'products_id' => $item->products_id,
                        'transfer_qty' => $transfer_qty,
                        'sold_qty' => $sales_qty,
                        'used_qty' => $used_qty,
                    ]);

                }
        }

    return;
}



//strips attributes and tags
function strip_attributes($string, $allow_tag=null){
    $allow = $allow_tag==null?'<h1><h2><h3><h4><h5><h6><p><a><b>':$allow_tag;
    $text = trim($string);
    $text = strip_tags($text, $allow);
    return preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $text);
}