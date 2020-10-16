<?php
class mdl_product extends CI_Model{

	var $tabel = 'mt_product';

	function __construct(){
		parent::__construct();

	}

	function data_product($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

		$this->db->select('mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*');
		$this->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
		$this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');

        /* where or like conditions */
        if( isset($p['product_istrash']) && $p['product_istrash'] != "" ){
			$this->db->where("mt_product.product_istrash",$p['product_istrash']);
		} else {
			$this->db->where("mt_product.product_istrash",0);
		}

		if( isset($p['store_id']) && $p['store_id'] != "" ){
			$this->db->where("store_id",$p['store_id']);
		}

		if( isset($p['product_id']) && $p['product_id'] != "" ){
			$this->db->where("mt_product.product_id",$p['product_id']);
		}

        if( isset($p['product_group_id']) && $p['product_group_id'] != "" ){
            if($p['product_group_id'] == "not_group"){
                $this->db->where("mt_product.product_group_id","0");
            } else {
                $this->db->where("mt_product.product_group_id",$p['product_group_id']);
            }
        }

		if( isset($p['product_show_id']) && $p['product_show_id'] != "" ){
			$this->db->where("mt_product.product_show_id",$p['product_show_id']);
		}

		if( isset($p['product_status_id']) && $p['product_status_id'] != "" ){
			$this->db->where("mt_product_detail.product_status_id",$p['product_status_id']);
		}

		if( isset($p['product_category_id']) && $p['product_category_id'] != "" ){
			$this->db->where("mt_product.product_category_id",$p['product_category_id']);
		}

        if(isset($p['product_awards_id']) && $p['product_awards_id'] != ''){
            $this->db->where_in("mt_product.product_awards", $p['product_awards_id']);
        }

        if(isset($p['product_tags_id']) && $p['product_tags_id'] != ''){
            $this->db->where_in("mt_product.product_tags", $p['product_tags_id']);
        }

        if( isset($p['product_brand_id']) && $p['product_brand_id'] != "" ){
            $this->db->where("mt_product.product_brand_id",$p['product_brand_id']);
        }

        if( isset($p['min_range']) && $p['min_range'] != "" ){
            $this->db->where("( mt_product_detail.product_price_sale >= '".$p['min_range']."' )");
        }
        if( isset($p['max_range']) && $p['max_range'] != "" ){
            $this->db->where("( mt_product_detail.product_price_sale <= '".$p['max_range']."' )");
        }

        if( trim($p['date_end']) != "" ){
            $this->db->where("( mt_product.product_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( mt_product.product_date >= '".$p['date_start']." 00:00:00' )");
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
            $this->db->order_by('mt_product.product_date_push','desc');
        }

        $qry = $this->db->get('mt_product');
        if($count==FALSE){
            $total = $this->data_product($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_category_name","product_root_category_name","store_id","product_name","product_name_simple","product_code","product_awards","product_group_id","product_date","product_date_update","product_date_push","images_cover","url_product","product_price_buy","product_price_sale","product_price_discount","product_price_grosir","product_sold","product_stock","product_stock_detail","product_show_id","product_show_name","product_status_id","product_status_name","url_product_category","product_stock_copy");
                    break;
                case 'list_app':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_category_name","store_id","product_name","product_name_simple","product_code","product_group_id","product_date","images_cover","product_price_buy","product_price_sale","product_price_discount","product_stock","product_show_id","product_show_name","product_status_id","product_status_name","varian_html","varian_text","product_stock_copy");
                    break;
                case 'list_app_detail':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_category_name","store_id","product_name","product_name_simple","product_code","product_group_id","product_date","images_cover","product_price_buy","product_price_sale","product_price_discount","product_stock","product_stock_detail","product_show_id","product_show_name","product_status_id","product_status_name","varian_html","varian_text","product_stock_copy");
                    break;
                case 'list_product_reseller':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_name","product_code","product_group_id","product_date","images_cover","url_product","product_price_buy","product_price_sale","reseller_prices");
                    break;
                case 'front':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_category_id","product_category_name","product_root_category_name","store_id","product_name","product_name_simple","product_code","product_group_id","product_date","product_date_update","product_date_push","images_cover","url_product","product_price_buy","product_price_sale","product_price_discount","product_price_grosir","product_stock","product_stock_detail","product_show_id","product_show_name","product_status_id","product_status_name","product_images","product_description","product_lead","product_brand_id","product_brand_name","product_tags","url_product_category","product_view","product_sold","product_rating","product_review");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result     = array();
            $convToJSON = array();
            $timestamp  = timestamp();
            foreach ($qry->result() as $key => $val) {
                foreach ($val as $key2 => $val2) {
                    if(in_array($key2, $isKey) || $isFull){
                        $result[$key]->$key2 = $val2;
                        $result[$key]->url   = $val->url_product;
                        if(in_array($key2, $convToJSON) && $val2 != ''){
                            $result[$key]->$key2 = json_decode($val2);
                        }

                        if(in_array("product_awards", $isKey) || $isFull){
			                $arr = NULL;
			                if($val->product_awards != ""){
				                $arr = array();
				                $exp = explode(',', $val->product_awards);
				                foreach ($exp as $key3 => $val3) {
				                    $arr[$key3]->product_awards_id   = $val3;
				                    $arr[$key3]->product_awards_name = get_product_awards($val3)->product_awards_name;
				                }
				            }
			                $result[$key]->product_awards = $arr;
			            }

                        if(in_array("product_tags", $isKey) || $isFull){
			                $arr = NULL;
			                if($val->product_tags != ""){
				                $arr = array();
				                $exp = explode(',', $val->product_tags);
				                foreach ($exp as $key3 => $val3) {
				                    $arr[$key3]->product_tags_id   = $val3;
				                    $arr[$key3]->product_tags_name = get_detail_product_tags($val3)->product_tags_name;
				                }
				            }
			                $result[$key]->product_tags = $arr;
			            }

                        if(in_array("product_price_grosir", $isKey) || $isFull){
			                $arr = NULL;
			                if($val->product_price_grosir != ""){
				                $arr = array();
				                $exp = json_decode($val->product_price_grosir);
				                foreach ($exp as $key3 => $val3) {
				                    $arr[$key3]->name   = $val3->name;
				                    $arr[$key3]->qty    = $val3->qty;
				                    $arr[$key3]->price  = $val3->price;
				                }
				            }
			                $result[$key]->product_price_grosir = $arr;
			            }

                        if(in_array("product_stock_detail", $isKey) || $isFull){
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
			                $result[$key]->product_stock_detail = $arr;
			            }

                        if(in_array("varian_html", $isKey) || $isFull){
                            $max_varian = 0;
                            if($val->product_stock_detail != ""){
                                $arr = array();
                                $exp = json_decode($val->product_stock_detail);
                                $li  = '<li style="width: 33%;display:inline-block;vertical-align:top;padding:2px 0px;">';
                                foreach ($exp as $key3 => $val3) {
                                    $max_varian += 1;
                                }
                                $i = 0;
                                $splitli = ceil($max_varian / 3);
                                foreach ($exp as $key3 => $val3) {
                                    $i  += 1;
                                    $li .= '<div>'.$val3->name.': '.($val3->qty>0?$val3->qty:"-").'</div>';
                                    if($i == $splitli){
                                        $i   = 0;
                                        $li .= '</li><li style="width: 33%;display:inline-block;vertical-align:top;padding:2px 0px;">';
                                    }
                                }
                                $li .= '</li>';
                                $varian_html = '<ul style="list-style:none;margin:-10px 5px;padding:0;width:100%;color:#727272;">'.$li.'</ul>';
                            } else {
                                $varian_html = '';
                            }
                            $result[$key]->varian_html = $varian_html;
                        }

                        if(in_array("varian_text", $isKey) || $isFull){
                            $arrNo   = "";
                            $arrName = "";
                            if($val->product_stock_detail != ""){
                                foreach ($exp as $key3 => $val3) {
                                    if($val3->qty > 0){
                                        $arrNo   .= ($arrNo==""?"":",").ltrim($val3->id,"0");
                                        $arrName .= ($arrName==""?"":", ").$val3->name;
                                    }
                                }
                                $varian_text = "";
                                if($val->product_stock_copy == 1){
                                    $varian_text = $val->product_name_simple." Ready No ".$arrNo;
                                } else if($val->product_stock_copy == 2){
                                    $varian_text = $val->product_name_simple." Ready ".$arrName;
                                }
                            } else {
                                $varian_text = "";
                            }
                            $result[$key]->varian_text  = $varian_text;
                        }

                        if(in_array("product_description", $isKey) || $isFull){
			                $product_lead        = NULL;
			                $product_description = NULL;
			                $val3 = get_product_description($val->product_id);
                            $product_lead        = $val3->product_lead;
                            $product_description = $val3->product_description;
			                $result[$key]->product_lead        = $product_lead;
			                $result[$key]->product_description = $product_description;
			            }

                        if(in_array("product_images", $isKey) || $isFull){
			                $arr  = NULL;
			                $get_ = get_image_detail($val->product_id);
			                if(count($get_) > 0){
			                	$arr = array();
				                foreach ($get_ as $key3 => $val3) {
				                    $arr[$key3]->images = $val3->image_filename;
				                }
				            }
			                $result[$key]->product_images = $arr;
			            }

                        if(in_array("reseller_prices", $isKey) || $isFull){
			                $arr  = NULL;
			                $get_ = get_reseller_prices($val->product_id);
			                if(count($get_) > 0){
			                	$arr = array();
				                foreach ($get_ as $key3 => $val3) {
				                    $arr[$key3]->store_id = $val3->store_id;
				                    $arr[$key3]->price    = $val3->price;
				                }
				            }
			                $result[$key]->reseller_prices = $arr;
			            }

                        if(in_array("product_category_name", $isKey) || $isFull){
		                    $result[$key]->product_category_name = get_product_category_name($val->product_category_id);
                        }
                        if(in_array("supplier_name", $isKey) || $isFull){
		                    $result[$key]->supplier_name = get_supplier($val->supplier_id)->supplier_name;
                        }
                        if(in_array("product_brand_name", $isKey) || $isFull){
		                    $result[$key]->product_brand_name = get_product_brand($val->product_brand_id)->product_brand_name;
                        }
                        if(in_array("product_show_id", $isKey) || $isFull){
		                    $result[$key]->product_show_id = $val->product_show_id;
                        }
                        if(in_array("product_show_name", $isKey) || $isFull){
		                    $result[$key]->product_show_name = ($val->product_show_id==1?'Tampil':'Tidak Tampil');
                        }
                        if(in_array("product_status_name", $isKey) || $isFull){
		                    $result[$key]->product_status_name = get_product_status($val->product_status_id)->product_status_name;
                        }
                        if(in_array("product_price_sale", $isKey) || $isFull){
                        	if(isset($p['reseller_id']) && $p['reseller_id'] != "" ){
			                    $result[$key]->product_price_sale = get_reseller_price($p['reseller_id'], $val->product_id);
			                }
                        }
                        if(in_array("images_cover", $isKey) || $isFull){
                            $result[$key]->images_cover = get_cover_image_detail($val->product_id);
                        }
                        if(in_array("product_satuan", $isKey) || $isFull){
                            $product_satuan = get_product_satuan($val->product_satuan_id);
                            $result[$key]->product_satuan_id   = $product_satuan->product_satuan_id;
                            $result[$key]->product_satuan_name = $product_satuan->product_satuan_name;
                            $result[$key]->product_satuan_qty  = $product_satuan->product_satuan_qty;
                        }
                        if(in_array("product_root_category_name", $isKey) || $isFull){
                            $result[$key]->product_root_category_name = get_root_product_category_parent($val->product_category_id);
                        }
                        if(in_array("product_sold_detail", $isKey) || $isFull){
                            $newArr  = NULL;
                            $get_ = array('3','8','2','11','7','5','6','4','9','10','1','21');
                            foreach ($get_ as $key3 => $val3) {
                                $v = get_product_sold_by_source($val->product_id, $val3);
                                $newArr[$key3]->id   = $v->id;
                                $newArr[$key3]->name = $v->name;
                                $newArr[$key3]->qty  = $v->qty;
                            }
                            $result[$key]->product_sold_detail = $newArr;
                        }

                        $day_on_going = xTimeAgo($val->product_date, $timestamp, "d");
                        $result[$key]->day_on_going = $day_on_going;
                    }
                }
            }

            return array(
                    "data"  => $result,
                    "total" => $total
                );
        } else {
            return $qry->num_rows();
        }
    }

    function data_product_by_group($p=array(),$count=FALSE){
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
            $total = $this->data_product_by_group($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("product_group_id","store_id","product_group_name","product_group_date");
                    break;
                case 'list_app':
                    $isFull     = FALSE;
                    $isKey      = array("product_group_id","product_group_name","product_group_date","product_group_item","item_html","varian_text");
                    break;
                case 'list_desktop':
                    $isFull     = FALSE;
                    $isKey      = array("product_group_id","product_group_name","product_group_date","product_group_item","product_sold","item_html","varian_text","varian_date_stock","array_product_id");
                    break;
                default:
                    $isFull     = TRUE;
                    $isKey      = array();
                    break;
            }

            $result = array();
            $iKey = 0;
            $timestamp = timestamp();
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
                    $code       = "";
                    $first_code = "";
                    $last_code  = "";

                    $colgroup = '<colgroup>';
                    $thead    = '<thead><tr>';
                    $tbody    = '<tbody>';
                    $arr  = NULL;
                    $get_ = get_product_by_group($val->product_group_id,1);
                    if(count($get_) > 0){
                        $max_varian  = 0;
                        foreach ($get_ as $key3 => $val3) {
                            if($val3->product_stock_detail != ''){
                                $iVarian = 0;
                                $exp = json_decode($val3->product_stock_detail);
                                foreach ($exp as $key4 => $val4) {
                                    $iVarian += 1;
                                 }
                                 if($max_varian < $iVarian){
                                    $max_varian = $iVarian;
                                 }
                            }
                        }

                        $arr = array();
                        $ii = 1;
                        $arrVarianText = array();
                        $arrVarianDateStock = array();
                        $array_product_id = "";
                        foreach ($get_ as $key3 => $val3) {
                            $arr[$key3]->id     = $val3->product_id;
                            $arr[$key3]->motif  = $val3->product_type_motif;

                            $product_id .= ($product_id==""?"":"-").$val3->product_id;

                            if($images_cover == ""){ $images_cover = get_cover_image_detail($val3->product_id); }
                            if($price_buy == 0){ $price_buy = $val3->product_price_buy; }
                            if($price_sale == 0){ $price_sale = $val3->product_price_sale; }
                            if($price_discount == 0){ $price_discount = $val3->product_price_discount; }
                            if($first_code == ""){ $first_code = $val3->product_code; }
                            $last_code = $val3->product_code;
                            $allcode .= ($allcode == ""?"":" - ").$val3->product_code;
                            $day_on_going = xTimeAgo($val3->product_date, $timestamp, "d");
                            $product_sold += $val3->product_sold;

                            if(in_array("varian", $isKey) || $isFull){
                                $varian = NULL;
                                if($val3->product_stock_detail != ''){
                                    $arr_stock = array();
                                    $exp = json_decode($val3->product_stock_detail);
                                    foreach ($exp as $key4 => $val4) {
                                        $varian[$key4]->id     = $val4->id;
                                        $varian[$key4]->name   = $val4->name;
                                        $varian[$key4]->qty    = $val4->qty;
                                        $varian[$key4]->status = $val4->status;
                                        if($val4->qty > 0){ $total_stock += $val4->qty; }
                                     }
                                }
                                $arr[$key3]->varian = $varian;
                            }
                            if(in_array("varian_qty", $isKey)){
                                $varian = NULL;
                                if($val3->product_stock_detail != ''){
                                    $arr_stock = array();
                                    $exp = json_decode($val3->product_stock_detail);
                                    foreach ($exp as $key4 => $val4) {
                                        // $varian[$key4]->qty    = $val4->qty;
                                        $varian .= ($varian==""?"":",").$val4->qty;
                                        if($val4->qty > 0){ $total_stock += $val4->qty; }
                                     }
                                }
                                $arr[$key3]->varian = $varian;
                            }
                            if(in_array("item_html", $isKey)){
                                $tbody    .= '<tr><td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.$val3->product_type_motif.'</td>';
                                if($val3->product_stock_detail != ''){
                                    $iTotal = 0;
                                    $i = 1;
                                    $iii = 0;
                                    $exp = json_decode($val3->product_stock_detail);
                                    foreach ($exp as $key4 => $val4) {
                                        $iTotal  += $val4->qty;
                                        if($val4->qty > 0){ $total_stock += $val4->qty; }
                                        $tbody    .= '<td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.($val4->qty > 0?$val4->qty:'-').'</td>';
                                        $i += 1;
                                        $iii += 1;
                                    }

                                    if($iii < $max_varian){
                                        $krg = $max_varian - $iii;
                                        for ($iiii=0; $iiii < $krg; $iiii++) {
                                            $tbody    .= '<td style="text-align:center;'.($ii%2==0?'background-color: #E8E9EE;':'').'">-</td>';
                                        }
                                    }

                                    $tbody    .= '<td style="text-align:center;font-weight:bold;'.($ii%2==0?'background-color: #E8E9EE;':'').'">'.$iTotal.'</td>';

                                }
                                $tbody    .= '</tr>';
                            }

                            if(in_array("varian_text", $isKey) || $isFull){
                                $arrNo   = "";
                                $arrName = "";
                                if($val3->product_stock_detail != ""){
                                    $exp = json_decode($val3->product_stock_detail);
                                    foreach ($exp as $key4 => $val4) {
                                        if($val4->qty > 0){
                                            $arrNo   .= ($arrNo==""?"":",").ltrim($val4->id,"0");
                                            $arrName .= ($arrName==""?"":", ").$val4->name;
                                        }
                                    }
                                    $varian_text = "";
                                    if($val3->product_stock_copy == 1){
                                        $varian_text = $val3->product_name_simple." Ready No ".$arrNo;
                                    } else if($val3->product_stock_copy == 2){
                                        $varian_text = $val3->product_name_simple." Ready ".$arrName;
                                    }
                                } else {
                                    $varian_text = "";
                                }

                                $arrVarianText[$key3]->title  = $val3->product_name_simple;
                                $arrVarianText[$key3]->varian = $varian_text;
                            }

                            if(in_array("varian_date_stock", $isKey) || $isFull){
                                $varian_text = $val3->product_name_simple." update ready stok terakhir tanggal: ".convDateTable($timestamp)."<br>";
                                if($val3->product_stock_detail != ""){
                                    $exp = json_decode($val3->product_stock_detail);
                                    foreach ($exp as $key4 => $val4) {
                                        if($val3->product_stock_copy == 1){
                                            $varian_text .= "- No ".ltrim($val4->id,"0")." ".($val4->qty > 0?"":"(HABIS)")."<br>";
                                        } else if($val3->product_stock_copy == 2){
                                            $varian_text .= "- ".$val4->name." ".($val4->qty > 0?"":"(HABIS)")."<br>";
                                        }
                                    }
                                } else {
                                    $varian_text .= "";
                                }

                                $arrVarianDateStock[$key3]->title  = $val3->product_name_simple;
                                $arrVarianDateStock[$key3]->varian = $varian_text;
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
                        for ($i=1; $i <= $max_varian; $i++) {
                            $colgroup .= '<col width="1">';
                            $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">N'.$i.'</th>';
                        }
                        $colgroup .= '<col width="1">';
                        $thead    .= '<th style="white-space:nowrap; text-align:center;background-color:#d1d2d3;">T</th>';

                        $colgroup .= '</colgroup>';
                        $thead    .= '</thead></tr>';
                        $tbody    .= '</tbody>';

                        $item_html = '<table style="width:100%;margin:-5px 0px;">'.$colgroup.$thead.$tbody.'</table>';
                        $result[$iKey]->item_html = $item_html;
                    }
                    if(in_array("varian_text", $isKey) || $isFull){
                        $result[$iKey]->varian_text  = $arrVarianText;
                    }
                    if(in_array("varian_date_stock", $isKey) || $isFull){
                        $result[$iKey]->varian_date_stock  = $arrVarianDateStock;
                    }
                    if(in_array("array_product_id", $isKey) || $isFull){
                        $result[$iKey]->array_product_id  = $array_product_id;
                    }

                    if(in_array("item", $isKey)){
                        $result[$iKey]->item = $arr;
                    }

                    $result[$iKey]->max_varian     = $max_varian;
                    $result[$iKey]->images_cover   = $images_cover;
                    $result[$iKey]->price_buy      = $price_buy;
                    $result[$iKey]->price_sale     = $price_sale;
                    $result[$iKey]->price_discount = $price_discount;
                    $result[$iKey]->total_stock  = $total_stock;
                    $result[$iKey]->day_on_going = $day_on_going;

                    $code = $first_code." - ".$last_code;
                    if($first_code == $last_code){ $code = $first_code; }
                    $result[$iKey]->code = $code;
                    $result[$iKey]->allcode = $allcode;
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
                    $arr = NULL;
                    $max_varian = 0;
                    if($val2->product_stock_detail != ""){
                        $arr = array();
                        $exp = json_decode($val2->product_stock_detail);
                        $li  = '<li style="width: 33%;display:inline-block;vertical-align:top;padding:2px 0px;">';
                        foreach ($exp as $key3 => $val3) {
                            $max_varian += 1;
                        }
                        $i = 0;
                        $splitli = ceil($max_varian / 3);
                        foreach ($exp as $key3 => $val3) {
                            $arr[$key3]->id     = $val3->id;
                            $arr[$key3]->name   = $val3->name;
                            $arr[$key3]->qty    = $val3->qty;
                            $arr[$key3]->status = $val3->status;

                            $i  += 1;
                            $li .= '<div>'.$val3->id.'. '.$val3->name.': '.($val3->qty>0?$val3->qty:"-").'</div>';
                            if($i == $splitli){
                                $i   = 0;
                                $li .= '</li><li style="width: 33%;display:inline-block;vertical-align:top;padding:2px 0px;">';
                            }
                        }
                        $li .= '</li>';
                        $item_html = '<ul style="list-style:none;margin:-10px 5px;padding:0;width:100%;color:#727272;">'.$li.'</ul>';
                    } else {
                        $item_html = '';
                    }

                    if(in_array("item_html", $isKey)){
                        $result[$iKey]->item_html = $item_html;
                    }

                    if(in_array("varian_text", $isKey) || $isFull){
                        $arrNo   = "";
                        $arrName = "";
                        if($val2->product_stock_detail != ""){
                            foreach ($exp as $key3 => $val3) {
                                if($val3->qty > 0){
                                    $arrNo   .= ($arrNo==""?"":",").ltrim($val3->id,"0");
                                    $arrName .= ($arrName==""?"":", ").$val3->name;
                                }
                            }
                            $varian_text = "";
                            if($val2->product_stock_copy == 1){
                                $varian_text = $val2->product_name_simple." Ready No ".$arrNo;
                            } else if($val2->product_stock_copy == 2){
                                $varian_text = $val2->product_name_simple." Ready ".$arrName;
                            }
                        } else {
                            $varian_text = "";
                        }

                        $arrVarianText = array();
                        $arrVarianText[0]->title  = $val2->product_name_simple;
                        $arrVarianText[0]->varian = $varian_text;
                        $result[$iKey]->varian_text  = $arrVarianText;
                    }

                    if(in_array("varian_date_stock", $isKey) || $isFull){
                        $varian_text = $val2->product_name_simple." update ready stok terakhir tanggal: ".convDateTable($timestamp)."<br>";
                        if($val2->product_stock_detail != ""){
                            foreach ($exp as $key3 => $val3) {
                                if($val2->product_stock_copy == 1){
                                    $varian_text .= "- No ".ltrim($val3->id,"0")." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                } else if($val2->product_stock_copy == 2){
                                    $varian_text .= "- ".$val3->name." ".($val3->qty > 0?"":"(HABIS)")."<br>";
                                }
                            }
                        } else {
                            $varian_text .= "";
                        }

                        $arrVarianDateStock = array();
                        $arrVarianDateStock[0]->title  = $val2->product_name_simple;
                        $arrVarianDateStock[0]->varian = $varian_text;
                        $result[$iKey]->varian_date_stock  = $arrVarianDateStock;
                    }

                    if(in_array("item", $isKey)){
                        $result[$iKey]->item = $arr;
                    }

                    if(in_array("array_product_id", $isKey) || $isFull){
                        $result[$iKey]->array_product_id = $val2->product_id;
                    }

                    $result[$iKey]->max_varian = $max_varian;
                }

                $result[$iKey]->images_cover = get_cover_image_detail($val2->product_id);
                $result[$iKey]->price_buy    = $val2->product_price_buy;
                $result[$iKey]->price_sale   = $val2->product_price_sale;
                $result[$iKey]->price_discount = $val2->product_price_discount;
                $result[$iKey]->code         = $val2->product_code;
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

	function data_product_report($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_product.*, mt_product.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_detail.*');
		$this->db->join("mt_product_category","mt_product_category.product_category_id = mt_product.product_category_id",'left');
		$this->db->join("mt_product_detail","mt_product_detail.product_id = mt_product.product_id",'left');
		$this->db->where("product_istrash",0);

		/* where or like conditions */

		if( isset($p['product_status_id']) ){
			$this->db->where("mt_product_detail.product_status_id",$p['product_status_id']);
		}

		// dont modified....
		if($count==FALSE){
			if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
				$p['offset'] = empty($p['offset'])?0:$p['offset'];
				$this->db->limit($p['limit'],$p['offset']);
			}
		}

		$this->db->order_by('mt_product.product_date_push','desc');

		$qry = $this->db->get('mt_product');
		if($count==FALSE){
			$total = $this->data_product_report($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_product_barcode($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('mt_print_barcode.*, mt_product.product_id, mt_product.product_name, mt_product.product_name_simple, mt_product.product_code');
		$this->db->join("mt_product","mt_product.product_id = mt_print_barcode.product_id",'left');


		/* where or like conditions */
		if( isset($p['print_barcode_istrash']) ){
			$this->db->where("mt_print_barcode.print_barcode_istrash",$p['print_barcode_istrash']);
		} else {
			$this->db->where("mt_print_barcode.print_barcode_istrash",0);
		}

		if( isset($p['store_id']) ){
			$this->db->where("mt_print_barcode.store_id",$p['store_id']);
		} else {
			$this->db->where("mt_print_barcode.store_id",1);
		}

		if( isset($p['product_id']) ){
			$this->db->where("mt_product.product_id",$p['product_id']);
		}

		if( isset($p['print_barcode_status']) ){
			$this->db->where("mt_print_barcode.print_barcode_status",$p['print_barcode_status']);
		}

		if( trim($this->jCfg['search']['date_end']) != "" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_print_barcode.print_barcode_date <= '".$this->jCfg['search']['date_end']." 23:59:59' )");
		}

		if( trim($this->jCfg['search']['date_start'])!="" ){
			//debugCode($this->jCfg['search']['date_end']);
			$this->db->where("( mt_print_barcode.print_barcode_date >= '".$this->jCfg['search']['date_start']." 00:00:00' )");
		}

		// dont modified....
		if( trim($this->jCfg['search']['colum'])=="" && trim($this->jCfg['search']['keyword']) != "" ){
			$str_like = "( ";
			$i=0;
			foreach ($p['param'] as $key => $value) {
				if($key != ""){
					$str_like .= $i!=0?"OR":"";
					$str_like .=" ".$key." LIKE '%".$this->jCfg['search']['keyword']."%' ";
					$i++;
				}
			}
			$str_like .= " ) ";
			$this->db->where($str_like);
		}
		if( trim($this->jCfg['search']['colum'])!="" && trim($this->jCfg['search']['keyword']) != "" ){
			$this->db->like($this->jCfg['search']['colum'],$this->jCfg['search']['keyword']);
		}
		if($count==FALSE){
			if( isset($p['offset']) && (isset($p['limit'])&&$p['limit']!='') ){
				$p['offset'] = empty($p['offset'])?0:$p['offset'];
				$this->db->limit($p['limit'],$p['offset']);
			}
		}

		if(trim($this->jCfg['search']['order_by'])!=""){
			$order_by  = $this->jCfg['search']['order_by'];
			$order_dir = $this->jCfg['search']['order_dir'];
			$this->db->order_by($order_by,$order_dir);
		} else {
			$this->db->order_by('mt_print_barcode.print_barcode_date','desc');
		}

		$qry = $this->db->get('mt_print_barcode');
		if($count==FALSE){
			$total = $this->data_product_barcode($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

}