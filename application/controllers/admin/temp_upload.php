<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/AdminController.php");
include_once(APPPATH."libraries/PHPExcel.php");

class temp_upload extends AdminController {
    function __construct()
    {
        parent::__construct();
        $this->_set_action();
        // $this->_set_action(array("view","delete"),"ITEM");
        $this->_set_title( 'Upload' );
        // $this->DATA->table = "mt_app_sessions";
        $this->folder_view = "config/";
        $this->prefix_view = strtolower($this->_getClass());
        // $this->load->model("mdl_session","M");
        $this->breadcrumb[] = array(
            "title"     => "Upload",
            "url"       => $this->own_link
        );

    }


    function index(){

        $this->_v($this->folder_view.$this->prefix_view,$data);
    }


    function upload(){
        $data = array();
        $data['err'] = true;
        $data['msg'] = '';
        $data['items'] = array();
        $count = 0;

        if(isset($_POST['thisAction']) && $_POST['thisAction'] == 'upload'){

            $upload_type = $_POST['upload_type'];
            $files = $_FILES['files'];
            if (!empty($files['name'])){
                $upload_path    = './assets/collections/tmp_files/';
                $filename       = $files['name'];
                $rand           = strtolower(changeEnUrl($filename)).'_'.convDatetoString(timestamp());
                $ext            = substr($filename, strpos($filename,'.'), strlen($filename)-1);
                $imgname        = $rand.$ext;
                $imgPath        = $upload_path.$imgname;
                if(isset($files) && $files['error'] == 0){
                    if(move_uploaded_file($files['tmp_name'], $imgPath)){

                        $objPHPExcel = PHPExcel_IOFactory::load($imgPath);
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $worksheetTitle     = $worksheet->getTitle();
                            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                            // $nrColumns = ord($highestColumn) - 64;

                            // debugCode($highestRow);
                            // debugCode($worksheetTitle);
                            if($upload_type == "ongkir"){
                                // for ($row = 1; $row <= $highestRow; $row++) {
                                //     $val = array();
                                //     $idx = 1;
                                //     for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                //         $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                //         if(strstr($cell->getValue(),'=')==true){
                                //             $val[$idx] = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                                //         } else {
                                //             $val[$idx] = $cell->getValue();
                                //         }
                                //         $idx += 1;
                                //     }

                                //     $data2 = array(
                                //         'temp_upload_date'  => convDateINtoEN($val[1])." 00:00:00",
                                //         'temp_upload_price' => convertRpToInt2($val[2])
                                //     );
                                //     $this->DATA->table="mt_temp_upload";
                                //     $a2 = $this->_save_master(
                                //         $data2,
                                //         array(
                                //             'temp_upload_id' => ''
                                //             ),
                                //         ''
                                //         );
                                // }

                                $data['err']  = false;
                                $data['msg'] .= 'Berhasil:<br>';
                            }
                        }
                    }
                } else {
                    $data['err']  = true;
                    $data['msg'] .= 'Error: ' . $files['error'] . '<br>';
                }
            }

            $upload_files = glob('./assets/collections/tmp_files/*');
            foreach($upload_files as $file){
                if(is_file($file))
                unlink($file);
            }

            redirect($this->own_link."?msg=".urlencode($data['msg'])."&type_msg=".($data['err']==true?'error':'success'));

        }
    }

}
