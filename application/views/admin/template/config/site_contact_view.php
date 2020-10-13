<div class="the-box no-border">
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI KONTAK</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Perusahaan</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->contact_name)?$val->contact_name:'';?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo isset($val->contact_desc)?$val->contact_desc:'';?></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Peta Lokasi</label>
                        <div class="col-sm-3">
                            <input id="lat__" type="text" name="contact_lat__" value="Latitude: <?php echo isset($val->contact_lat)?$val->contact_lat:""?>" placeholder="Latitude: " class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input id="lng__" type="text" name="contact_lang__" value="Longitude: <?php echo isset($val->contact_lang)?$val->contact_lang:""?>" placeholder="Longitude: " class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <div id="gmaps" class="gmaps"></div>
                        </div>
                    </div>

                    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyASBO8oYGp2iQJb4oiclUj-fV0137CTbxo" type="text/javascript"></script>
                    <script type="text/javascript">
                        if (GBrowserIsCompatible()) {
                            var gmaps = new GMap2(document.getElementById("gmaps"));
                            gmaps.addControl(new GSmallMapControl());
                            gmaps.addControl(new GMapTypeControl());
                            <?php if(!empty($val->contact_lang) && !empty($val->contact_lat)){?>
                                var center = new GLatLng(<?php echo $val->contact_lat;?>,<?php echo $val->contact_lang;?>);
                                gmaps.setCenter(center, 14);
                            <?php } else { ?>
                                var center = new GLatLng(-1.47204, 119.26968);
                                gmaps.setCenter(center, 4);
                            <?php } ?>

                            geocoder = new GClientGeocoder();
                            var marker = new GMarker(center, {draggable: false});  
                            gmaps.addOverlay(marker);
                        }
                    </script>
                    
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <a href="<?php echo $own_links.'/edit/'.$val->contact_id.'-'.changeEnUrl($val->contact_name);?>"><div class="btn btn-danger">Edit</div></a>
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>