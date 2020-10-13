
<link href="<?php echo base_url();?>assets/plugins/c3-chart/c3.css" rel="stylesheet">

<?php
    $timestamp = timestamp();
    $dateStart = convDatepickerDec(getMinDay($timestamp, 7));
    $dateEnd   = convDatepickerDec($timestamp);
?>

<div class="the-box no-border">
    <h3 class="panel-title text-uppercase mb-10">Data Penjualan</h3>
    <div class="row">
        <div class="col-sm-12">
            <div class="the-box">
                <h4 class="small-title">PENJUALAN</h4>
                <div class="pull-right">
                    <input type="hidden" id="date_start_orders" name="" value="<?php echo $dateStart ?>">
                    <input type="hidden" id="date_end_orders" name="" value="<?php echo $dateEnd ?>">
                </div>
                <div id="c3-orders" style="height: 300px;"></div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="the-box">
                <h4 class="small-title">PRODUK</h4>
                <div class="pull-right">
                    <input type="hidden" id="date_start_product" name="" value="<?php echo $dateStart ?>">
                    <input type="hidden" id="date_end_product" name="" value="<?php echo $dateEnd ?>">
                </div>
                <div id="c3-product" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- C3 JS -->
<script src="<?php echo base_url();?>assets/plugins/c3-chart/d3.v5.min.js" charset="utf-8"></script>
<script src="<?php echo base_url();?>assets/plugins/c3-chart/c3.js"></script>
<!-- <script src="<?php echo base_url();?>assets/plugins/c3-chart/example.js"></script> -->
<script type="text/javascript">
    $(document).ready(function() {

        var chartOrders = c3.generate({
                bindto: '#c3-orders',
                data: {
                    x : 'x',
                    columns: [
                        ['x', '2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05', '2013-01-06'],
                        ['Grand Total', 0, 0, 0, 0, 0, 0],
                        ['Total Beli', 0, 0, 0, 0, 0, 0],
                        ['Total Jual', 0, 0, 0, 0, 0, 0],
                        ['Total Laba', 0, 0, 0, 0, 0, 0],
                        ['Total Ongkir', 0, 0, 0, 0, 0, 0]
                    ]
                },
                axis : {
                    x : {
                        type : 'timeseries',
                        tick : {
                            format : "%e %b"
                        }
                    }
                }
            });

        function getChartOrders(){
            var dateStartOrders = $("#date_start_orders").val();
            var dateEndOrders   = $("#date_end_orders").val();
            if(dateStartOrders != "" && dateEndOrders != ""){
                $.ajax({
                    type: 'POST',
                    url: MOD_URL+'admin/statistik/ajax_get_orders_report',
                    data: {'date_start':dateStartOrders,'date_end':dateEndOrders,'thisAction':'getdata'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        if(!data.err){
                            console.log(data.result);

                            var colX = [];
                            var colGrandTotal = [];
                            var colTotalBuy = [];
                            var colTotalSale = [];
                            var colTotalLaba = [];
                            var colTotalOngkir = [];
                            $.map(data.result, function (item) {
                                colX.push(item.date);
                                $.map(item.statistik, function (statistik) {
                                    if(statistik.id == "5"){
                                        colGrandTotal.push(statistik.price);
                                    } else if(statistik.id == "6"){
                                        colTotalBuy.push(statistik.price);
                                    } else if(statistik.id == "7"){
                                        colTotalSale.push(statistik.price);
                                    } else if(statistik.id == "10"){
                                        colTotalLaba.push(statistik.price);
                                    } else if(statistik.id == "8"){
                                        colTotalOngkir.push(statistik.price);
                                    }
                                });
                            });

                            setTimeout(function () {
                                chartOrders.load({
                                    columns: [
                                        ['x'].concat(colX),
                                        ['Grand Total'].concat(colGrandTotal),
                                        ['Total Beli'].concat(colTotalBuy),
                                        ['Total Jual'].concat(colTotalSale),
                                        ['Total Laba'].concat(colTotalLaba),
                                        ['Total Ongkir'].concat(colTotalOngkir)
                                    ]
                                });
                            }, 1000);
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        alert('error ajax');
                    }
                });
            }
        }
        getChartOrders();


        var chartProduct = c3.generate({
                bindto: '#c3-product',
                data: {
                    x : 'x',
                    columns: [
                        ['x', '2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05', '2013-01-06'],
                        ['Total Orderan', 0, 0, 0, 0, 0, 0],
                        ['Total Produk', 0, 0, 0, 0, 0, 0],
                        ['Total Item', 0, 0, 0, 0, 0, 0]
                    ]
                },
                axis : {
                    x : {
                        type : 'timeseries',
                        tick : {
                            format : "%e %b"
                        }
                    }
                }
            });

        function getChartProduct(){
            var dateStartProduct = $("#date_start_product").val();
            var dateEndProduct   = $("#date_end_product").val();
            if(dateStartProduct != "" && dateEndProduct != ""){
                $.ajax({
                    type: 'POST',
                    url: MOD_URL+'admin/statistik/ajax_get_orders_report',
                    data: {'date_start':dateStartProduct,'date_end':dateEndProduct,'thisAction':'getdata'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data){
                        if(!data.err){
                            console.log(data.result);

                            var colX = [];
                            var colTotalOrders = [];
                            var colTotalProduct = [];
                            var colTotalItem = [];
                            $.map(data.result, function (item) {
                                colX.push(item.date);
                                $.map(item.statistik, function (statistik) {
                                    if(statistik.id == "1"){
                                        colTotalOrders.push(statistik.qty);
                                    } else if(statistik.id == "2"){
                                        colTotalProduct.push(statistik.qty);
                                    } else if(statistik.id == "3"){
                                        colTotalItem.push(statistik.qty);
                                    }
                                });
                            });

                            setTimeout(function () {
                                chartProduct.load({
                                    columns: [
                                        ['x'].concat(colX),
                                        ['Total Orderan'].concat(colTotalOrders),
                                        ['Total Produk'].concat(colTotalProduct),
                                        ['Total Item'].concat(colTotalItem)
                                    ]
                                });
                            }, 1000);
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        alert('error ajax');
                    }
                });
            }
        }
        getChartProduct();

    });

</script>