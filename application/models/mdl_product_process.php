<?php
class mdl_product_process extends CI_Model{

	var $tabel = 'mt_product';

	function __construct(){
		parent::__construct();

	}

    function save_update_stock_barcode($p=array()){
        $data   = array();
        $error  = false;
        $msg    = "";
        $result = "";

        $user_id     = $p['user_id'];
        $store_id    = $p['store_id'];
        $thisAction      = $p['thisAction'];
        $product_barcode = $p['product_barcode'];

        $data['result_msg'] = array();
        if($product_barcode != ""){
            $arrNoBarcode = explode("-", $product_barcode);
            $m = $this->db->get_where("mt_product",array(
                "product_code"  => strtoupper($arrNoBarcode[0]),
                "store_id"      => $store_id
            ),1,0)->row();
            if(count($m) > 0){
                $detail = $this->db->get_where("mt_product_detail",array(
                    'product_id'    => $m->product_id
                ),1,0)->row();
                if(count($detail) > 0){
                    $product_id = $m->product_id;
                    if(count($arrNoBarcode) == 1){
                        if($detail->product_stock_detail == '' || $detail->product_stock_detail == '[]'){
                            $title_id = "";
                            $new_qty  = 0;
                            if($thisAction == 'minus'){
                                if($detail->product_stock > 0){
                                    $title_id = "15";  // Pengurangan Stok Manual
                                    $new_qty  = ($detail->product_stock - 1);
                                    $new_qty  = ($new_qty > 0?$new_qty:0);
                                    $error    = false;
                                    $msg      = $m->product_name." stok berhasil dikurangi. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
                                    $data['result_msg'][] = $msg;
                                    if($new_qty==0){
                                        sendProductNotif(array(
                                            'user_id'       => $user_id,
                                            'store_id'      => $store_id,
                                            'product_id'    => $product_id,
                                            'product_item'  => NULL,
                                            'product_qty'   => $new_qty,
                                            'notif_title'   => "Produk sudah habis",
                                            'notif_desc'    => "",
                                            'notif_status'  => 1,
                                            'notif_notify'  => 3
                                        ));
                                    }
                                }
                            } else if($thisAction == 'plus'){
                                $title_id = "14"; // Penambahan Stok Manual
                                $new_qty  = ($detail->product_stock + 1);
                                if($detail->product_stock == 0){
                                    sendProductNotif(array(
                                        'user_id'       => $user_id,
                                        'store_id'      => $store_id,
                                        'product_id'    => $product_id,
                                        'product_item'  => NULL,
                                        'product_qty'   => $new_qty,
                                        'notif_title'   => "Produk restock lagi",
                                        'notif_desc'    => "",
                                        'notif_status'  => 1,
                                        'notif_notify'  => 3
                                    ));
                                }
                                $error    = false;
                                $msg      = $m->product_name." stok berhasil ditambahkan. Stok saat ini ada: ".$new_qty;
                                $data['result_msg'][] = $msg;
                            }
                            $total_qty = $new_qty;
                            if($error == false){
                                $dataDetail = array(
                                    'product_status_id'         => ($total_qty > 0?1:3),
                                    'product_stock'             => $total_qty
                                );
                                $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                                set_last_date_product_setup();
                                writeLog(array(
                                    'log_user_type'     => "1", // Admin
                                    'log_user_id'       => $user_id,
                                    'log_role'          => NULL,
                                    'log_type'          => "2", // Produk
                                    'log_detail_id'     => $product_id,
                                    'log_detail_item'   => NULL,
                                    'log_detail_qty'    => 1,
                                    'log_title_id'      => $title_id,
                                    'log_desc'          => NULL,
                                    'log_status'        => "0"
                                ));
                            }
                        } else {
                            $error = true;
                            $msg   = "Kode Barcode harus diakhiri dengan No Variasi Produk.";
                        }
                    } else if(count($arrNoBarcode) == 2){
                        $title_id  = "";
                        $log_item  = "";
                        $new_qty   = 0;
                        $status    = 0;
                        $total_qty = 0;
                        $found_sold = false;
                        $found_restock = false;
                        $found_variasi = false;
                        $arr_stock = array();
                        $arr_item  = array();
                        $product_stock_detail = json_decode($detail->product_stock_detail);
                        foreach ($product_stock_detail as $key3 => $value3) {
                            $new_qty = $value3->qty;
                            $status  = $value3->status;
                            if($value3->id == $arrNoBarcode[1]){
                                $found_variasi = true;
                                if($thisAction == 'minus'){
                                    if($value3->qty > 0){
                                        $title_id = "15"; // Pengurangan Stok Manual
                                        $new_qty  = ($value3->qty - 1);
                                        $new_qty  = ($new_qty > 0?$new_qty:0);
                                        $error    = false;
                                        $msg      = $m->product_name." ".$value3->name." stok berhasil dikurangi. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
                                        $data['result_msg'][] = $msg;
                                        if($new_qty==0){
                                            $found_sold = true;
                                        }
                                    } else {
                                        $error   = true;
                                        $msg     = $m->product_name." ".$value3->name." stok sudah habis.";
                                    }
                                } else if($thisAction == 'plus'){
                                    $title_id = "14"; // Penambahan Stok Manual
                                    $new_qty  = ($value3->qty + 1);
                                    $error    = false;
                                    $msg      = $m->product_name." ".$value3->name." stok berhasil ditambahkan. Stok saat ini ada: ".$new_qty;
                                    $data['result_msg'][] = $msg;
                                    if($value3->qty==0){
                                        $found_restock = true;
                                    }
                                }
                                $status  = ($new_qty > 0?'1':'2');

                                $arr_item[] = array('id'        => $value3->id,
                                                    'name'      => $value3->name,
                                                    'qty_old'   => $value3->qty,
                                                    'qty_new'   => $new_qty,
                                                    'status'    => $status
                                                );
                            }
                            $arr_stock[] = array('id'       => $value3->id,
                                                 'name'     => $value3->name,
                                                 'color'    => $value3->color,
                                                 'qty'      => $new_qty,
                                                 'status'   => $status
                                              );
                            $total_qty += $new_qty;

                            if($found_sold){
                                sendProductNotif(array(
                                    'user_id'       => $user_id,
                                    'store_id'      => $store_id,
                                    'product_id'    => $product_id,
                                    'product_item'  => json_encode($arr_item),
                                    'product_qty'   => $total_qty,
                                    'notif_title'   => "Variasi produk sudah habis",
                                    'notif_desc'    => "",
                                    'notif_status'  => 1,
                                    'notif_notify'  => 3
                                ));
                            }
                            if($found_restock){
                                sendProductNotif(array(
                                    'user_id'       => $user_id,
                                    'store_id'      => $store_id,
                                    'product_id'    => $product_id,
                                    'product_item'  => json_encode($arr_item),
                                    'product_qty'   => $total_qty,
                                    'notif_title'   => "Variasi produk restock kembali",
                                    'notif_desc'    => "",
                                    'notif_status'  => 1,
                                    'notif_notify'  => 3
                                ));
                            }
                        }

                        if($found_variasi == false){
                            $error   = true;
                            $msg     = $m->product_name." variasi ".$arrNoBarcode[1]." tidak ditemukan.";
                        }

                        if($error == false){
                            $dataDetail = array(
                                'product_status_id'         => ($total_qty > 0?1:3),
                                'product_stock'             => $total_qty,
                                'product_stock_detail'      => json_encode($arr_stock)
                            );
                            $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

                            set_last_date_product_setup();
                            writeLog(array(
                                'log_user_type'     => "1", // Admin
                                'log_user_id'       => $user_id,
                                'log_role'          => NULL,
                                'log_type'          => "2", // Produk
                                'log_detail_id'     => $product_id,
                                'log_detail_item'   => json_encode($arr_item),
                                'log_detail_qty'    => 1,
                                'log_title_id'      => $title_id,
                                'log_desc'          => NULL,
                                'log_status'        => "0"
                            ));
                        } else {
                            $msg   = ($msg!=""?$msg:"No Variasi tidak ditemukan.");
                        }
                    }
                } else {
                    $error = true;
                    $msg   = "Produk Detail tidak ditemukan.";
                }
            } else {
                $error = true;
                $msg   = "Kode Barcode tidak ditemukan.";
            }
        } else {
            $error = true;
            $msg   = "Kode Barcode belum diinput.";
        }

        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "result_msg" => $data['result_msg'],
                "total"  => 1
            );
    }


}