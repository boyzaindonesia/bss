<?php
class mdl_product_archive extends CI_Model{

	var $tabel = 'mt_product_archive';

	function __construct(){
		parent::__construct();

	}

	function data_product_archive($p=array(),$count=FALSE){
        $total = 0;
        /* table conditions */

		$this->db->select('mt_product_archive.*, mt_product_archive.url as url_product, mt_product_category.*, mt_product_category.url as url_product_category, mt_product_archive_detail.*');
		$this->db->join("mt_product_category","mt_product_category.product_category_id = mt_product_archive.product_category_id",'left');
		$this->db->join("mt_product_archive_detail","mt_product_archive_detail.product_id = mt_product_archive.product_id",'left');

        /* where or like conditions */
        if( isset($p['product_istrash']) && $p['product_istrash'] != "" ){
			$this->db->where("mt_product_archive.product_istrash",$p['product_istrash']);
		} else {
			$this->db->where("mt_product_archive.product_istrash",0);
		}

		if( isset($p['store_id']) && $p['store_id'] != "" ){
			$this->db->where("store_id",$p['store_id']);
		}

		if( isset($p['product_id']) && $p['product_id'] != "" ){
			$this->db->where("mt_product_archive.product_id",$p['product_id']);
		}

        if( isset($p['product_group_id']) && $p['product_group_id'] != "" ){
            if($p['product_group_id'] == "not_group"){
                $this->db->where("mt_product_archive.product_group_id","0");
            } else {
                $this->db->where("mt_product_archive.product_group_id",$p['product_group_id']);
            }
        }

		if( isset($p['product_status_id']) && $p['product_status_id'] != "" ){
			$this->db->where("mt_product_archive_detail.product_status_id",$p['product_status_id']);
		}

		if( isset($p['product_category_id']) && $p['product_category_id'] != "" ){
			$this->db->where("mt_product_archive.product_category_id",$p['product_category_id']);
		}

        if(isset($p['product_awards_id']) && $p['product_awards_id'] != ''){
            $this->db->where_in("mt_product_archive.product_awards", $p['product_awards_id']);
        }

        if(isset($p['product_tags_id']) && $p['product_tags_id'] != ''){
            $this->db->where_in("mt_product_archive.product_tags", $p['product_tags_id']);
        }

        if( isset($p['product_brand_id']) && $p['product_brand_id'] != "" ){
            $this->db->where("mt_product_archive.product_brand_id",$p['product_brand_id']);
        }


        if( trim($p['date_end']) != "" ){
            $this->db->where("( mt_product_archive.product_date <= '".$p['date_end']." 23:59:59' )");
        }

        if( trim($p['date_start'])!="" ){
            $this->db->where("( mt_product_archive.product_date >= '".$p['date_start']." 00:00:00' )");
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
            $this->db->order_by('mt_product_archive.product_date_archive','desc');
        }

        $qry = $this->db->get('mt_product_archive');
        if($count==FALSE){
            $total = $this->data_product_archive($p,TRUE);

            $type_result = "fullresult";
            if(isset($p['type_result']) && $p['type_result'] != ""){
                $type_result = $p['type_result'];
            }
            switch ($p['type_result']) {
                case 'list':
                    $isFull     = FALSE;
                    $isKey      = array("product_id","product_category_name","product_root_category_name","store_id","product_name","product_name_simple","product_code","product_awards","product_group_id","product_date","product_date_update","product_date_push","product_date_archive","images_cover","url_product","product_price_buy","product_price_sale","product_price_discount","product_price_grosir","product_stock","product_stock_detail","product_show_id","product_show_name","product_status_id","product_status_name","url_product_category","product_stock_copy");
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

               //          if(in_array("product_description", $isKey) || $isFull){
			            //     $product_lead        = NULL;
			            //     $product_description = NULL;
			            //     $get_ = get_product_description($val->product_id);
			            //     foreach ($get_ as $key3 => $val3) {
			            //         $product_lead        = $val3->product_lead;
			            //         $product_description = $val3->product_description;
			            //     }
			            //     $result[$key]->product_lead        = $product_lead;
			            //     $result[$key]->product_description = $product_description;
			            // }

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

}