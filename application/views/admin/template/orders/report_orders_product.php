<?php js_picker() ?>

<?php
    $timestamp = timestamp();
    $dateStart = convDatepickerDec(getMinDay($timestamp, 0));
    $dateEnd   = convDatepickerDec($timestamp);
    // $dateStart = "2018-09-28";
    // $dateEnd   = "2018-09-28";
?>
<div class="the-box no-border">

    <form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
        <div class="btn-toolbar toolbar-btn-action">
            <div class="pull-right">
                <div class="btn-group mr-10">
                    <input type="text" id="datereportstart" name="date_start" class="form-control" value="<?php echo convDatepickerEnc($dateStart) ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Mulai...">
                </div>
                <div class="btn-group mr-10 mt-5">
                    Sampai
                </div>
                <div class="btn-group mr-10">
                    <input type="text" id="datereportend" name="date_end" class="form-control" value="<?php echo convDatepickerEnc($dateEnd) ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Akhir...">
                </div>
                <div class="btn-group">
                    <input type="button" class="btn btn-danger btn-ajax-get-report" value="Cari">
                </div>

            </div>
        </div>
    </form>

    <div class="row">
        <?php
        for ($i=0; $i <= 5; $i++) {
            switch ($i) {
                case 0: $name = "Jumlah Orderan"; $bg = "warning"; break;
                case 1: $name = "Jumlah Produk"; $bg = "primary"; break;
                case 2: $name = "Total Qty"; $bg = "info"; break;
                case 3: $name = "Harga Beli"; $bg = "success"; break;
                case 4: $name = "Harga Jual"; $bg = "danger"; break;
                case 5: $name = "Laba"; $bg = "dark"; break;
                default: $name = ""; $bg = ""; break;
            }
        ?>
        <div class="col-sm-2">
            <div class="the-box no-border bg-<?php echo $bg ?> tiles-information">
                <a href="javascript:void(0);" class="inherit" style="display: block;">
                    <i class="fa fa-users icon-bg"></i>
                    <div class="tiles-inner text-center">
                        <p style="text-transform: uppercase; min-height: 40px;"><?php echo $name ?></p>
                        <p id="tiles-value-<?php echo $i ?>" class="bolded">0</p>
                    </div>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
    <h3 class="panel-title text-uppercase mb-10">Data Penjualan</h3>
    <div class="relative no-border mt-20">
        <div id="product-list" class="product-list mb-0">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var checkin = $('#datereportstart').datepicker({
          onRender: function(date) {
            // return date.valueOf() < now.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 0);
            checkout.setValue(newDate);
          } else {
            checkout.setValue(checkout.date.valueOf());
          }
          checkin.hide();
          // $('#datereportend')[0].focus();
        }).data('datepicker');
        var checkout = $('#datereportend').datepicker({
          onRender: function(date) {
            return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkout.hide();
        }).data('datepicker');

        $(document).on('click', '.btn-ajax-get-report', function(e){
            e.preventDefault();
            var $this   = $(this);
            if(!$this.hasClass('loading')){
                $this.addClass('loading');
                getChartOrders();
            }
        });
    });

    function getChartOrders(){
        var result = $("#product-list");
        result.html("");
        result.addClass('loading-gif');
        var dateStartReport = $("#datereportstart").val();
        var dateEndReport   = $("#datereportend").val();
        if(dateStartReport != "" && dateEndReport != ""){
            setTimeout(function () {
                $.ajax({
                    type: 'POST',
                    url: MOD_URL+'admin/report/ajax_get_orders_report',
                    data: {'date_start':dateStartReport,'date_end':dateEndReport,'thisAction':'getdata'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        if(!data.err){
                            if(data.result == ""){
                                result.html("Laporan tidak ditemukan...");
                            } else {
                                result.html(data.result);
                            }
                            result.removeClass('loading-gif');
                            $("#tiles-value-0").html(data.total_orders);
                            $("#tiles-value-1").html(data.total_product);
                            $("#tiles-value-2").html(data.total_qty);
                            $("#tiles-value-3").html(data.total_price_buy);
                            $("#tiles-value-4").html(data.total_price_sale);
                            $("#tiles-value-5").html(data.total_laba);
                        } else {
                            alert(data.msg);
                        }
                        $(".btn-ajax-get-report").removeClass('loading');
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        $(".btn-ajax-get-report").removeClass('loading');
                        alert('error ajax');
                    }
                });
            }, 400);
        }
    }
    setTimeout(function () {
        getChartOrders();
    }, 400);

</script>