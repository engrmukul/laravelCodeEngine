<?php

namespace App\Helper;
use DB;
use Auth;
//use excelHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\Delegation\DelegationProcess;

class ExportHelper
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public static function excelHeader($filename,$spreadsheet)
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new xlsx($spreadsheet);
        //$writer->save('php://output');
        $writer->save("./public/export/".$filename);
    }

    public static function get_header_design($number,&$row,$report_name = NULL,$sheet, $report_names = NULL,$is_required_default_title = NULL)
    {
        if($is_required_default_title != 'No'){
            $sheet->setCellValue(self::get_letter($number).$row, 'SR Chemical Industries LTD.');
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)->getFont()->setSize(18);
            $row++;
        }else{
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)->getFont()->setSize(18);

        }


        if($report_name){
            $sheet->setCellValue(self::get_letter($number).$row, $report_name);
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)->getFont()->setSize(14);

            $row++;
        }

        if(is_array($report_names) && !empty($report_names)){
            foreach ($report_names as $head){
                $sheet->setCellValue(self::get_letter($number).$row, $head);
                $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter(10).$row)->getStyle('A'.$row.':J'.$row)->getFont()->setSize(14);
                $row++;
            }
        }

    }





    /*
     *$number = column number Exm. 0=A,1=B,2=C etc
     * $row = Row number
     * $data = array() - all reports data
     * $mergeCells = maximum rowspan in reports header
     * $sheet = spreadsheet object
     * $additionalRowColumn = if show another column between geo map and sku column or if you show another row at top or bottom reports header
     * exm:
     *      - $additionalRowColumn = array(
     *                                  'addiColumn'=>array('Total Outlet','Visited Outlet'),
     *                                  'topRow'=>array('BCP'),
 *                                      'bottomRow'=>array('a','b'));
     */

    public static function get_column_title(&$number,&$row,$columns,$sheet)
    {
        foreach($columns as $column){
            $sheet->setCellValue(self::get_letter($number).$row, $column)->getStyle(self::get_letter($number).$row, $column)->getFont()->setBold(true);
            $number++;
        }
        $row = $row++;
    }

    public static function geo_map_excel(&$number,&$row,&$rowspan,$data,$mergeCells,$sheet)
    {
        foreach(parrentColumnTitleValue($data['view_report'],$mergeCells)['value'] as $pctv)
        {
            $number++;
            $sheet->setCellValue(self::get_letter($number).$row, ucfirst($pctv));
            $sheet->mergeCells(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)->getStyle(self::get_letter($number).$row.':'.self::get_letter($number).$rowspan)
                ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }
    }

    public static function get_letter($numeric_value)
    {
        $letter = array(
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
            "AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ",
            "BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ",
            "CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ",
            "DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ",
            "EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ",
            "FA","FB","FC","FD","FE","FF","FG","FH","FI","FJ","FK","FL","FM","FN","FO","FP","FQ","FR","FS","FT","FU","FV","FW","FX","FY","FZ",
            "GA","GB","GC","GD","GE","GF","GG","GH","GI","GJ","GK","GL","GM","GN","GO","GP","GQ","GR","GS","GT","GU","GV","GW","GX","GY","GZ",
            "HA","HB","HC","HD","HE","HF","HG","HH","HI","HJ","HK","HL","HM","HN","HO","HP","HQ","HR","HS","HT","HU","HV","HW","HX","HY","HZ"
        );
        return $letter[$numeric_value];
    }


    public static function getCustomCell($sheet,$row,$col,$val,$colspan=false,$rowspan=false,$font_size=11,$align='left',$bold=false){
        $row = isset($row)?$row:'1';
        $col = isset($col)?$col:'1';
        $sheet = isset($sheet)?$sheet:'Sheet 1';
        $val = isset($val)?$val:'';
        $font_size = isset($font_size)?$font_size:'11';
        $sheet->setCellValue(self::get_letter($col).$row, $val);
        if($colspan){
            $sheet->mergeCells(self::get_letter($col).$row.':'.self::get_letter($col+$colspan).$row)
                ->getStyle(self::get_letter($col).$row.':'.self::get_letter($col+$colspan).$row)
                ->getFont()->setSize($font_size)->setBold($bold);
            if($align=='center'){
                $sheet->mergeCells(self::get_letter($col).$row.':'.self::get_letter($col+$colspan).$row)
                    ->getStyle(self::get_letter($col).$row.':'.self::get_letter($col+$colspan).$row)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

        }
        if($rowspan){
            $sheet->mergeCells(self::get_letter($col).$row.':'.self::get_letter($col).($row+$rowspan))
                ->getStyle(self::get_letter($col).$row.':'.self::get_letter($col).($row+$rowspan))
                ->getFont()->setSize($font_size)->setBold($bold);

            if($align=='center'){
                $sheet->mergeCells(self::get_letter($col).$row.':'.self::get_letter($col).($row+$rowspan))
                    ->getStyle(self::get_letter($col).$row.':'.self::get_letter($col).($row+$rowspan))
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
    }

    public  static  function goToDelegationProcess($request){
        $post = $request->all();
        //debug($post,1);
        if($post['delegation_type'] == 'send_for_approval'){
            $result = \App\Http\Controllers\Delegation\DelegationProcess::delegationInitialize($request);
            //$result =  app('App\Http\Controllers\Delegation')->delegationInitialize($request);
        }else if($post['delegation_type'] == 'approval'){
            //$result = App\Http\Controllers\Delegation\delegationApprove($request);
        }else if($post['delegation_type'] == 'decline'){
            //$result = App\Http\Controllers\Delegation\delegationDeclineProcess($request);
        }
        return $result;
    }

//    public static function reference_details($data,$statementType,$drow,$sheet)
//    {
//
//    }


}


