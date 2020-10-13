<?php
class mdl_product_apps extends CI_Model{

	var $tabel = 'mdl_product';

	function __construct(){
		parent::__construct();

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

            $isFull     = FALSE;
            $isKey      = array("product_group_id","product_group_name","product_group_date","product_group_item","item_html","varian_text");

            $result = array();
            $iKey = 0;
            $timestamp = timestamp();
            foreach ($qry->result() as $key => $val) {
                $result[$iKey]->id = $val->product_group_id;
                $result[$iKey]->store_id = $val->store_id;
                $result[$iKey]->name = $val->product_group_name;
                $result[$iKey]->date = $val->product_group_date;
                $result[$iKey]->isGroup = 1;

                if(in_array("product_group_item", $isKey) || $isFull){
                    $total_stock = 0;
                    $product_id  = "";
                    $images_cover = "";
                    $price_buy  = 0;
                    $price_sale = 0;
                    $price_discount = 0;
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
                            $day_on_going = xTimeAgo($val3->product_date, $timestamp, "d");

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
                }

                $iKey += 1;
            }

            $get_product_by_group = get_product_by_group("0","1");
            foreach ($get_product_by_group as $key2 => $val2) {
                $result[$iKey]->id = $val2->product_id;
                $result[$iKey]->store_id = $val2->store_id;
                $result[$iKey]->name = $val2->product_name_simple;
                $result[$iKey]->date = $val2->product_date;
                $result[$iKey]->isGroup = 0;

                $day_on_going = xTimeAgo($val2->product_date, $timestamp, "d");
                $result[$iKey]->day_on_going = $day_on_going;

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

                    $result[$iKey]->max_varian = $max_varian;
                }

                $result[$iKey]->images_cover = get_cover_image_detail($val2->product_id);
                $result[$iKey]->price_buy    = $val2->product_price_buy;
                $result[$iKey]->price_sale   = $val2->product_price_sale;
                $result[$iKey]->price_discount = $val2->product_price_discount;
                $result[$iKey]->code         = $val2->product_code;
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

}