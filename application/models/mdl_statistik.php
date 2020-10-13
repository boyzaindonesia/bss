<?php
class mdl_statistik extends CI_Model{

	var $tabel = 'mt_saldo';

	function __construct(){
		parent::__construct();
	}

	function data_statistik($p=array(),$count=FALSE){
		$total = 0;
		/* table conditions */

		$this->db->select('*');
		$this->db->where("orders_source_id",$p['id']);

		/* where or like conditions */

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
			$this->db->order_by('saldo_date','desc');
		}

		$qry = $this->db->get('mt_saldo');
		if($count==FALSE){
			$total = $this->data_statistik($p,TRUE);
			return array(
					"data"	=> $qry->result(),
					"total" => $total
				);
		}else{
			return $qry->num_rows();
		}
	}

	function data_statistik_penjualan($p=array()){
		$return = array();
		$getDateGroup = getDateGroup($p['tipe_date'],$p['date_start'],$p['order_by']);
		$i = 0;
        foreach ($getDateGroup as $k) {
            $total_jual   = 0;
            $total_ongkir = 0;
            $total_beli   = 0;
            $total_barang = 0;
            $orders = get_orders_by_date($p['tipe_date'],$k);
            foreach ($orders as $key => $value){
                $total_jual   = $total_jual + $value->orders_price_grand_total;
                $total_ongkir = $total_ongkir + $value->orders_price_shipping;
                $total_beli   = $total_beli + $value->orders_price_buy_total;

                $orders_detail = get_orders_detail($value->orders_id);
                foreach ($orders_detail as $key2 => $value2){
                    $total_barang = $total_barang + $value2->orders_detail_qty;
                }
            }

            $total_purchase = 0;
            $purchase = get_purchase_by_date($p['tipe_date'],$k);
            foreach ($purchase as $key => $value){
                $total_purchase = $total_purchase + $value->purchase_price_grand_total;
            }

            $total_laba      = $total_jual - $total_ongkir - $total_beli - $total_purchase;
            $total_transaksi = count($orders);

            // $return[$i]['data_orders'] = $orders;
            $return[$i]['date'] = $k;
            $return[$i]['total_jual'] = $total_jual;
            $return[$i]['total_ongkir'] = $total_ongkir;
            $return[$i]['total_beli'] = $total_beli;
            $return[$i]['total_purchase'] = $total_purchase;
            $return[$i]['total_laba'] = $total_laba;
            $return[$i]['total_transaksi'] = $total_transaksi;
            $return[$i]['total_barang'] = $total_barang;
            $i += 1;
        }
        return $return;
	}
}