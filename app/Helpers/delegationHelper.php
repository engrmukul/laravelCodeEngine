<?php

function getStatusName($status_flows_id=''){
    $sql = "SELECT sys_status_flows.status_flows_name 
            FROM `sys_status_flows`
            WHERE sys_status_flows.status_flows_id = '".$status_flows_id."'";
    $status = DB::select(DB::raw($sql));
    if($status->status_flows_name){
        return $status->status_flows_name;
    }else{
        return '';
    }
}

function goToDelegationProcess($data){
    if($data['delegation_type'] == 'send_for_approval'){
//        $result = app('App\Http\Controllers\Delegation\DelegationProcess')->delegationInitialize($data);
        $result = app('App\Http\Controllers\Delegation\DelegationProcess')->sendForApproval($data);
    }else if($data['delegation_type'] == 'approval'){
        $result = app('App\Http\Controllers\Delegation\DelegationProcess')->delegationApprove($data);
    }else if($data['delegation_type'] == 'decline'){
        $result = app('App\Http\Controllers\Delegation\DelegationProcess')->delegationDeclineProcess($data);
    }else if($data['delegation_type'] == 'approval_check'){
        $result = app('App\Http\Controllers\Delegation\DelegationProcess')->checkDeligationAccessibility($data['data']);
    }
    return $result;
}
function getDelegationHistory($ref_event,$ref_id){
    $sql = DB::table('sys_delegation_historys');
    $sql->select('sys_delegation_historys.*');
    $sql->addSelect('sys_users.name as uname','designations.designations_name');
    $sql->leftJoin('sys_users','sys_users.id','=','sys_delegation_historys.delegation_reliever_id');
    $sql->leftJoin('designations','designations.designations_id','=','sys_users.designations_id');
    $sql->where('ref_event',$ref_event);
    $sql->where('ref_id',$ref_id);
    $sql->orderBy('created_at');
    $result = $sql->get()->toArray();
    return $result;
}

function boardMembers($reference_code){
    $sql = DB::table('purchase_cs_boards');
    $sql->select('purchase_cs_boards.*','sys_users.username','designations.designations_name');
    $sql->leftJoin('sys_users','sys_users.id','=','purchase_cs_boards.users_id');
    $sql->leftJoin('designations','designations.designations_id','=','sys_users.designations_id');
    $sql->where('purchase_cs_id',$reference_code);
    $result = $sql->get()->toArray();
    return $result;
}


function getUserInfoFromDesignationId($designation_id){

    $sql = DB::table('sys_users');
    $sql->where('designations_id',$designation_id);
    $result = $sql->get()->first();
    return $result;
}
/*======================================================================*/