<?php
    $timestamp = timestamp();
    $dateStart = convDatepickerDec(getMinDay($timestamp, 7));
    $dateEnd   = convDatepickerDec($timestamp);
?>

    <link href="<?php echo base_url();?>assets/plugins/c3-chart/c3.css" rel="stylesheet">

    <button class="btn-alert">Alert</button>
    <div class="the-box no-border">
        <div class="row">
            <div class="col-sm-12">
                <div class="the-box">
                    <h4 class="small-title">GRAFIK PENJUALAN</h4>
                    <div class="pull-right">
                        <input type="hidden" id="date_start_orders" name="" value="<?php echo $dateStart ?>">
                        <input type="hidden" id="date_end_orders" name="" value="<?php echo $dateEnd ?>">
                    </div>
                    <div id="c3-orders" style="height: 300px;"></div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="the-box">
                    <h4 class="small-title">HISTORY PENJUALAN</h4>
                    <div class="pull-right">
                        <?php
                            $dateStartHistory = convDatepickerDec(getMinDay($timestamp, 7));
                            $dateEndHistory   = convDatepickerDec($timestamp);
                        ?>
                        <input type="hidden" id="date_start_history_orders" name="" value="<?php echo $dateStartHistory ?>">
                        <input type="hidden" id="date_end_history_orders" name="" value="<?php echo $dateEndHistory ?>">
                    </div>
                    <div id="history-orders" class="relative no-border" style="min-height:185px; overflow-x: scroll;"></div>
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
            // $('.btn-alert').on('click', function(){
            //     alert('Click button...');
            //     $(this).html("Click");
            // });

            $(document).on('click', '.btn-alert', function(e){
                e.preventDefault();
                alert('Click button...');
                $(this).html("Click");
            });

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
                var dateStart = $("#date_start_orders").val();
                var dateEnd   = $("#date_end_orders").val();
                if(dateStart != "" && dateEnd != ""){
                    $.ajax({
                        type: 'POST',
                        url: MOD_URL+'apps/ajax_get_orders_report',
                        data: {'date_start':dateStart,'date_end':dateEnd,'thisAction':'getdata'},
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

            function getHistoryOrders(){
                var result = $("#history-orders");
                result.html("");
                result.addClass('loading-gif');
                var dateStart = $("#date_start_history_orders").val();
                var dateEnd   = $("#date_end_history_orders").val();
                if(dateStart != "" && dateEnd != ""){
                    $.ajax({
                        type: 'POST',
                        url: MOD_URL+'apps/ajax_get_history_orders_report',
                        data: {'date_start':dateStart,'date_end':dateEnd,'thisAction':'getdata'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if(!data.err){
                                console.log(data.result);
                                result.html(data.result);
                                result.removeClass('loading-gif');
                            }
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            alert('error ajax');
                        }
                    });
                }
            }
            getHistoryOrders();

        });

    </script>