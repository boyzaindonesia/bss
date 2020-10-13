<form action="<?php echo !empty($url_form)?$url_form:"";?>" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
    <div class="btn-toolbar margin-bottom">
        <div class="btn-group col-sm-3 no-padding">
            <input type="text" name="keyword" value="<?php echo $this->jCfg['search']['keyword'];?>" class="form-control" placeholder="Kata Pencarian..."/>
        </div>
        <?php if( count($this->cat_search) > 0 ){?>
        <div class="btn-group col-sm-2 no-padding">
            <select name="colum" class="form-control">
                <?php cat_search($this->cat_search);?>
            </select>
        </div>
        <?php }else{ ?> 
        <input type="hidden" name="colum" value="" />
        <?php } ?>
        <div class="btn-group col-sm-2 no-padding">
            <input type="text" id="datepickerstart" name="date_start" class="form-control" value="<?php echo ($this->jCfg['search']['date_start']!=''?convDatepickerEnc($this->jCfg['search']['date_start']):'') ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Mulai...">
        </div>
        <div class="btn-group col-sm-2 no-padding">
            <input type="text" id="datepickerend" name="date_end" class="form-control" value="<?php echo ($this->jCfg['search']['date_end']!=''?convDatepickerEnc($this->jCfg['search']['date_end']):'') ?>" data-date-format="dd-mm-yyyy" placeholder="Tanggal Akhir...">
        </div>
        <div class="btn-group">
            <input type="submit" name="btn_search" class="btn btn-danger" value="Cari">
        </div>
        <div class="btn-group">
            <input type="submit" name="btn_reset" class="btn btn-warning" value="Atur Ulang !">
        </div>
    </div>
</form>