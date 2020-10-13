<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->supplier_name)?$val->supplier_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Hp</label>
                        <div class="col-sm-5">
                            <div class="form-control"><?php echo isset($val->supplier_phone)?$val->supplier_phone:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Alamat</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php echo $val->supplier_address ?>, <?php echo getCitySet($val->supplier_city_id) ?>, <?php echo getProvSet($val->supplier_province_id) ?> - <?php echo $val->supplier_postal_code ?>
                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">No Rekening</label>
                        <div class="col-sm-9">
                            <div class="form-control">
                                <?php
                                    $supplier_bank = $val->supplier_bank;
                                    if($supplier_bank != ""){
                                        foreach (json_decode($supplier_bank) as $k1 => $v1) {
                                            echo "<strong>".$v1->name."<strong> - ".$v1->bank." ".$v1->norek."</br>";
                                        }
                                    }
                                ?>
                                </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->supplier_id.'-'.changeEnUrl($val->supplier_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>