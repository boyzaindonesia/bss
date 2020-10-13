<div class="the-box no-border">

    <form action="<?php echo $own_links;?>/save_edit_sku" method="post" enctype="multipart/form-data" autocomplete="off">

        <?php
        if(isset($_GET['id']) && $_GET['id']!=''){
            $id   = $_GET['id'];
            $exp  = explode("-", $id);
            foreach ($exp as $n) {
                $product_id = $n;
                $product = get_detail_product($product_id);
                $mp = get_product_mp($product_id);
                ?>
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase"><?php echo $product->product_name ?> - <?php echo $product->product_code ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <input type="hidden" name="product_id[]" value="<?php echo isset($product->product_id)?$product->product_id:'';?>" />
                    <input type="hidden" name="product_mp_id[]" value="<?php echo isset($mp->product_mp_id)?$mp->product_mp_id:'';?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tokopedia</label>
                        <div class="col-sm-5">
                            <input type="text" name="product_mp_tokped[]" value="<?php echo isset($mp->product_mp_tokped)?$mp->product_mp_tokped:'';?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Bukalapak</label>
                        <div class="col-sm-5">
                            <input type="text" name="product_mp_bulap[]" value="<?php echo isset($mp->product_mp_bulap)?$mp->product_mp_bulap:'';?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Shopee</label>
                        <div class="col-sm-5">
                            <input type="text" name="product_mp_shopee[]" value="<?php echo isset($mp->product_mp_shopee)?$mp->product_mp_shopee:'';?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Lazada</label>
                        <div class="col-sm-5">
                            <input type="text" name="product_mp_lazada[]" value="<?php echo isset($mp->product_mp_lazada)?$mp->product_mp_lazada:'';?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        } ?>

        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <input type="hidden" name="next" value="<?php echo $this->input->get("next"); ?>" />
                <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                <input type="reset" name="reset" class="btn btn-default" value="Reset" />
                <?php if($this->input->get("next")!=""){ ?>
                <a href="<?php echo $this->input->get("next") ?>"><div class="btn btn-default pull-right">Back</div></a>
                <?php } else { ?>
                <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                <?php } ?>
            </div>
        </div>

    </form>

</div>