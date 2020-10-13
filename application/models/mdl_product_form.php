<?php
class mdl_product_form extends CI_Model{

	var $tabel = 'mt_product';

	function __construct(){
		parent::__construct();

	}

    function data_form_update_price_multiple($p=array()){
        $user_id     = $p['user_id'];
        $store_id    = $p['store_id'];
        $product_id  = $p['product_id'];

        $arr_name       = "";
        $price_buy      = 0;
        $price_sale     = 0;
        $price_discount = 0;

        $exp = explode("-", $product_id);
        foreach ($exp as $n) {
            $m = $this->db->get_where("mt_product",array(
                'product_id'    => $n
            ),1,0)->row();
            $arr_name .= '<li>'.$m->product_name.'</li>';
            if($price_buy == 0){
                $m2 = $this->db->order_by('product_detail_id','asc')->get_where("mt_product_detail",array(
                    'product_id'  => $m->product_id
                ),1,0)->row();
                $price_buy  = $m2->product_price_buy;
                $price_sale = $m2->product_price_sale;
                $price_discount = $m2->product_price_discount;
            }
        }

        $isiGrosir = "";
        for ($i=0; $i < 5; $i++) {
            $isiGrosir .= '<tr class="">
                            <td class="nobr">
                                <input type="text" name="product_qty_grosir[]" value="" class="form-control" min="1" maxlength="5">
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="product_price_grosir[]" value="" class="form-control moneyRp_masking" maxlength="23">
                                </div>
                            </td>
                        </tr>';
        }

        $result = '<form class="form_save_product" data-id="'.$product_id.'" action="'.base_url().'admin/product/save_update_price_multiple" method="post" autocomplete="off" enctype="multipart/form-data">
                    <legend>Group Produk</legend>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Produk</label>
                            <div class="col-sm-9">
                                <ol>
                                    '.$arr_name.'
                                </ol>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Price Buy</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="product_price_buy" value="" class="form-control moneyRp_masking" maxlength="23" placeholder="'.convertRp2($price_buy).'">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Price Sale</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="product_price_sale" value="" class="form-control moneyRp_masking" maxlength="23" placeholder="'.convertRp2($price_sale).'">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Price Discount</label>
                            <div class="col-sm-5 ">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="product_price_discount" value="" class="form-control moneyRp_masking" maxlength="23" placeholder="'.convertRp2($price_discount).'">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Grosir</label>
                            <div class="col-sm-3">
                                <div class="checkbox">
                                  <label><input type="checkbox" name="chk_product_price_grosir" onclick="enabledPriceGrosir();" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group PriceGrosir" style="display: none;">
                            <label class="col-sm-3 control-label">&nbsp;</label>
                            <div class="col-sm-9 content-PriceGrosir">
                                <div class="table-responsive">
                                    <table class="table table-th-block">
                                        <colgroup>
                                            <col width="1">
                                            <col>
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="nobr text-center" width="130">Qty</th>
                                                <th>Price Grosir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            '.$isiGrosir.'
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-action mb-0">
                            <input type="hidden" name="product_id" value="'.$product_id.'" />
                            <input type="hidden" name="thisAction" value="save" />
                            <button type="submit" name="save_update" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default popup-close" data-remove-content="true">Close</button>
                        </div>
                    </div>
                </form>';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

    function data_form_update_stock_barcode($p=array()){
        $user_id     = $p['user_id'];
        $store_id    = $p['store_id'];
        $result_msg  = $p['result_msg'];

        $result = '
            <form id="form_save_update_stock_barcode" class="form_save_update_stock_barcode" data-id="" action="'.base_url().'admin/product/save_update_stock_barcode" method="post" autocomplete="off" enctype="multipart/form-data">
                <legend>Masukkan Barcode</legend>
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="text" id="product_barcode" name="product_barcode" value="" class="form-control text-uppercase" style="font-size:30px;line-height:36px;height:inherit;" maxlength="50" required>
                        </div>
                        <label class="col-sm-3" style="padding-left:0px;"><button type="submit" class="btn btn-primary">Cari</button></label>
                    </div>
                    <p>Result update terakhir:</p>
                    <div class="form-group no-margin" style="max-height:200px; overflow-x:scroll;">
                        <div class="col-sm-12">
                            <ul id="result_update_stock_barcode">
                                '.$result_msg.'
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group form-action mb-0" style="height:39px;">
                    <input type="hidden" name="thisAction" value="minus" />
                    <button type="button" class="btn btn-default popup-close" data-remove-content="false">Close</button>
                </div>
            </form>
            ';

        return array(
                "data"  => $result,
                "total" => 1
            );
    }

}