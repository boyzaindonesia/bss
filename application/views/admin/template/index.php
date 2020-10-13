<div class="the-box no-border">
	<div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

	<!-- BEGIN EXAMPLE ALERT -->
	<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<p><strong>Welcome!</strong></p>
		<p class="text-muted">Dihalaman Dashboard Anda memiliki kemampuan untuk melihat snapshot dari aktivitas baru akun anda dan memperbarui informasi akun anda. Pilih link di bawah ini untuk melihat atau mengedit informasi.</p>
	</div>
	<!-- END EXAMPLE ALERT -->

</div>

<div class="row">

	<?php $countProductDetail = getCountProductDetail($this->store_id); ?>
    <div class="col-sm-3">
        <div class="the-box no-border bg-warning tiles-information">
            <a href="javascript:void(0)" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;">BARANG READY</p>
                    <h3 class="bolded"><?php echo $countProductDetail['stock_ready']; ?></h3>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      </div>
                    </div>
                    <p><small>Last Update ( <?php echo timeAgo(timestamp()) ?> )</small></p>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="the-box no-border bg-primary tiles-information">
            <a href="javascript:void(0)" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;">HARGA BELI</p>
                    <h3 class="bolded"><?php echo convertRp($countProductDetail['price_buy']); ?></h3>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      </div>
                    </div>
                    <p><small>Last Update ( <?php echo timeAgo(timestamp()) ?> )</small></p>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="the-box no-border bg-info tiles-information">
            <a href="javascript:void(0)" class="inherit" style="display: block;">
                <i class="fa fa-users icon-bg"></i>
                <div class="tiles-inner text-center">
                    <p style="text-transform: uppercase; min-height: 40px;">HARGA JUAL</p>
                    <h3 class="bolded"><?php echo convertRp($countProductDetail['price_sale']); ?></h3>
                    <div class="progress no-rounded progress-xs">
                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                      </div>
                    </div>
                    <p><small>Last Update ( <?php echo timeAgo(timestamp()) ?> )</small></p>
                </div>
            </a>
        </div>
    </div>


</div>