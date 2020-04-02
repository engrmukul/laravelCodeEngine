<?php
/**
 * Created by PhpStorm.
 * User: Rashed
 * Date: 6/19/2019
 * Time: 5:14 PM
 */
function attendanceData(){
    $data = DB::table('hr_temporary_emp_attendance')->get();
    return $data;
}
function getShiftDateTime($date,$in,$out){
    $shiftInTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $in));
    if($in>$out) {
        $shiftOutTime = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date . ' ' . $out)));
    }else{
        $shiftOutTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $out));
    }
    return array(
        'inTime'=>$shiftInTime,
        'outTime'=>$shiftOutTime,
    );
}

function getDateMax($data,$date){
    $filtarred_data = [];
    foreach($data as $key=>$each){
        if(date('Y-m-d',strtotime($each['logTime'])) == $date){
            $filtarred_data[] = array(
                'user_code'=>$each['user_code'],
                'logTime'=>$each['logTime']
            );
        }

    }
    return $filtarred_data;
}
function getAttendance($user_code,$date,$in,$out){
    $rotable_shift = array('2','3','4');
    $shiftTimes = getShiftDateTime($date,$in,$out);

//    dd($shiftTimes);
    $data = attendanceData();
//    dd($data);
    $max = getDateMax($data,$date);


    $attendanceData = [];

    return $attendanceData;
}