<?php
/**
 * Created by PhpStorm.
 * User: APSIS-M
 * Date: 5/28/2018
 * Time: 9:48 AM
 */

namespace App\Helpers;


class PdfHelper
{
    public static function exportPdf($view,$data)
    {
        $config = [
            'mode' => 'BN',
            "autoScriptToLang" => true,
            "autoLangToFont" => true,
        ];

        $mgl = (isset($data['margin']['mgl'])?$data['margin']['mgl']:20);
        $mgr = (isset($data['margin']['mgr'])?$data['margin']['mgr']:10);
        $mgt = (isset($data['margin']['mgt'])?$data['margin']['mgt']:30);
        $mgb = (isset($data['margin']['mgb'])?$data['margin']['mgb']:20);
        $mgh = (isset($data['margin']['mgh'])?$data['margin']['mgh']:5);
        $mgf = (isset($data['margin']['mgf'])?$data['margin']['mgf']:10);

        $my_header = (isset($data['my_header'])?$data['my_header']:self::defaultHeader($data));
        $my_footer = (isset($data['my_footer'])?$data['my_footer']:self::defaultFooter());

        $orientation = (isset($data['orientation'])?$data['orientation']:'L');

        $paper_size = (isset($data['paper_size'])?$data['paper_size']:'A4');

        $mpdf = new \Mpdf\Mpdf($config);
        if(isset($data['report_title'])){
            $mpdf->SetTitle($data['report_title']);
        }


        //$mpdf->useOddEven = 1;
        $mpdf->defHeaderByName('myHeader', $my_header );

        $mpdf->DefFooterByName('myFooter', $my_footer );

        $mpdf->AddPage(
            $orientation,
            'NEXT-ODD',
            '',
            '',
            '',
            $mgl,
            $mgr,
            $mgt,
            $mgb,
            $mgh,
            $mgf,
            'myHeader',
            'myHeader',
            'myFooter',
            'myFooter',
            1,
            1,
            1,
            1,
            0,
            $paper_size
        );

//        $mpdf->SetHTMLFooter('<h5 style="text-align: center">© '.date('Y').' SR Chemical Limited</h5>');


        $html = view($view,$data);
//        $stylesheet = 'public/css/pdf.css';
        $stylesheet = file_get_contents('public/css/pdf.css');
        $stylesheet = file_get_contents('public/css/bootstrap.min.css');
        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML('<h4 style="text-align: center; text-decoration: underline; margin-bottom: 10px">'.(isset($data['report_title'])?$data['report_title']:'').'</h4>');
        $mpdf->WriteHTML($html);
        $filename = (isset($data['filename'])?$data['filename']:'pdf'). date("Y-m-d-H-i-s").'.pdf';
        if(isset($data['download']) && $data['download']==true){
            $mpdf->Output($filename, 'D'); //for download
        } else{
            $mpdf->Output($filename, "I"); // open in browser
        }
    }


    public static function defaultHeader($data){
        return $default_header = array (
            'L' => array (
                'content'=>'<img class="img-responsive" style="height:60px; width:140px; margin-bottom: 5px" src="public/img/srcil_logo2.png" alt=""/>'
            ),
            'R' => array (
                'content'=>'<i>Print Date : '.date("d-M-Y").'</i>',
                'font-size'=>8
            ),
            'C' => array (
                'content' => '<h2 style="font-size: 16px;">SR CHEMICAL INDUSTRIES LTD</h2>
                                  <h5 style="font-size: 14px">' . (isset($data['branch_address'])?$data['branch_address']:'') . '</h5>',
                'font-style' => 'B',
                'font-family' => 'serif',
            ),
            'line' => 1,
        );
    }

    public static function defaultFooter(){
        return array (
            'L' => array (
                'content'=>'Developed by: <a target="_blank" href="http://apsissolutions.com">apsissolutions.com</a>',
                'font-size'=>7,
                'font-family' => 'serif',
            ),
            'R' => array (
                'content'=>'<i>Page {PAGENO} of {nb}</i>',
                'font-size'=>8
            ),
            'C' => array (
                'content' => '<h5 style="text-align: center; font-size: 12px">© '.date('Y').' SR Chemical Limited</h5>',
                'font-style' => 'B',
                'font-family' => 'serif',
            ),
            'line' => 1,
        );
    }
}
