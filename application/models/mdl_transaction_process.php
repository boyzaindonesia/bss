<?php
class mdl_transaction_process extends CI_Model{

    var $tabel = 'mt_orders';

    function __construct(){
        parent::__construct();
    }



    function save_confirm_orders($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id        = $p['user_id'];
        $store_id       = $p['store_id'];
        $orders_id      = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'store_id'       => $store_id,
            'orders_id'      => $orders_id,
            'orders_istrash' => 0
        ),1,0)->row();
        if(count($r) > 0){
            if($r->orders_status > 3){
                $error  = true;
                $msg[]  = 'Pesanan '.$r->orders_source_invoice.' sudah diproses.';
            } else {
                $data1 = array(
                    'orders_status' => 4,
                    'date_notify'   => timestamp()
                );
                $data2 = array(
                    'orders_shipping_status' => 4,
                    'date_notify'            => timestamp()
                );

                insert_orders_timestamp($orders_id, 4);
                $isPickup = isPickup($r->orders_courier_id);
                if($isPickup){
                    $data1['orders_status'] = 5;
                    $data2['orders_shipping_status']   = 5;
                    $data2['orders_shipping_price']    = $r->orders_price_shipping;
                    $data2['orders_shipping_date']     = timestamp();
                    insert_orders_timestamp($orders_id, 5);

                    $isPriceDebetCourier = isPriceDebetCourier($r->orders_source_id, $r->orders_courier_id);
                    if($isPriceDebetCourier){
                        $data1['orders_price_debet_ship']  = $r->orders_price_shipping;
                        $data1['orders_price_grand_total'] = $r->orders_price_product;
                        $data3 = array(
                            'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                            'date_notify'                => timestamp()
                        );
                        $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                    }
                }

                $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));
                $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

                $error = false;
                // $data['href']   = $this->own_link.'/confirm_shipping';
            }
        } else {
            $error = true;
            $msg[] = 'Pesanan tidak ditemukan.';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function save_process_shipping($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id         = $p['user_id'];
        $store_id        = $p['store_id'];
        $orders_id       = $p['orders_id'];
        $chkPriceDebetCourier  = $p['chkPriceDebetCourier'];
        $orders_shipping_price = $p['orders_shipping_price'];
        $orders_shipping_resi  = $p['orders_shipping_resi'];

        $r = $this->db->get_where("mt_orders",array(
            'store_id'   => $store_id,
            'orders_id'  => $orders_id
        ),1,0)->row();
        if(count($r) > 0){
            $orders_price_product           = $r->orders_price_product;
            $orders_price_shipping          = $r->orders_price_shipping;
            $orders_price_insurance         = $r->orders_price_insurance;
            $orders_voucher_price           = $r->orders_voucher_price;
            $orders_price_grand_total       = $r->orders_price_grand_total;

            $data1 = array(
                'orders_status'          => 5,
                'orders_claim_status'    => 0,
                'date_notify'            => timestamp()
            );
            if($orders_price_shipping > 0){
                if($orders_shipping_price > $orders_price_shipping){
                    $data1['orders_claim_status'] = 1;
                    $data1['orders_claim_price']  = ($orders_shipping_price - $orders_price_shipping);
                }
            } else {
                $data1['orders_price_shipping']    = $orders_shipping_price;
                $data1['orders_price_grand_total'] = ($orders_price_product + $orders_shipping_price + $orders_price_insurance) - $orders_voucher_price;
                if($chkPriceDebetCourier == 1){
                    $data1['orders_price_grand_total'] = $orders_price_product - $orders_voucher_price;
                }

                $data3 = array(
                    'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                    'date_notify'                => timestamp()
                );
                $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
            }

            $data1['orders_price_debet_ship'] = 0;
            if($chkPriceDebetCourier == 1){
                $data1['orders_price_debet_ship']  = $orders_shipping_price;
                $data1['orders_price_grand_total'] = $orders_price_product - $orders_voucher_price;
            }

            $isPriceDebetCourier = isPriceDebetCourier($r->orders_source_id, $r->orders_courier_id);
            if($isPriceDebetCourier){
                if($chkPriceDebetCourier == 0){
                    $data1['orders_price_debet_ship']  = 0;
                    $data1['orders_price_grand_total'] = ($orders_price_product + $orders_shipping_price + $orders_price_insurance) - $orders_voucher_price;
                    $data3 = array(
                        'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                        'date_notify'                => timestamp()
                    );
                    $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                }
            } else {
                if($chkPriceDebetCourier == 1){
                    $data1['orders_price_debet_ship']  = $orders_shipping_price;
                    $data1['orders_price_grand_total'] = $orders_price_product - $orders_voucher_price;
                    $data3 = array(
                        'orders_payment_grand_total' => $data1['orders_price_grand_total'],
                        'date_notify'                => timestamp()
                    );
                    $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));
                }
            }

            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));

            $data2 = array(
                'orders_shipping_status' => 5,
                'orders_shipping_price'  => $orders_shipping_price,
                'date_notify'            => timestamp()
            );
            if($orders_shipping_resi != ""){
                $data2['orders_shipping_resi'] = strtoupper($orders_shipping_resi);
            }
            $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

            insert_orders_timestamp($orders_id, 5);

            $error = false;
            $msg   = "Berhasil simpan data...";
        } else {
            $error = true;
            $msg   = 'Pesanan tidak ditemukan ...';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function save_payment($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id        = $p['user_id'];
        $store_id       = $p['store_id'];
        $orders_id      = $p['orders_id'];
        $orders_price_grand_total = $p['orders_price_grand_total'];
        $orders_payment_price     = $p['orders_payment_price'];
        $orders_voucher_price     = $p['orders_voucher_price'];
        $orders_price_debet_ship  = $p['orders_price_debet_ship'];
        $orders_claim_price       = $p['orders_claim_price'];
        $orders_price_return      = $p['orders_price_return'];
        $r = $this->db->get_where("mt_orders",array(
            'store_id'   => $store_id,
            'orders_id'  => $orders_id
        ),1,0)->row();
        if(count($r) > 0){
            $data1 = array(
                'orders_status'          => 8,
                'orders_claim_status'    => 0,
                'date_notify'            => timestamp()
            );

            if($orders_price_grand_total > 0){
                $data1['orders_price_grand_total']   = $orders_price_grand_total;
                $data3['orders_payment_grand_total'] = $orders_price_grand_total;
            }
            if($orders_price_debet_ship > 0){
                $data1['orders_price_debet_ship'] = $orders_price_debet_ship;
            }
            if($orders_voucher_price > 0){
                $data1['orders_voucher_price'] = $orders_voucher_price;
                $data1['orders_voucher_code']  = NULL;
            }
            if($orders_claim_price > 0){
                $data1['orders_claim_status'] = 1;
                $data1['orders_claim_price']  = $orders_claim_price;
                $data1['orders_claim_date']   = timestamp();
            } else if($r->orders_claim_status > 0){
                $data1['orders_claim_status'] = 3;
                $data1['orders_claim_date']   = timestamp();
            }
            if($orders_price_return > 0){
                $data1['orders_price_return'] = $orders_price_return;
            }

            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));

            $data2 = array(
                'orders_shipping_status' => 8,
                'date_notify'            => timestamp()
            );
            $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

            $data3 = array(
                'orders_payment_status'      => 3,
                'orders_payment_price'       => $orders_payment_price,
                'date_notify'                => timestamp()
            );
            $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));

            insert_orders_timestamp($orders_id, 8);

            $error = false;
            $msg   = "Berhasil simpan data...";
        } else {
            $error = true;
            $msg   = 'Pesanan tidak ditemukan ...';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function save_verifikasi_payment($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id        = $p['user_id'];
        $store_id       = $p['store_id'];
        $orders_id      = $p['orders_id'];
        $orders_price_grand_total= $p['orders_price_grand_total'];
        $orders_payment_price    = $p['orders_payment_price'];
        $orders_voucher_price    = $p['orders_voucher_price'];
        $orders_cashback_seller  = $p['orders_cashback_seller'];
        $orders_price_debet_ship = $p['orders_price_debet_ship'];
        $orders_claim_price      = $p['orders_claim_price'];
        $orders_price_return     = $p['orders_price_return'];
        $orders_payment_date     = $p['orders_payment_date'];
        $r = $this->db->get_where("mt_orders",array(
            'store_id'   => $store_id,
            'orders_id'  => $orders_id
        ),1,0)->row();
        if(count($r) > 0){
            $data1 = array(
                'orders_status'          => 8,
                'orders_claim_status'    => 0,
                'date_notify'            => timestamp()
            );
            if($orders_price_grand_total > 0){
                $data1['orders_price_grand_total']   = $orders_price_grand_total;
                $data3['orders_payment_grand_total'] = $orders_price_grand_total;
            }
            if($orders_cashback_seller > 0){
                $data1['orders_cashback_seller'] = $orders_cashback_seller;
            }
            if($orders_price_debet_ship > 0){
                $data1['orders_price_debet_ship'] = $orders_price_debet_ship;
            }
            if($orders_voucher_price > 0){
                $data1['orders_voucher_price'] = $orders_voucher_price;
                $data1['orders_voucher_code']  = NULL;
            }
            if($orders_claim_price > 0){
                $data1['orders_claim_status'] = 1;
                $data1['orders_claim_price']  = $orders_claim_price;
                $data1['orders_claim_date']   = timestamp();
            }
            if($orders_price_return > 0){
                $data1['orders_price_return'] = $orders_price_return;
            }

            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));

            $data2 = array(
                'orders_shipping_status' => 8,
                'date_notify'            => timestamp()
            );
            $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));

            $data3 = array(
                'orders_payment_status'      => 3,
                'orders_payment_price'       => $orders_payment_price,
                'date_notify'                => $orders_payment_date
            );
            $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));

            if($r->orders_status == 4){
                insert_orders_timestamp($orders_id, 5);
            }
            insert_orders_timestamp($orders_id, 8);

            $mp = (isset($this->jCfg['marketplace_payment'])?$this->jCfg['marketplace_payment']:array());
            foreach ($mp as $key => $val) {
                if($val->mp_source_invoice == $r->orders_source_invoice){
                    unset($this->jCfg['marketplace_payment'][$key]);
                    $this->_releaseSession();
                }
            }

            $error = false;
            $msg   = 'Berhasil simpan pembayaran...';
        } else {
            $error = true;
            $msg   = 'Pesanan tidak ditemukan ...';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function save_claim($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id        = $p['user_id'];
        $store_id       = $p['store_id'];
        $orders_id      = $p['orders_id'];
        $claim_status   = $p['claim_status'];
        $claim_price    = $p['claim_price'];
        $r = $this->db->get_where("mt_orders",array(
            'store_id'   => $store_id,
            'orders_id'  => $orders_id
        ),1,0)->row();
        if(count($r) > 0){
            if($claim_price != ""){
                $data1['orders_claim_price']  = convertRpToInt($claim_price);
            } else if($r->orders_claim_price == 0){
                $data1['orders_claim_price']  = $r->orders_source_price;
            }

            if($claim_status == 3){
                $data1 = array(
                    'orders_status' => 8,
                    'date_notify'   => timestamp()
                );
                $data2 = array(
                    'orders_shipping_status' => 8,
                    'date_notify'            => timestamp()
                );
                $this->db->update("mt_orders_shipping",$data2,array("orders_id"=>$orders_id));
                $data3 = array(
                    'orders_payment_status'      => 3,
                    'orders_payment_price'       => $data1['orders_claim_price'],
                    'orders_payment_grand_total' => $data1['orders_claim_price'],
                    'date_notify'                => timestamp()
                );
                $this->db->update("mt_orders_payment",$data3,array("orders_id"=>$orders_id));

                insert_orders_timestamp($orders_id, 8);
            }

            $data1['orders_claim_status'] = $claim_status;
            $data1['orders_claim_date']   = timestamp();
            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));

            $error = false;
            $msg   = 'Berhasil simpan claim...';
        } else {
            $error = true;
            $msg   = 'Pesanan tidak ditemukan ...';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function save_canceled($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id        = $p['user_id'];
        $store_id       = $p['store_id'];
        $orders_id      = $p['orders_id'];
        $orders_status  = $p['orders_status'];

        $r = $this->db->get_where("mt_orders",array(
            'store_id'   => $store_id,
            'orders_id'  => $orders_id
        ),1,0)->row();
        if(count($r) > 0){
            $m1 = $this->db->order_by("orders_detail_id", "asc")->get_where("mt_orders_detail",array(
                "orders_id"            => $orders_id,
                "orders_detail_status" => 1
            ))->result();
            if(count($m1) > 0){
                foreach ($m1 as $key1 => $val1) {
                    $product_id = $val1->product_id;
                    $orders_qty = $val1->orders_detail_qty;
                    $tempItem = array();
                    if($val1->orders_detail_item != ""){
                        $orders_detail_item = json_decode($val1->orders_detail_item);
                        foreach ($orders_detail_item as $key2 => $val2){
                            $tempItem[$val2->id] = $val2->qty;
                        }
                    }

                    $m2 = $this->db->get_where("mt_product_detail",array(
                        "product_id" => $product_id
                    ),1,0)->row();
                    if(count($m2) > 0){
                        $product_stock = $m2->product_stock;
                        $new_stock   = $orders_qty + $product_stock;
                        $data_detail = array(
                            'product_stock'         => $new_stock,
                            'product_stock_detail'  => NULL
                        );

                        if($m2->product_stock_detail != "" && (count($tempItem) > 0) ){
                            $arr_stock  = array();
                            $product_stock_detail = json_decode($m2->product_stock_detail);
                            foreach ($product_stock_detail as $key2 => $val2){
                                $total_stock = $val2->qty;
                                if(array_key_exists($val2->id, $tempItem)){
                                    $total_stock = $val2->qty + $tempItem[$val2->id];
                                    if($val2->qty <= 0){
                                        sendProductNotif(array(
                                            'user_id'       => $user_id,
                                            'store_id'      => $store_id,
                                            'product_id'    => $product_id,
                                            'product_item'  => $val2->id,
                                            'product_qty'   => $total_stock,
                                            'notif_title'   => "Produk item update stok",
                                            'notif_desc'    => "",
                                            'notif_status'  => 1,
                                            'notif_notify'  => 3
                                        ));
                                    }
                                }
                                $arr_stock[] = array('id'       => $val2->id,
                                                     'name'     => $val2->name,
                                                     'color'    => $val2->color,
                                                     'qty'      => $total_stock,
                                                     'status'   => ($total_stock>0?1:2)
                                                  );
                            }
                            $data_detail['product_stock_detail'] = json_encode($arr_stock);
                        }
                        $this->db->update("mt_product_detail",$data_detail,array("product_detail_id"=>$m2->product_detail_id));
                    }

                    $this->db->update("mt_orders_detail",array("orders_detail_status"=>0),array("orders_detail_id"=>$val1->orders_detail_id));
                }
            }

            $data1 = array(
                'orders_print'           => 1,
                'orders_status'          => $orders_status,
                'date_notify'            => timestamp()
            );

            $this->db->update("mt_orders",$data1,array("orders_id"=>$orders_id));

            insert_orders_timestamp($orders_id, $orders_status);
            set_last_date_product_setup();

            $error  = false;
            $msg    = 'Berhasil cancel pesanan ...';
            if($orders_status == 11){
                $msg  = 'Berhasil return pesanan ...';
            }
        } else {
            $error  = true;
            $msg    = 'Pesanan tidak ditemukan ...';
        }

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

}