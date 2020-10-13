<form action="<?php echo !empty($url_form)?$url_form:"";?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
	<div class="btn-toolbar margin-bottom pull-right">
		<div class="btn-group">
            <input type="search" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
		</div>
        <div class="btn-group">
            <input type="submit" name="btn_search" class="btn btn-danger" value="Cari">
        </div>
        <div class="btn-group">
            <input type="submit" name="btn_reset" class="btn btn-warning" value="Atur Ulang !">
        </div>
	</div>
</form>