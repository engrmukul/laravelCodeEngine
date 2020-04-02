<?php
function getTagLabelAndValue($data){
    $statement_tags  = DB::table('acc_voucher_tag_details');

    if(isset($data['voucher_no'])){
        $statement_tags->where('voucher_no',$data['voucher_no']);
    }

    if(isset($data['voucher_line_id'])){
        $statement_tags->where('voucher_line_id',$data['voucher_line_id']);
    }


    if(isset($data['sl'])){
        $statement_tags->where('sl_no',$data['sl']);
    }

    $tags = $statement_tags->get()->toArray();
    $tag_array = array();
    foreach($tags as $tag){
        $acc_tags  = DB::table('acc_tags');
        $acc_tags->where('voucher_ref_field',$tag->voucher_reference_name);
        $acc_tag = $acc_tags->get()->first();

        $acc_tag_lebels  = DB::table($acc_tag->table_name);
        $acc_tag_lebels->where($acc_tag->field_id,$tag->voucher_reference_id);
        $acc_tag_lebel = $acc_tag_lebels->get()->first();
        $acc_tag_lebel_array = (array)$acc_tag_lebel;
        if(isset($data['with_raw'])){
            $ra = $acc_tag->voucher_ref_field.'-'.$tag->voucher_reference_id;
            $tag_array[$tag->voucher_line_id][$ra] = '<b>'.$acc_tag->acc_tag_name.'</b>'.':'.$acc_tag_lebel_array[$acc_tag->field_name];
        }else{
            $tag_array[$tag->voucher_line_id][] = '<b>'.$acc_tag->acc_tag_name.'</b>'.':'.$acc_tag_lebel_array[$acc_tag->field_name];
        }
    }
    return $tag_array;
}


function getTagLabelAndValueForCartSingle($data){

    $tag_array = array();
    foreach($data['tags'] as $tag){
        //debug($tag,1);
        $tagValue = explode('-',$tag);
        $acc_tags  = DB::table('acc_tags');
        $acc_tags->where('voucher_ref_field',$tagValue[0]);
        $acc_tag = $acc_tags->get()->first();

        $acc_tag_lebels  = DB::table($acc_tag->table_name);
        $acc_tag_lebels->where($acc_tag->field_id,$tagValue[1]);
        $acc_tag_lebel = $acc_tag_lebels->get()->first();

        $acc_tag_lebel_array = (array)$acc_tag_lebel;
        if(isset($data['with_raw'])){
            $tag_array[$tag] = '<b>'.$acc_tag->acc_tag_name.'</b>'.':'.$acc_tag_lebel_array[$acc_tag->field_name];
        }else if(isset($data['orginal_val'])){
            //$tag_array[] = $acc_tag->acc_tag_name.'-'.$acc_tag_lebel_array[$acc_tag->field_name];
            $tag_array[] = $tag;
        }else{
            $tag_array[] = '<b>'.$acc_tag->acc_tag_name.'</b>'.':'.$acc_tag_lebel_array[$acc_tag->field_name];
        }

    }
    return $tag_array;
}




function getTaggedDropdown($data){
    $columns = 12;
    if(isset($data['columns'])){
        $columns = $data['columns'];
    }
    $selected_tag = array();
    if(isset($data['selected_tags'])){
        $selected_tag = $data['selected_tags'];
    }
    $html = "";
    $accounts_head_tag_details  = DB::table('acc_head_tag_details')
        ->select('acc_head_tag_details.*','acc_tags.acc_tag_name','acc_tags.track_name','acc_tags.field_id','acc_tags.field_name',
            'acc_tags.table_name','acc_tags.voucher_ref_field','acc_tags.acc_tags_id')
        ->leftJoin('acc_tags', 'acc_head_tag_details.acc_tag_id', '=', 'acc_tags.acc_tags_id')
        ->where('acc_tags.status', '=', 'Active')
        ->where('acc_head_tag_details.acc_head_id', '=', $data['account_code'])
        ->get()->toArray();
    $html .= '';
        foreach ($accounts_head_tag_details as $value) {
            $queryInner  = DB::table($value->table_name)
                ->select($value->field_id,$value->field_name)
                ->get()->toArray();
            $html .='<div class="form-group col-lg-'.$columns.'"><label for="" class="control-label">' . $value->acc_tag_name . '</label>';
            $html .='<select name="tagged_account[]" class="tagged_account form-control" style="padding:0px !important;width:100%;">';
            $html .='<option value="">Select One</option>';
            foreach ($queryInner as $k=>$innerVal) {
                $field_name = $value->field_name;
                $field_id = $value->field_id;
                $tagvalue = $value->voucher_ref_field . '-' . $innerVal->$field_id;
                $html .='<option ' . (in_array($tagvalue,$selected_tag)?'selected':'') . ' value="' . $tagvalue . '">' . $innerVal->$field_name . '</option>';
            }
            $html .= '</select></div>';
        }

        return $html;
    }
//Developed by Mamun
function getChequeTaggedLabel($tag_value){
        $tag_html = '';
        
        $tag_data = explode('-',$tag_value);
        
        $acc_tags  = DB::table('acc_tags');
        $acc_tags->where('voucher_ref_field',$tag_data[0]);
        $acc_tag = $acc_tags->get()->first();
        
        $tag_info = DB::table('acc_tags')->where('voucher_ref_field',$tag_data[0])->first();
        $tag_result = DB::table($tag_info->table_name)->where($tag_info->field_id,$tag_data[1])->first();
        $tag_result_array = (array)$tag_result;
        $tag_html .= '<span class="btn btn-success btn-xs tags_button">'.$acc_tag->acc_tag_name.': '.$tag_result_array[$tag_info->field_name].'</span>&nbsp;';
               
        return $tag_html;
    }

?>