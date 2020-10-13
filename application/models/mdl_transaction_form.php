<?php
class mdl_transaction_form extends CI_Model{

    var $tabel = 'mt_orders';

    function __construct(){
        parent::__construct();
    }

    function data_transaction_form($p=array()){
        $count_list = 0;
        $count_item = 0;
        $cart_list  = "";

        $user_id       = $p['user_id'];
        $store_id      = $p['store_id'];
        $store_name    = $p['store_name'];
        $store_phone   = $p['store_phone'];
        $store_product = $p['store_product'];

        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();

        $i = 0;
        $orders_source = '';
        $arr_orders_source = get_orders_source();
        foreach ($arr_orders_source as $k => $v) {
            $selected = (($i=='0')||($v->orders_source_id==$r->orders_source_id)?'selected':'');
            $orders_source .= '<option value="'.$v->orders_source_id.'" '.$selected.'>'.$v->orders_source_name.'</option>';
            $i += 1;
        }

        $rs = get_detail_orders_shipping($orders_id);

        $i = 0;
        $shipping_courier = '';
        $get_orders_courier = get_orders_courier();
        foreach ($get_orders_courier as $k => $v) {
            $get_orders_courier2 = get_orders_courier($v->orders_courier_id, true);
            foreach ($get_orders_courier2 as $k2 => $v2) {
                $shipping_courier .= '<option value="'.$v2->orders_courier_id.'" '.(($i=='0')||($r->orders_courier_id==$v2->orders_courier_id)?'selected':'').'>'.$v->orders_courier_name.' - '.$v2->orders_courier_service.'</option>';
                $i += 1;
            }
        }

        $result = '
        <form class="form_save_form_label" action="'.base_url().'admin/transaction_form/save_form_label" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-horizontal">
                <legend>Form Alamat Pengiriman</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Orderan Dari</label>
                    <div class="col-sm-4">
                        <select name="orders_source_id" class="form-control" required>
                            '.$orders_source.'
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="orders_source_invoice" value="'.$r->orders_source_invoice.'" class="form-control text-uppercase" placeholder="No Invoice Bukalapak / Tokopedia">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Kurir</label>
                    <div class="col-sm-4">
                        <select name="orders_courier_id" class="form-control">
                            <option value="" selected>--- Pilih ----</option>
                            '.$shipping_courier.'
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="orders_product_category_title" value="'.($rs->orders_product_category_title!=''?$rs->orders_product_category_title:$store_product).'" class="form-control" placeholder="Isi Paket">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="orders_ship_name" value="'.($rs->orders_ship_name!=''?$rs->orders_ship_name:$store_name).'" class="form-control" placeholder="'.$store_name.'">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="orders_ship_phone" value="'.($rs->orders_ship_phone!=''?$rs->orders_ship_phone:$store_phone).'" class="form-control" placeholder="'.$store_phone.'">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Penerima</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_shipping_name" value="'.$rs->orders_shipping_name.'" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">No Hp</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_shipping_phone" value="'.$rs->orders_shipping_phone.'" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Alamat Lengkap</label>
                    <div class="col-sm-9">
                        <textarea name="orders_shipping_address" class="form-control no-resize" rows="3" maxlength="300" >'.$rs->orders_shipping_address.'</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Kode Booking</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_shipping_resi" value="'.$rs->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Harga Jual di MP</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="text" name="orders_source_price" value="'.$r->orders_source_price.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                        </div>
                    </div>
                    <label class="col-sm-2 control-label">Asuransi</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="text" name="orders_price_insurance" value="'.$r->orders_price_insurance.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Estimasi Ongkir</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">Rp</span>
                            <input type="text" name="orders_price_shipping" value="'.$r->orders_price_shipping.'" class="form-control moneyRp_masking" maxlength="23" placeholder="0">

                        </div>
                    </div>
                    <label class="col-sm-2 control-label">Berat</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" name="orders_shipping_weight" value="'.$rs->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                            <span class="input-group-addon">Kg</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Catatan Order (optional)</label>
                    <div class="col-sm-9">
                        <textarea name="orders_noted" class="form-control no-resize" rows="3" maxlength="300" >'.$r->orders_noted.'</textarea>
                    </div>
                </div>
                <div class="form-group form-action mb-0">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <input type="hidden" name="orders_id" value="'.$r->orders_id.'" />
                        <input type="hidden" name="orders_voucher_price" value="'.($r->orders_voucher_price!=""?$r->orders_voucher_price:0).'" />
                        <input type="hidden" name="thisAction" value="save" />
                        <button type="submit" class="btn btn-primary">Submit</button>
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

    function data_transaction_form_detail_courier($p=array()){
        $count_list = 0;
        $count_item = 0;
        $cart_list  = "";

        $user_id       = $p['user_id'];
        $store_id      = $p['store_id'];
        $store_name    = $p['store_name'];
        $store_phone   = $p['store_phone'];
        $store_product = $p['store_product'];

        $orders_id  = $p['orders_id'];
        $r = $this->db->get_where("mt_orders",array(
            'orders_id '    => $orders_id,
            'store_id '     => $store_id
        ),1,0)->row();

        $rs = get_detail_orders_shipping($r->orders_id);

        $result = '
        <form class="form_save_detail_courier" action="'.base_url().'admin/transaction_form/save_detail_courier" method="post" autocomplete="off" enctype="multipart/form-data">
            <div class="form-horizontal">
                <legend>'.$r->orders_source_invoice.'</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Pengirim</label>
                    <div class="col-sm-4">
                        <input type="text" name="orders_ship_name" value="'.($rs->orders_ship_name!=''?$rs->orders_ship_name:$store_name).'" class="form-control" placeholder="'.$store_name.'">
                    </div>
                    <div class="col-sm-5">
                        <input type="text" name="orders_ship_phone" value="'.($rs->orders_ship_phone!=''?$rs->orders_ship_phone:$store_phone).'" class="form-control" placeholder="'.$store_phone.'">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Nama Penerima</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_shipping_name" value="'.$rs->orders_shipping_name.'" class="form-control" required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Isi Paket</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_product_category_title" value="'.($rs->orders_product_category_title!=''?$rs->orders_product_category_title:$store_product).'" class="form-control" placeholder="Isi Paket">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Kode Booking</label>
                    <div class="col-sm-9">
                        <input type="text" name="orders_shipping_resi" value="'.$rs->orders_shipping_resi.'" class="form-control text-uppercase" placeholder="Kode Booking" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Berat</label>
                    <div class="col-sm-3">
                        <div class="input-group">
                            <input type="text" name="orders_shipping_weight" value="'.$rs->orders_shipping_weight.'" class="form-control" maxlength="2" placeholder="1">
                            <span class="input-group-addon">Kg</span>
                        </div>
                    </div>
                </div>
                <div class="form-group form-action mb-0">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <input type="hidden" name="orders_id" value="'.$r->orders_id.'" />
                        <input type="hidden" name="orders_source_id" value="'.$r->orders_source_id.'" />
                        <input type="hidden" name="orders_courier_id" value="'.$r->orders_courier_id.'" />
                        <input type="hidden" name="thisAction" value="save" />
                        <button type="submit" class="btn btn-primary">Submit</button>
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