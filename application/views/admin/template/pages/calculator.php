
<?php js_mask() ?>

<div class="the-box no-border">

    <div class="relative clearfix">
        <div class="panel panel-toolbar panel-dark">
            <div class="panel-heading">
                <div class="right-content">
                <button class="btn btn-dark btn-sm btn-rounded-lg to-collapse" data-toggle="collapse" data-target="#panel-collapse-toolbar-2"><i class="fa fa-chevron-down"></i></button>
                </div>
                <h3 class="panel-title">Calculator</h3>
            </div>
            <div id="panel-collapse-toolbar-2" class="collapse" style="height: 0px;">
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label text-left">Harga Normal</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input type="text" name="calc_price_normal" value="0" class="form-control moneyRp_masking" maxlength="23">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" name="calc_percent" value="20" class="form-control" maxlength="4">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">= Rp</span>
                                    <input type="text" name="calc_result" value="0" class="form-control moneyRp_masking" maxlength="4">
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click','input[type="text"]',function(){ this.select(); });
                                $(document).on('keyup change', 'input[name="calc_price_normal"], input[name="calc_percent"]', function(e){
                                    e.preventDefault();
                                    var calc_price_normal = $('input[name="calc_price_normal"]').val();
                                    var calc_percent      = $('input[name="calc_percent"]').val();
                                    var result = 0;
                                    result = (parseInt(convertRpToInt(calc_price_normal)) / (100 - parseInt(calc_percent))) * 100;

                                    $('input[name="calc_result"]').val(result);
                                    $('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
