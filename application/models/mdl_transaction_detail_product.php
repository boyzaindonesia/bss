<?php
class mdl_transaction_detail_product extends CI_Model{

    var $tabel = 'mt_orders';

    function __construct(){
        parent::__construct();
    }

    function data_get_detail_product($p=array()){
        $count_list = 0;
        $count_item = 0;
        $cart_list  = "";

        $user_id    = $p['user_id'];
        $store_id   = $p['store_id'];
        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();
        if(count($r)){
            $detail   = get_orders_detail($r->orders_id);
            $shipping = get_detail_orders_shipping($r->orders_id);
            foreach ($detail as $key2 => $val2) {
                $count_list += 1;

                $varian = "";
                if($val2->orders_detail_item != "" && $val2->orders_detail_item != "[]"){
                    $varian .= '<div class="relative">';
                    $varian .= '<p class="no-margin no-padding">Varian:</p>';
                    $varian .= '<ul class="product-list-item-stock clearfix mb-5">';
                    $orders_detail_item = json_decode($val2->orders_detail_item);
                    foreach ($orders_detail_item as $key3 => $val3) {
                        $varian .= '<li><p>'.$val3->name.' = '.$val3->qty.'</p></li>';
                        $count_item += $val3->qty;
                    }
                    $varian .= '</ul>';
                    $varian .= '</div>';
                } else {
                    $count_item += 1;
                }

                $cart_list .= '
                <div class="cart-list-item">
                    <div class="cart-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$val2->product_images).');"></div>
                    <div class="cart-list-content">
                        <h6 class="no-margin no-padding"><strong>'.$val2->product_code.'</strong></h6>
                        <p class="no-margin no-padding"><strong>'.$val2->product_name.'</strong></p>
                        <h6 class="no-margin no-padding mb-5">'.convertRp($val2->orders_detail_price).' x '.$val2->orders_detail_qty.' = <strong>'.convertRp(($val2->orders_detail_price * $val2->orders_detail_qty)).'</strong></h6>
                        '.$varian.'
                    </div>
                </div>
                ';
            }
        }

        $result = '
            <div class="form-horizontal">
                <legend>Daftar Produk</legend>
                <div class="relative cart-list cart-list-small no-margin mb-5">
                    '.$cart_list.'
                </div>
                <div class="relative">
                    <h6 class="no-margin no-padding"><strong>'.($cart_list!=""?'Total Produk: '.$count_list.', Jumlah Item: '.$count_item.'':'Belum ada produk...').'</strong></h6>
                </div>
            </div>
        ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

    function data_transaction_detail_product($p=array()){
        $count_list = 0;
        $count_item = 0;
        $cart_list  = "";

        $user_id    = $p['user_id'];
        $store_id   = $p['store_id'];
        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();
        if(count($r)){
            $detail   = get_orders_detail($r->orders_id);
            $shipping = get_detail_orders_shipping($r->orders_id);
            foreach ($detail as $key2 => $val2) {
                $count_list += 1;

                $varian = "";
                if($val2->orders_detail_item != "" && $val2->orders_detail_item != "[]"){
                    $varian .= '<div class="relative">';
                    $varian .= '<p class="no-margin no-padding">Varian:</p>';
                    $varian .= '<ul class="product-list-item-stock clearfix mb-5">';
                    $orders_detail_item = json_decode($val2->orders_detail_item);
                    foreach ($orders_detail_item as $key3 => $val3) {
                        $varian .= '<li><p>'.$val3->name.' = '.$val3->qty.'</p></li>';
                        $count_item += $val3->qty;
                    }
                    $varian .= '</ul>';
                    $varian .= '</div>';
                } else {
                    $count_item += 1;
                }

                $cart_list .= '
                <div class="cart-list-item">
                    <div class="cart-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$val2->product_images).');"></div>
                    <div class="cart-list-content">
                        <h6 class="no-margin no-padding"><strong>'.$val2->product_code.'</strong></h6>
                        <p class="no-margin no-padding"><strong>'.$val2->product_name.'</strong></p>
                        <h6 class="no-margin no-padding mb-5">'.convertRp($val2->orders_detail_price).' x '.$val2->orders_detail_qty.' = <strong>'.convertRp(($val2->orders_detail_price * $val2->orders_detail_qty)).'</strong></h6>
                        '.$varian.'
                    </div>
                </div>
                ';
            }
        }

        $result = '
        <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-horizontal">
                <legend>Daftar Produk "'.$shipping->orders_shipping_name.'"</legend>
                <div class="relative cart-list cart-list-small no-margin mb-5">
                    '.$cart_list.'
                </div>
                <div class="relative">
                    <h6 class="no-margin no-padding"><strong>'.($cart_list!=""?'Total Produk: '.$count_list.', Jumlah Item: '.$count_item.'':'Belum ada produk...').'</strong></h6>
                </div>
                <div class="form-group form-action mb-0">
                    <div class="col-sm-12 clearfix" style="min-height:34px;">
                        <button type="button" class="btn btn-primary btn-add-product" data-id="'.$r->orders_id.'">Tambah Produk</button>'.($cart_list!=""?'
                        <button type="button" class="btn btn-danger btn-delete-product" data-id="'.$r->orders_id.'">Hapus Produk</button>':'').'
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </div>
            </div>
        </form>
        ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

    function data_transaction_add_detail_product($p=array()){
        $user_id    = $p['user_id'];
        $store_id   = $p['store_id'];
        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();
        if(count($r)){
            $detail   = get_orders_detail($r->orders_id);
            $shipping = get_detail_orders_shipping($r->orders_id);
        }

        $result = '
            <form id="form_save_add_detail_product" class="form_save_add_detail_product" data-id="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                <legend>Tambah Produk "'.$shipping->orders_shipping_name.'"</legend>
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="text" id="product_barcode" name="product_barcode" value="" class="form-control text-uppercase" style="font-size:30px;line-height:36px;height:inherit;" maxlength="50" placeholder="Kode Barcode">
                        </div>
                        <label class="col-sm-3" style="padding-left:0px;"><button type="button" class="btn btn-primary btn-find-barcode">Cari & Tambahkan</button></label>
                    </div>
                </div>
                <div id="list_product"></div>
                <div class="form-group form-action mb-0" style="min-height:34px;">
                    <input type="hidden" name="orders_id" value="'.$r->orders_id.'" />
                    <input type="hidden" name="thisAction" value="save" />
                    <button type="button" class="btn btn-danger btn-delete-product" data-id="'.$r->orders_id.'">Hapus Produk</button>
                    <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                </div>
            </form>
            ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

    function data_transaction_save_detail_product($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id         = $p['user_id'];
        $store_id        = $p['store_id'];
        $orders_id       = $p['orders_id'];
        $product_barcode = $p['product_barcode'];
        if($orders_id != "" && $product_barcode != ""){
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
                    $item_id    = "";
                    $item_name  = "";
                    if(count($arrNoBarcode) == 1){
                        if($detail->product_stock_detail == '' || $detail->product_stock_detail == '[]'){
                            $title_id = "";
                            $new_qty  = 0;
                            if($detail->product_stock > 0){
                                $title_id = "11";  // Pengurangan Stok Otomatis
                                $new_qty  = ($detail->product_stock - 1);
                                $new_qty  = ($new_qty > 0?$new_qty:0);
                                $error    = false;
                                $msg      = $m->product_name." berhasil ditambahkan. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
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

                            $total_qty = $new_qty;
                            if($error == false){
                                $dataDetail = array(
                                    'product_status_id'         => ($total_qty > 0?1:3),
                                    'product_stock'             => $total_qty
                                );
                                $this->db->update("mt_product_detail",$dataDetail,array("product_detail_id"=>$detail->product_detail_id));

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
                                $item_id    = $value3->id;
                                $item_name  = $value3->name;
                                if($value3->qty > 0){
                                    $title_id = "11"; // Pengurangan Stok Otomatis
                                    $new_qty  = ($value3->qty - 1);
                                    $new_qty  = ($new_qty > 0?$new_qty:0);
                                    $error    = false;
                                    $msg      = $m->product_name." ".$value3->name." berhasil ditambahkan. Stok saat ini ".($new_qty>0?"ada: ".$new_qty.".":"sudah habis.");
                                    $data['result_msg'][] = $msg;
                                    if($new_qty==0){
                                        $found_sold = true;
                                    }
                                } else {
                                    $error   = true;
                                    $msg     = $m->product_name." ".$value3->name." stok sudah habis.";
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

                    if($error == false){
                        $totalBuy   = 0;
                        $totalPrice = 0;
                        $product = $this->db->get_where("mt_product",array(
                            'product_id'    => $product_id
                        ),1,0)->row();
                        if(count($product) > 0){

                            $detail_qty         = 1;
                            $orders_detail_id   = NULL;
                            $orders_detail_item = NULL;
                            $orders_detail = $this->db->get_where("mt_orders_detail",array(
                                'product_id'   => $product_id,
                                'orders_id'    => $orders_id
                            ),1,0)->row();
                            if(count($orders_detail) > 0){
                                $detail_qty        += $orders_detail->orders_detail_qty;
                                $orders_detail_id   = $orders_detail->orders_detail_id;
                                $orders_detail_item = $orders_detail->orders_detail_item;
                            }

                            $product_detail = $this->db->get_where("mt_product_detail",array(
                                'product_id'    => $product_id
                            ),1,0)->row();

                            $detail_weight = $product_detail->product_weight;
                            $totalBuy      = $totalBuy + ($product_detail->product_price_buy * $detail_qty);

                            $detail_price = $product_detail->product_price_sale;
                            if($product_detail->product_price_discount > 0){
                                $detail_price = $product_detail->product_price_discount;
                            } else {
                                if($product_detail->product_price_grosir != ''){
                                    $product_price_grosir = json_decode($product_detail->product_price_grosir);
                                    foreach ($product_price_grosir as $key => $value){
                                        if($value->qty <= $detail_qty){
                                            $detail_price = $value->price;
                                        }
                                    }
                                }
                            }
                            $totalPrice    = $totalPrice + ($detail_price * $detail_qty);

                            if($item_id != ""){
                                $found_detail = false;
                                $arr_detail = array();
                                $item_stock = 0;
                                $orders_detail_item = json_decode($orders_detail_item);
                                foreach ($orders_detail_item as $key2 => $val2){
                                    if($val2->id == $item_id){
                                        $item_stock = ($val2->qty + 1);
                                        $arr_detail[] = array('id'      => $val2->id,
                                                            'name'      => $val2->name,
                                                            'qty'       => $item_stock
                                                        );
                                        $found_detail = true;
                                    } else {
                                        $arr_detail[] = array('id'      => $val2->id,
                                                            'name'      => $val2->name,
                                                            'qty'       => $val2->qty
                                                        );
                                    }
                                }
                                if(!$found_detail){
                                    $arr_detail[] = array('id'      => $item_id,
                                                        'name'      => $item_name,
                                                        'qty'       => 1
                                                    );
                                }
                                $orders_detail_item = json_encode($arr_detail);
                            }

                            $data0 = array(
                                'orders_id'             => $orders_id,
                                'product_id'            => $product->product_id,
                                'product_name'          => $product->product_name,
                                'product_images'        => get_cover_image_detail($product->product_id),
                                'product_price_buy'     => $product_detail->product_price_buy,
                                'orders_detail_price'   => $detail_price,
                                'orders_detail_qty'     => $detail_qty,
                                'orders_detail_weight'  => $detail_weight,
                                'orders_detail_item'    => $orders_detail_item,
                                'orders_detail_status'  => 1,
                                'date_created'          => timestamp()
                            );

                            if($orders_detail_id == NULL){
                                $this->db->insert("mt_orders_detail",$data0);
                            } else {
                                $this->db->update("mt_orders_detail",$data0,array("orders_detail_id"=>$orders_detail_id));
                            }

                            $this->db->update("mt_orders",array('orders_product_detail' => 1),array("orders_id"=>$orders_id));

                            recalc_price_orders($orders_id);
                            set_last_date_product_setup();
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

        $result = "";
        return array(
                "data"   => $result,
                "error"  => $error,
                "msg"    => $msg,
                "total"  => 1
            );
    }

    function data_transaction_del_detail_product($p=array()){
        $count_list = 0;
        $cart_list  = "";

        $user_id    = $p['user_id'];
        $store_id   = $p['store_id'];
        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();
        if(count($r)){
            $detail = get_orders_detail($r->orders_id);
            foreach ($detail as $key2 => $val2) {
                $count_list += 1;

                if($val2->orders_detail_item == "" || $val2->orders_detail_item == NULL){
                    $cart_list .= '
                            <div class="cart-list-item">
                                <div class="cart-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$val2->product_images).');"></div>
                                <div class="cart-list-content">
                                    <p class="no-margin no-padding"><strong>'.$val2->product_code." ".$val2->product_name.'</strong></p>
                                    <h6 class="no-margin no-padding mb-5">'.convertRp($val2->orders_detail_price).'</strong></h6>
                                    <div class="product-list-action">
                                        <div class="btn-group pull-right"><div class="btn btn-danger btn-xs btn-delete-item-product" data-id="'.$r->orders_id.'" data-detail-id="'.$val2->orders_detail_id.'" data-detail-item-id="" data-detail-item-name="'.$val2->product_name.'" data-detail-item-qty="1">Hapus</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';
                } else {
                    $orders_detail_item = json_decode($val2->orders_detail_item);
                    foreach ($orders_detail_item as $key3 => $val3) {
                        for ($i=0; $i < $val3->qty; $i++) {
                            $cart_list .= '
                            <div class="cart-list-item">
                                <div class="cart-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$val2->product_images).');"></div>
                                <div class="cart-list-content">
                                    <p class="no-margin no-padding"><strong>'.$val2->product_code." ".$val2->product_name.' - '.$val3->name.'</strong></p>
                                    <h6 class="no-margin no-padding mb-5">'.convertRp($val2->orders_detail_price).'</strong></h6>
                                    <div class="product-list-action">
                                        <div class="btn-group pull-right"><div class="btn btn-danger btn-xs btn-delete-item-product" data-id="'.$r->orders_id.'" data-detail-id="'.$val2->orders_detail_id.'" data-detail-item-id="'.$val3->id.'" data-detail-item-name="'.$val2->product_name.' - '.$val3->name.'" data-detail-item-qty="1">Hapus</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ';
                        }
                    }
                }
            }
        }

        $result = '
        <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-horizontal">
                <legend>Hapus Produk</legend>
                <div class="relative cart-list cart-list-small no-margin mb-5">
                    '.$cart_list.'
                </div>
                <div class="form-group form-action mb-0">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9 clearfix" style="min-height:34px;">
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </div>
            </div>
        </form>
        ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

    function data_transaction_save_del_detail_product($p=array()){
        $data  = array();
        $error = false;
        $msg   = "";

        $user_id      = $p['user_id'];
        $store_id     = $p['store_id'];
        $orders_id    = $p['orders_id'];
        $thisDetailId = $p['thisDetailId'];
        $thisItemId   = $p['thisItemId'];
        $thisItemName = $p['thisItemName'];
        $thisItemQty  = $p['thisItemQty'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id'  => $orders_id,
            'store_id'   => $store_id
        ),1,0)->row();
        if(count($r) > 0){

            $m1 = $this->db->get_where("mt_orders_detail",array(
                "orders_id"            => $orders_id,
                "orders_detail_id"     => $thisDetailId
            ),1,0)->result();
            if(count($m1) > 0){
                foreach ($m1 as $key1 => $val1) {
                    $product_id = $val1->product_id;
                    $orders_detail_qty = $val1->orders_detail_qty;
                    $orders_qty = $thisItemQty;

                    $tempItem = array();
                    $arr_item = array();
                    $data_orders_detail = array();
                    $data_orders_detail['orders_detail_qty'] = ($orders_detail_qty - $orders_qty);
                    if($val1->orders_detail_item != ""){
                        $tempItem[$thisItemId] = $orders_qty;

                        $item_stock = 0;
                        $orders_detail_item = json_decode($val1->orders_detail_item);
                        foreach ($orders_detail_item as $key2 => $val2){
                            if($val2->id == $thisItemId){
                                $item_stock = ($val2->qty - $orders_qty);
                                if($item_stock >= 1){
                                    $arr_item[] = array('id'        => $val2->id,
                                                        'name'      => $val2->name,
                                                        'qty'       => $item_stock
                                                    );
                                }
                            } else {
                                $arr_item[] = array('id'        => $val2->id,
                                                    'name'      => $val2->name,
                                                    'qty'       => $val2->qty
                                                );
                            }
                        }

                        $data_orders_detail['orders_detail_item'] = json_encode($arr_item);
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

                    $this->db->update("mt_orders_detail",$data_orders_detail,array("orders_detail_id"=>$val1->orders_detail_id));
                    if(count($arr_item) <= 0){
                        $this->db->delete("mt_orders_detail",array('orders_detail_id' => $val1->orders_detail_id));
                    }
                }

                recalc_price_orders($orders_id);
                set_last_date_product_setup();
            }

            $error  = false;
            $msg    = 'Berhasil hapus produk ...';
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

    function data_transaction_detail_product_backup($p=array()){
        $count_list = 0;
        $count_item = 0;
        $cart_list  = "";

        $user_id    = $p['user_id'];
        $store_id   = $p['store_id'];
        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders_bk",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();
        if(count($r)){
            $detail   = get_orders_detail_bk($r->orders_id);
            $shipping = get_detail_orders_shipping_bk($r->orders_id);
            foreach ($detail as $key2 => $val2) {
                $count_list += 1;

                $varian = "";
                if($val2->orders_detail_item != "" && $val2->orders_detail_item != "[]"){
                    $varian .= '<div class="relative">';
                    $varian .= '<p class="no-margin no-padding">Varian:</p>';
                    $varian .= '<ul class="product-list-item-stock clearfix mb-5">';
                    $orders_detail_item = json_decode($val2->orders_detail_item);
                    foreach ($orders_detail_item as $key3 => $val3) {
                        $varian .= '<li><p>'.$val3->name.' = '.$val3->qty.'</p></li>';
                        $count_item += $val3->qty;
                    }
                    $varian .= '</ul>';
                    $varian .= '</div>';
                } else {
                    $count_item += 1;
                }

                $cart_list .= '
                <div class="cart-list-item">
                    <div class="cart-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$val2->product_images).');"></div>
                    <div class="cart-list-content">
                        <h6 class="no-margin no-padding"><strong>'.$val2->product_code.'</strong></h6>
                        <p class="no-margin no-padding"><strong>'.$val2->product_name.'</strong></p>
                        <h6 class="no-margin no-padding mb-5">'.convertRp($val2->orders_detail_price).' x '.$val2->orders_detail_qty.' = <strong>'.convertRp(($val2->orders_detail_price * $val2->orders_detail_qty)).'</strong></h6>
                        '.$varian.'
                    </div>
                </div>
                ';
            }
        }

        $result = '
        <form class="" action="" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-horizontal">
                <legend>Daftar Produk "'.$shipping->orders_shipping_name.'"</legend>
                <div class="relative cart-list cart-list-small no-margin mb-5">
                    '.$cart_list.'
                </div>
                <div class="relative">
                    <h6 class="no-margin no-padding"><strong>'.($cart_list!=""?'Total Produk: '.$count_list.', Jumlah Item: '.$count_item.'':'Belum ada produk...').'</strong></h6>
                </div>
                <div class="form-group form-action mb-0">
                    <div class="col-sm-12 clearfix" style="min-height:34px;">
                        <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                    </div>
                </div>
            </div>
        </form>
        ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

}