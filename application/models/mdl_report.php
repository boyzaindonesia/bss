<?php
class mdl_report extends CI_Model{

	var $tabel = 'mt_orders';

	function __construct(){
		parent::__construct();
	}

    function data_report_product($p=array()){
        $data = array();
        $data['err']     = true;
        $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'];
        $data['result']  = array();
        $product     = array();
        $arrProduct      = array();
        if(trim($p['date_start'])!="" && trim($p['date_end']) != "" ){
            $this->db->select('mt_orders.*');
            // $this->db->join("mt_product","mt_product.product_id = mt_orders_detail.product_id",'left');
            // $this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

            if(trim($p['orders_source_id'])!=""){
                $this->db->where("( mt_orders.orders_source_id = '".$p['orders_source_id']."' )");
            }

            $this->db->where("( mt_orders.orders_date <= '".$p['date_end']." 23:59:59' )");
            $this->db->where("( mt_orders.orders_date >= '".$p['date_start']." 00:00:00' )");
            $this->db->where("( mt_orders.orders_status <= '8' )");
            $this->db->where("( mt_orders.orders_status >= '2' )");
            $this->db->order_by("mt_orders.orders_date","desc");

            $qry = $this->db->get('mt_orders');
            foreach ($qry->result() as $key => $val) {
                $orders_id  = $val->orders_id;
                $detail = get_orders_detail($val->orders_id);
                foreach ($detail as $key2 => $val2) {
                    if(!in_array($val2->product_id, $arrProduct)){
                        $arrProduct[] = $val2->product_id;
                    }
                    $product[$val2->product_id]->total     += $val2->orders_detail_qty;
                    $product[$val2->product_id]->shopee    += 0;
                    $product[$val2->product_id]->tokopedia += 0;
                    $product[$val2->product_id]->lazada    += 0;
                    $product[$val2->product_id]->other     += 0;
                    switch ($val->orders_source_id) {
                        case '8': $product[$val2->product_id]->shopee += $val2->orders_detail_qty; break;
                        case '3': $product[$val2->product_id]->tokopedia += $val2->orders_detail_qty; break;
                        case '11': $product[$val2->product_id]->lazada += $val2->orders_detail_qty; break;
                        default: $product[$val2->product_id]->other += $val2->orders_detail_qty; break;
                    }
                }
            }


            $data['err']     = false;
            $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'].' berhasil...';
            $data['product'] = $product;
            $data['arrProduct'] = $arrProduct;
        }
        return $data;
    }

    function data_report_orders($p=array()){
        $data = array();
        $data['err']     = true;
        $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'];
        $data['result']  = array();
        if(trim($p['date_start'])!="" && trim($p['date_end']) != "" ){
            $this->db->select('mt_orders.*');
            // $this->db->join("mt_product","mt_product.product_id = mt_orders_detail.product_id",'left');
            // $this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

            $this->db->where("( mt_orders.orders_date <= '".$p['date_end']." 23:59:59' )");
            $this->db->where("( mt_orders.orders_date >= '".$p['date_start']." 00:00:00' )");
            $this->db->where("( mt_orders.orders_status <= '8' )");
            $this->db->where("( mt_orders.orders_status >= '2' )");
            $this->db->order_by("mt_orders.orders_date","desc");

            $qry = $this->db->get('mt_orders');

            $iRow = 32;
            $arrPrice = array();
            $arrQty   = array();
            for ($i=0; $i <= $iRow; $i++) {
                $arrPrice[$i] = 0;
                $arrQty[$i]   = 0;
            }
            foreach ($qry->result() as $key => $val) {
                $orders_id  = $val->orders_id;
                $arrQty[0] += 1;
                $detail = get_orders_detail($val->orders_id);
                foreach ($detail as $key2 => $val2) {
                    $arrQty[1] += 1;
                    $arrQty[2] += $val2->orders_detail_qty;
                }
                $arrPrice[4] += $val->orders_price_grand_total;
                $arrPrice[5] += $val->orders_price_buy_total;
                $arrPrice[6] += $val->orders_price_product;
                $arrPrice[7] += $val->orders_price_shipping;
                $arrPrice[8] += $val->orders_price_insurance;
                $arrPrice[32] += $val->orders_source_price;
                $temp_laba = 0;
                if($val->orders_price_buy_total > 0){
                    $temp_laba = $val->orders_price_product - $val->orders_price_buy_total;
                }
                $arrPrice[9]  += $temp_laba;
                $arrPrice[10] += $val->orders_claim_price;
                $parent_id_courier = get_parent_id_orders_courier($val->orders_courier_id);
                switch ($parent_id_courier) {
                    case '1':  $arrPrice[11] += $val->orders_price_shipping; $arrQty[11] += 1; break;
                    case '5':  $arrPrice[13] += $val->orders_price_shipping; $arrQty[13] += 1; break;
                    case '7':  $arrPrice[12] += $val->orders_price_shipping; $arrQty[12] += 1; break;
                    case '9':  $arrPrice[14] += $val->orders_price_shipping; $arrQty[14] += 1; break;
                    case '12': $arrPrice[15] += $val->orders_price_shipping; $arrQty[15] += 1; break;
                    case '16': $arrPrice[16] += $val->orders_price_shipping; $arrQty[16] += 1; break;
                    case '19': $arrPrice[17] += $val->orders_price_shipping; $arrQty[17] += 1; break;
                    case '22': $arrPrice[18] += $val->orders_price_shipping; $arrQty[18] += 1; break;
                    case '26': $arrPrice[19] += $val->orders_price_shipping; $arrQty[19] += 1; break;
                    default: break;
                }

                $orders_source_id = $val->orders_source_id;
                switch ($orders_source_id) {
                    case '1':  $arrPrice[20] += $val->orders_price_product; $arrQty[20] += 1; break;
                    case '2':  $arrPrice[23] += $val->orders_price_product; $arrQty[23] += 1; break;
                    case '3':  $arrPrice[22] += $val->orders_price_product; $arrQty[22] += 1; break;
                    case '4':  $arrPrice[26] += $val->orders_price_product; $arrQty[26] += 1; break;
                    case '5':  $arrPrice[27] += $val->orders_price_product; $arrQty[27] += 1; break;
                    case '6':  $arrPrice[28] += $val->orders_price_product; $arrQty[28] += 1; break;
                    case '7':  $arrPrice[25] += $val->orders_price_product; $arrQty[25] += 1; break;
                    case '8':  $arrPrice[21] += $val->orders_price_product; $arrQty[21] += 1; break;
                    case '9':  $arrPrice[29] += $val->orders_price_product; $arrQty[29] += 1; break;
                    case '10': $arrPrice[30] += $val->orders_price_product; $arrQty[30] += 1; break;
                    case '11': $arrPrice[24] += $val->orders_price_product; $arrQty[24] += 1; break;
                    case '12': $arrPrice[31] += $val->orders_price_product; $arrQty[31] += 1; break;
                    default: break;
                }
                $arrQty[7] += 1;
            }

            $report_desc = array();
            for ($i=0; $i <= $iRow; $i++) {
                switch ($i) {
                    case '0':  $name = "Total Orderan"; $price = 0; $qty = $arrQty[$i]; break;
                    case '1':  $name = "Total Produk"; $price = 0; $qty = $arrQty[$i]; break;
                    case '2':  $name = "Total Item"; $price = 0; $qty = $arrQty[$i]; break;
                    case '3':  $name = "Total Pengeluaran"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '4':  $name = "Grand Total"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '5':  $name = "Total Harga Beli"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '6':  $name = "Total Harga Jual"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '7':  $name = "Total Ongkos Kirim"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '8':  $name = "Total Asuransi"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '9':  $name = "Total Laba"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '10': $name = "Total Claim"; $price = $arrPrice[$i]; $qty = 0; break;
                    case '11': $name = "Ongkir JNE"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '12': $name = "Ongkir J&T"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '13': $name = "Ongkir Wahana"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '14': $name = "Ongkir Gosend"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '15': $name = "Ongkir Grab"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '16': $name = "Ongkir Tiki"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '17': $name = "Ongkir Pos"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '18': $name = "Ongkir Sicepat"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '19': $name = "Ongkir Ninja"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '20': $name = "Terjual Website"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '21': $name = "Terjual Shopee"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '22': $name = "Terjual Tokopedia"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '23': $name = "Terjual Bukalapak"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '24': $name = "Terjual Lazada"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '25': $name = "Terjual Whatsapp"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '26': $name = "Terjual Intagram"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '27': $name = "Terjual Facebook"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '28': $name = "Terjual Line"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '29': $name = "Terjual Pickup"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '30': $name = "Terjual Apps"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '31': $name = "Terjual Other"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    case '32': $name = "Total Jual All MP"; $price = $arrPrice[$i]; $qty = $arrQty[$i]; break;
                    default: $name = ""; $price = 0; $qty = 0; break;
                }
                $report_desc[$i]->id    = ($i + 1);
                $report_desc[$i]->name  = $name;
                $report_desc[$i]->price = $price;
                $report_desc[$i]->qty   = $qty;
            }

            $data['err']     = false;
            $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'].' berhasil...';
            $data['result']  = $report_desc;
        }
        return $data;
    }

	function data_produk_keluar($p=array()){
        $data = array();
        $data['err']     = true;
        $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'];
        $data['result']  = array();
        if(trim($p['date_start'])!="" && trim($p['date_end']) != "" ){
            $this->db->select('mt_orders_detail.*, mt_product.product_code, mt_product.url, mt_product_detail.product_status_id, mt_product_detail.product_stock, mt_product_detail.product_stock_detail');
            $this->db->join("mt_product","mt_product.product_id = mt_orders_detail.product_id",'left');
            $this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

            $this->db->where("( mt_orders_detail.date_created <= '".$p['date_end']." 23:59:59' )");
            $this->db->where("( mt_orders_detail.date_created >= '".$p['date_start']." 00:00:00' )");
            // $this->db->group_by("mt_orders_detail.product_id");
            $this->db->order_by("mt_product.product_date_push","desc");

            $qry = $this->db->get('mt_orders_detail');
            $arrOrdersId = array();
            $arrProduct  = array();
            $total_price_buy  = 0;
            $total_price_sale = 0;
            $total_qty        = 0;
            foreach ($qry->result() as $key => $val) {
                if(!in_array($val->orders_id, $arrOrdersId)){
                    $arrOrdersId[] = $val->orders_id;
                }
                if(!array_key_exists($val->product_id, $arrProduct)){
                    $orders_id  = $val->orders_id;
                    $price_buy  = ($val->product_price_buy * $val->orders_detail_qty);
                    $price_sale = ($val->orders_detail_price * $val->orders_detail_qty);
                    $qty        = $val->orders_detail_qty;
                    $qty_orders = 1;

                    $total_price_buy  += $price_buy;
                    $total_price_sale += $price_sale;
                    $total_qty        += $qty;

                    $arr = NULL;
                    if($val->orders_detail_item != ""){
                        $arr = array();
                        $exp = json_decode($val->orders_detail_item);
                        foreach ($exp as $key3 => $val3) {
                            // $arr[$key3]->name   = $val3->name;
                            // $arr[$key3]->qty    = $val3->qty;
                            $arr[changeEnUrl($val3->name)]->name   = $val3->name;
                            $arr[changeEnUrl($val3->name)]->qty    = $val3->qty;
                        }
                    }
                    $orders_detail_item = $arr;

                    $arr = NULL;
                    if($val->product_stock_detail != ""){
                        $arr = array();
                        $exp = json_decode($val->product_stock_detail);
                        foreach ($exp as $key3 => $val3) {
                            $arr[$key3]->id     = $val3->id;
                            $arr[$key3]->name   = $val3->name;
                            $arr[$key3]->color  = $val3->color;
                            $arr[$key3]->qty    = $val3->qty;
                            $arr[$key3]->status = $val3->status;
                        }
                    }
                    $product_stock_detail = $arr;

                    $arrProduct[$val->product_id]->product_id   = $val->product_id;
                    $arrProduct[$val->product_id]->product_name = $val->product_name;
                    $arrProduct[$val->product_id]->product_code = $val->product_code;
                    $arrProduct[$val->product_id]->product_images = $val->product_images;
                    $arrProduct[$val->product_id]->price_buy  = $price_buy;
                    $arrProduct[$val->product_id]->price_sale = $price_sale;
                    $arrProduct[$val->product_id]->qty        = $qty;
                    $arrProduct[$val->product_id]->qty_orders = $qty_orders;
                    $arrProduct[$val->product_id]->orders_detail_item   = $orders_detail_item;
                    $arrProduct[$val->product_id]->product_status_id    = $val->product_status_id;
                    $arrProduct[$val->product_id]->product_stock        = $val->product_stock;
                    $arrProduct[$val->product_id]->product_stock_detail = $product_stock_detail;
                    $arrProduct[$val->product_id]->url = $val->url;
                } else {
                    $price_buy  += ($val->product_price_buy * $val->orders_detail_qty);
                    $price_sale += ($val->orders_detail_price * $val->orders_detail_qty);
                    $qty        += $val->orders_detail_qty;
                    $qty_orders += 1;

                    $total_price_buy  += ($val->product_price_buy * $val->orders_detail_qty);
                    $total_price_sale += ($val->orders_detail_price * $val->orders_detail_qty);
                    $total_qty        += $val->orders_detail_qty;

                    $oldProduct = $arrProduct[$val->product_id];
                    $oldDetail  = $oldProduct->orders_detail_item;

                    $arr = NULL;
                    if($val->orders_detail_item != ""){
                        $arr = array();
                        $exp = json_decode($val->orders_detail_item);
                        foreach ($exp as $key3 => $val3) {
                            if($oldDetail[changeEnUrl($val3->name)]){
                                $oldDetail[changeEnUrl($val3->name)]->qty += $val3->qty;
                            } else {
                                $oldDetail[changeEnUrl($val3->name)]->name = $val3->name;
                                $oldDetail[changeEnUrl($val3->name)]->qty  = $val3->qty;
                            }
                        }
                    }
                    $orders_detail_item = $oldDetail;

                    $arrProduct[$val->product_id]->product_id   = $oldProduct->product_id;
                    $arrProduct[$val->product_id]->product_name = $oldProduct->product_name;
                    $arrProduct[$val->product_id]->product_code = $oldProduct->product_code;
                    $arrProduct[$val->product_id]->product_images = $oldProduct->product_images;
                    $arrProduct[$val->product_id]->price_buy  = $price_buy;
                    $arrProduct[$val->product_id]->price_sale = $price_sale;
                    $arrProduct[$val->product_id]->qty        = $qty;
                    $arrProduct[$val->product_id]->qty_orders = $qty_orders;
                    $arrProduct[$val->product_id]->orders_detail_item   = $orders_detail_item;
                    $arrProduct[$val->product_id]->product_status_id    = $oldProduct->product_status_id;
                    $arrProduct[$val->product_id]->product_stock        = $oldProduct->product_stock;
                    $arrProduct[$val->product_id]->product_stock_detail = $oldProduct->product_stock_detail;
                    $arrProduct[$val->product_id]->url = $oldProduct->url;
                }
            }

            $data['err']     = false;
            $data['msg']     = 'Pengambilan data tanggal '.$p['date_start'].' sampai '.$p['date_end'].' berhasil...';
            $data['total_orders']     = count($arrOrdersId);
            $data['total_product']    = count($arrProduct);
            $data['total_price_buy']  = $total_price_buy;
            $data['total_price_sale'] = $total_price_sale;
            $data['total_laba']       = ($total_price_sale - $total_price_buy);
            $data['total_qty']        = $total_qty;
            $data['result']  = $arrProduct;
        }

        return $data;
	}

    function data_orders_marketplace($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

        $this->db->select('mt_product_group.*');

        /* where or like conditions */
        if( isset($p['product_group_istrash']) && $p['product_group_istrash'] != "" ){
            $this->db->where("product_group_istrash",$p['product_group_istrash']);
        } else {
            $this->db->where("product_group_istrash",0);
        }

        if( isset($p['store_id']) && $p['store_id'] != "" ){
            $this->db->where("store_id",$p['store_id']);
        }

        if( isset($p['product_group_id']) && $p['product_group_id'] != "" ){
            $this->db->where("product_group_id",$p['product_group_id']);
        }
        if( isset($p['product_group_show']) && $p['product_group_show'] != "" ){
            $this->db->where("product_group_show",$p['product_group_show']);
        }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( product_group_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( product_group_date >= '".$p['date_start']." 00:00:00' )");
        }
        // dont modified....
        if( trim($p['colum'])=="" && trim($p['keyword']) != "" ){
            $str_like = "( ";
            $i=0;
            foreach ($p['param'] as $key => $value) {
                if($key != ""){
                    $str_like .= $i!=0?"OR":"";
                    $str_like .=" ".$key." LIKE '%".$p['keyword']."%' ";
                    $i++;
                }
            }
            $str_like .= " ) ";
            $this->db->where($str_like);
        }
        if( trim($p['colum'])!="" && trim($p['keyword']) != "" ){
            $this->db->like($p['colum'],$p['keyword']);
        }
        if($count==FALSE){
            if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
                $p['offset'] = empty($p['offset'])?0:$p['offset'];
                $this->db->limit($p['limit'],$p['offset']);
            }
        }

        if( (isset($p['order_by']) && $p['order_by']!="") && (isset($p['order_dir']) && $p['order_dir']!="") ){
            $order_by  = $p['order_by'];
            $order_dir = $p['order_dir'];
            $this->db->order_by($order_by,$order_dir);
        } else {
            $this->db->order_by('product_group_date','desc');
        }

        $qry = $this->db->get('mt_product_group');
        if($count==FALSE){
            $total = $this->data_orders_marketplace($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list_desktop':
                    $isFull     = FALSE;
                    $isKey      = array("product_group_id","product_group_name","product_group_date","product_group_item","product_sold","item_html","array_product_id");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result = array();
            $iKey = 0;
            $timestamp = timestamp();
            $get_orders_source = get_orders_source();
            foreach ($qry->result() as $key => $val) {
                if(in_array("product_group_id", $isKey) || $isFull){
                    $result[$iKey]->id = $val->product_group_id;
                }
                if(in_array("store_id", $isKey) || $isFull){
                    $result[$iKey]->store_id = $val->store_id;
                }
                if(in_array("product_group_name", $isKey) || $isFull){
                    $result[$iKey]->name = $val->product_group_name;
                }
                if(in_array("product_group_date", $isKey) || $isFull){
                    $result[$iKey]->date = $val->product_group_date;
                }
                $result[$iKey]->isGroup = 1;

                $product_sold = 0;
                if(in_array("product_group_item", $isKey) || $isFull){
                    $total_stock = 0;
                    $product_id  = "";
                    $images_cover = "";
                    $price_buy  = 0;
                    $price_sale = 0;
                    $price_discount = 0;
                    $allcode    = "";

                    $colgroup = '<colgroup>';
                    $thead    = '<thead><tr>';
                    $tbody    = '<tbody>';
                    $arr  = NULL;
                    $get_ = get_product_by_group($val->product_group_id,"");
                    if(count($get_) > 0){
                        $arr = array();
                        $ii = 1;
                        $array_product_id = "";
                        foreach ($get_ as $key3 => $val3) {
                            $arr[$key3]->id     = $val3->product_id;
                            $arr[$key3]->motif  = $val3->product_type_motif;

                            $product_id .= ($product_id==""?"":"-").$val3->product_id;

                            if($images_cover == ""){ $images_cover = get_cover_image_detail($val3->product_id); }
                            if($price_buy == 0){ $price_buy = $val3->product_price_buy; }
                            if($price_sale == 0){ $price_sale = $val3->product_price_sale; }
                            if($price_discount == 0){ $price_discount = $val3->product_price_discount; }
                            $allcode .= ($allcode == ""?"":" - ").$val3->product_code;
                            $day_on_going = xTimeAgo($val3->product_date, $timestamp, "d");
                            $product_sold += $val3->product_sold;
                            $exp = json_decode($val3->product_stock_detail);
                            foreach ($exp as $key4 => $val4) {
                                $total_stock  += $val4->qty;
                            }

                            if(in_array("item_html", $isKey)){
                                $tbody    .= '<tr><td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.$val3->product_type_motif.'</td>';
                                if($val3->product_stock_detail != ''){
                                    foreach ($get_orders_source as $key4 => $val4) {
                                        $source_id = $val4->orders_source_id;
                                        $qty = 0;
                                        $product_sold_detail = json_decode($val3->product_sold_detail);
                                        foreach ($product_sold_detail as $key5 => $val5) {
                                            if($val5->id == $source_id){ $qty = $val5->qty; }
                                        }
                                        $tbody    .= '<td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.($qty > 0?$qty:'-').'</td>';
                                    }

                                    $tbody    .= '<td style="text-align:center;font-weight:bold;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.$val3->product_sold.'</td>';

                                }
                                $tbody    .= '</tr>';
                            }

                            if(in_array("array_product_id", $isKey) || $isFull){
                                $array_product_id .= ($array_product_id==""?"":"-").$val3->product_id;
                            }

                            $ii += 1;
                        }
                    }

                    if(in_array("item_html", $isKey)){
                        $colgroup .= '<col width="1">';
                        $thead    .= '<th style="white-space:nowrap;text-align:center;background-color:#d1d2d3;">M</th>';
                        foreach ($get_orders_source as $key4 => $val4) {
                            $colgroup .= '<col width="1">';
                            $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">'.$val4->orders_source_name.'</th>';
                        }
                        $colgroup .= '<col width="1">';
                        $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">Total</th>';

                        $colgroup .= '</colgroup>';
                        $thead    .= '</thead></tr>';
                        $tbody    .= '</tbody>';

                        $item_html = '<table style="width:100%;margin:-5px 0px;">'.$colgroup.$thead.$tbody.'</table>';
                        $result[$iKey]->item_html = $item_html;
                    }
                    if(in_array("array_product_id", $isKey) || $isFull){
                        $result[$iKey]->array_product_id  = $array_product_id;
                    }

                    $result[$iKey]->images_cover   = $images_cover;
                    $result[$iKey]->price_buy      = $price_buy;
                    $result[$iKey]->price_sale     = $price_sale;
                    $result[$iKey]->price_discount = $price_discount;
                    $result[$iKey]->total_stock  = $total_stock;
                    $result[$iKey]->day_on_going = $day_on_going;
                    $result[$iKey]->allcode      = $allcode;
                }

                if(in_array("product_sold", $isKey) || $isFull){
                    $result[$iKey]->product_sold = $product_sold;
                }

                $iKey += 1;
            }

            $get_product_by_group = get_product_no_group("1",$p['store_id']);
            foreach ($get_product_by_group as $key2 => $val2) {
                if(in_array("product_group_id", $isKey) || $isFull){
                    $result[$iKey]->id = $val2->product_id;
                }
                if(in_array("store_id", $isKey) || $isFull){
                    $result[$iKey]->store_id = $val2->store_id;
                }
                if(in_array("product_group_name", $isKey) || $isFull){
                    $result[$iKey]->name = $val2->product_name_simple;
                }
                if(in_array("product_group_date", $isKey) || $isFull){
                    $result[$iKey]->date = $val2->product_date;
                }
                $result[$iKey]->isGroup = 0;

                $day_on_going = xTimeAgo($val2->product_date, $timestamp, "d");
                $result[$iKey]->day_on_going = $day_on_going;

                if(in_array("product_sold", $isKey) || $isFull){
                    $result[$iKey]->product_sold = $val2->product_sold;
                }

                if(in_array("product_group_item", $isKey) || $isFull){
                    $colgroup = "";
                    $thead    = "";
                    $tbody    = "";
                    // $tbody    .= '<tr><td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'"></td>';
                    foreach ($get_orders_source as $key4 => $val4) {
                        $source_id = $val4->orders_source_id;
                        $qty = 0;
                        $product_sold_detail = json_decode($val2->product_sold_detail);
                        foreach ($product_sold_detail as $key5 => $val5) {
                            if($val5->id == $source_id){ $qty = $val5->qty; }
                        }
                        $tbody    .= '<td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.($qty > 0?$qty:'-').'</td>';
                    }

                    $tbody    .= '<td style="text-align:center;font-weight:bold;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.$val2->product_sold.'</td>';

                    $tbody    .= '</tr>';

                    if(in_array("item_html", $isKey)){
                        $colgroup .= '<col width="1">';
                        // $thead    .= '<th style="white-space:nowrap;text-align:center;background-color:#d1d2d3;">M</th>';
                        foreach ($get_orders_source as $key4 => $val4) {
                            $colgroup .= '<col width="1">';
                            $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">'.$val4->orders_source_name.'</th>';
                        }
                        $colgroup .= '<col width="1">';
                        $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">Total</th>';

                        $colgroup .= '</colgroup>';
                        $thead    .= '</thead></tr>';
                        $tbody    .= '</tbody>';

                        $item_html = '<table style="width:100%;margin:-5px 0px;">'.$colgroup.$thead.$tbody.'</table>';
                        $result[$iKey]->item_html = $item_html;
                    }

                    if(in_array("array_product_id", $isKey) || $isFull){
                        $result[$iKey]->array_product_id = $val2->product_id;
                    }
                }

                $result[$iKey]->images_cover = get_cover_image_detail($val2->product_id);
                $result[$iKey]->price_buy    = $val2->product_price_buy;
                $result[$iKey]->price_sale   = $val2->product_price_sale;
                $result[$iKey]->price_discount = $val2->product_price_discount;
                $result[$iKey]->allcode      = $val2->product_code;
                $result[$iKey]->total_stock  = $val2->product_stock;

                $iKey += 1;
            }

            return array(
                    "data"  => $result,
                    "total" => $total
                );
        } else {
            return $qry->num_rows();
        }
    }

    function layout_report_new_orders($p=array()){
        $return = "";
        if(count($p) > 0){
            $ongkir_lain = 0;
            $ongkir_qtylain = 0;
            $arrOngkir = array(13,14,15,16,17,18,19);
            foreach ($arrOngkir as $n) {
                $ongkir_lain += $p[$n]->price;
                $ongkir_qtylain += $p[$n]->qty;
            }

            $ship_qty_lain   = 0;
            $ship_price_lain = 0;
            $arrShip = array(20,24,25,26,27,28,29,30,31);
            foreach ($arrShip as $n) {
                $ship_qty_lain   += $p[$n]->qty;
                $ship_price_lain += $p[$n]->price;
            }

            $return .= '
            <div class="relative clearfix">
                <div class="row">
                    <div class="col-sm-3" style="padding:0 3px;">
                        <ul class="list-group">
                            <li class="list-group-item">'.$p[0]->name.'<span class="badge badge-success">'.$p[0]->qty.' order</span></li>
                            <li class="list-group-item">'.$p[1]->name.'<span class="badge badge-success">'.$p[1]->qty.' produk</span></li>
                            <li class="list-group-item">'.$p[2]->name.'<span class="badge badge-success">'.$p[2]->qty.' item</span></li>
                            <li class="list-group-item">'.$p[32]->name.'<span class="badge badge-success">'.convertRp($p[32]->price).'</span></li>
                        </ul>
                    </div>
                    <div class="col-sm-3" style="padding:0 3px;">
                        <ul class="list-group">
                            <li class="list-group-item">'.$p[4]->name.'<span class="badge badge-success">'.convertRp($p[4]->price).'</span></li>
                            <li class="list-group-item">'.$p[5]->name.'<span class="badge badge-success">'.convertRp($p[5]->price).'</span></li>
                            <li class="list-group-item">'.$p[6]->name.'<span class="badge badge-success">'.convertRp($p[6]->price).'</span></li>
                            <li class="list-group-item">'.$p[9]->name.'<span class="badge badge-success">'.convertRp($p[9]->price).'</span></li>
                        </ul>
                    </div>
                    <div class="col-sm-3" style="padding:0 3px;">
                        <ul class="list-group">
                            <li class="list-group-item">'.$p[7]->name.'<span class="badge badge-success">'.convertRp($p[7]->price).'</span></li>
                            <li class="list-group-item">'.$p[11]->name.' ('.$p[11]->qty.')<span class="badge badge-success">'.convertRp($p[11]->price).'</span></li>
                            <li class="list-group-item">'.$p[12]->name.' ('.$p[12]->qty.')<span class="badge badge-success">'.convertRp($p[12]->price).'</span></li>
                            <li class="list-group-item">Ongkir Lainnya ('.$ongkir_qtylain.')<span class="badge badge-success">'.convertRp($ongkir_lain).'</span></li>
                        </ul>
                    </div>
                    <div class="col-sm-3" style="padding:0 3px;">
                        <ul class="list-group">
                            <li class="list-group-item">'.$p[21]->name.' ('.$p[21]->qty.')<span class="badge badge-success">'.convertRp($p[21]->price).'</span></li>
                            <li class="list-group-item">'.$p[22]->name.' ('.$p[22]->qty.')<span class="badge badge-success">'.convertRp($p[22]->price).'</span></li>
                            <li class="list-group-item">'.$p[23]->name.' ('.$p[23]->qty.')<span class="badge badge-success">'.convertRp($p[23]->price).'</span></li>
                            <li class="list-group-item">Terjual Lainnya ('.$ship_qty_lain.')<span class="badge badge-success">'.convertRp($ship_price_lain).'</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            ';
        }

        return $return;
    }

    function layout_produk_keluar($p=array()){
        $return = "";
        if(count($p) > 0){
            foreach ($p as $key => $r) {
                $html_stok_keluar = "";
                if(isset($r->orders_detail_item) && $r->orders_detail_item != ''){
                    $foundSold = false;
                    $html_stok_keluar .= '<ul class="product-list-item-stock clearfix mb-5">';
                    foreach ($r->orders_detail_item as $key => $value) {
                        $html_stok_keluar .= '<li><p class="text-info">'.$value->name.' = '.$value->qty.'</p></li>';
                    }
                    $html_stok_keluar .= '</ul>';
                }

                $html_stok_detail = "";
                if(isset($r->product_stock_detail) && $r->product_stock_detail != ''){
                    $foundSold = false;
                    $html_stok_detail .= '<ul class="product-list-item-stock clearfix mb-5">';
                    foreach ($r->product_stock_detail as $key => $value) {
                        if($value->status==1){
                            $html_stok_detail .= '<li><p>'.$value->name.' = '.$value->qty.'</p></li>';
                        } else { $foundSold = true; }
                    }
                    $html_stok_detail .= '</ul>';
                    if($r->product_status_id==1 && $foundSold){
                        $html_stok_detail .= '<p class="no-margin no-padding text-danger">Variasi Sold:</p>
                        <ul class="product-list-item-stock clearfix mb-0">';
                        foreach ($r->product_stock_detail as $key => $value) {
                            if($value->status==2){
                                $html_stok_detail .= '<li><p class="text-danger">'.$value->name.' = '.$value->qty.'</p></li>';
                            }
                        }
                        $html_stok_detail .= '</ul>';
                    }
                }

                $return .= '
                <div class="product-list-item" data-id="'.$r->product_id.'">
                    <div class="product-list-image" style="background-image: url('.get_image(base_url()."assets/collections/product/thumb/".$r->product_images).');"></div>
                    <div class="product-list-content">
                        <p class="product-list-title no-margin no-padding mb-5"><strong><a href="'.base_url().'admin/product/view/'.$r->product_id.'-'.changeEnUrl($r->product_name).'">'.$r->product_name.'</a> - '.$r->product_code.'</strong></p>

                        <h6 class="no-margin no-padding mb-5">Harga Beli: <span style="">'.convertRp($r->price_buy).'</span></h6>
                        <h6 class="no-margin no-padding mb-5">Harga Jual: <span style="">'.convertRp($r->price_sale).'</span></h6>
                        <h6 class="no-margin no-padding mb-5">Laba: <span style="">'.convertRp($r->price_sale - $r->price_buy).'</span></h6>
                    </div>
                    <div class="relative">
                        <p class="no-margin no-padding text-info">Stok Keluar: '.$r->qty.' pcs</p>
                        '.$html_stok_keluar.'
                    </div>
                    <div class="relative">
                        <p class="no-margin no-padding">Stok Saat Ini: '.$r->product_stock.' pcs</p>
                        '.$html_stok_detail.'
                    </div>
                </div>';
            }
        }

        return $return;
    }

    function layout_report_history_orders_mobile($p=array()){
        $return = "";
        $colgroup = '<colgroup><col width="1">';
        $thead    = '<thead><tr><th class="nobr">&nbsp;</th>';
        $tbody    = '<tbody>';
        if(count($p) > 0){
            $cols = count($p);
            $i = 0;
            foreach ($p as $key => $r) {
                $colgroup .= '<col width="1">';
                $thead    .= '<th class="nobr">'.getMonthDate2($r->date).'</th>';
            }

            $arrID = array(1,2,3,10,5,7,6,8,12,13,22,23,24,25);
            foreach ($arrID as $key2 => $val2) {
                $tbody .= '<tr>';
                $statistik = $p[0]->statistik;
                foreach ($statistik as $key3 => $val3) {
                    if($val3->id == $val2){
                        $tbody .= '<td class="nobr">'.$val3->name.'</td>';
                    }
                }

                for ($i=0; $i < $cols; $i++) {
                    $statistik = $p[$i]->statistik;
                    foreach ($statistik as $key3 => $val3) {
                        if($val3->id == $val2){
                            if(in_array($val2, array(5,6,7,8,10))){
                                $tbody .= '<td class="nobr">'.convertRp($val3->price).'</td>';
                            } else if(in_array($val2, array(12,13,22,23,24,25))){
                                $tbody .= '<td class="nobr">'.convertRp($val3->price).' ('.$val3->qty.')</td>';
                            } else {
                                $tbody .= '<td class="nobr">'.$val3->qty.'</td>';
                            }
                        }
                    }
                }
                $tbody .= '</tr>';
            }

        }
        $colgroup .= '</colgroup>';
        $thead    .= '</tr></thead>';
        $tbody    .= '</tbody>';

        $return .= '
        <table class="table table-th-block">
            '.$colgroup.'
            '.$thead.'
            '.$tbody.'
        </table>';

        return $return;
    }

}