<?php

function maintSaveAsDraftBtn($data){
  $user = auth()->user();
  if (!isset($data->fam_maint_requests_id)) {
    return true;
  } else if ($data->created_by == $user->id && $data->maint_req_statuses_id == '') {
    return true;
  }
  return false;
}

function maintSaveAndSendForApprovalBtn($data){
  $user = auth()->user();
  if (!isset($data->fam_maint_requests_id)) {
    return true;
  } else if ($data->created_by == $user->id && $data->maint_req_statuses_id == '') {
    return true;
  }
  return false;
}

function maintSendForApprovalBtn($data){
  $user = auth()->user();
  if ($data->created_by == $user->id && $data->maint_req_statuses_id == '') {
    return true;
  }
  return false;
}

function maintApproveBtn($data){
  $user = auth()->user();
  if ($data->is_manual){
    $delegation_manual_user = json_decode($data->delegation_manual_user, true);
    if ($data->delegation_person == $user->id
            && in_array($user->id, $delegation_manual_user)
                    && $data->maint_req_statuses_id == 75) {
      return true;
    }
  }
  return false;
}

function maintDeclineBtn($data){
  $user = auth()->user();
  if ($data->is_manual){
    $delegation_manual_user = json_decode($data->delegation_manual_user, true);
    if ($data->delegation_person == $user->id
            && in_array($user->id, $delegation_manual_user)
                    && $data->maint_req_statuses_id != 77) {
      return true;
    }
  }
  return false;
}

function maintenanceReceive($data){
  $user = auth()->user();
  if ($data->created_by == $user->id && $data->maint_req_statuses_id == 83) {
    return true;
  }
  return false;
}
