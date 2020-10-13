
<div class="the-box no-border">
    <h3 class="panel-title text-uppercase mb-10">Data Penjualan</h3>
    <div class="row">
        <?php foreach ($data_statistik_penjualan as $key => $value){ ?>
        <div class="col-sm-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><?php echo convYearsMonth($value['date']) ?></h3>
                </div>
                <!-- <div class="panel-body"></div> -->
                <div class="table-responsive">
                    <table class="table table-th-block table-success">
                        <tbody>
                            <tr>
                                <td>Total Pemasukan</td>
                                <td><?php echo convertRp($value['total_jual']) ?></td>
                            </tr>
                            <tr>
                                <td>Total Ongkir</td>
                                <td><?php echo convertRp($value['total_ongkir']) ?></td>
                            </tr>
                            <tr>
                                <td>Total Pembelian</td>
                                <td><?php echo convertRp($value['total_beli']) ?></td>
                            </tr>
                            <tr>
                                <td>Total Pengeluaran</td>
                                <td><?php echo convertRp($value['total_purchase']) ?></td>
                            </tr>
                            <tr>
                                <td>Total Laba</td>
                                <td><?php echo convertRp($value['total_laba']) ?></td>
                            </tr>
                            <tr>
                                <td>Total Transaksi</td>
                                <td><?php echo $value['total_transaksi'] ?> transaksi</td>
                            </tr>
                            <tr>
                                <td>Total Barang</td>
                                <td><?php echo $value['total_barang'] ?> item</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="panel-footer"></div> -->
            </div>
        </div>
        <?php } ?>
    </div>
</div>
