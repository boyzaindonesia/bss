<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI KONTAK</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Perusahaan <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="contact_name" value="<?php echo isset($val->contact_name)?$val->contact_name:'';?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea name="contact_desc" class="form-control no-resize" rows="6"><?php echo isset($val->contact_desc)?$val->contact_desc:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Peta Lokasi</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input id="lat" type="hidden" name="contact_lat" value="<?php echo isset($val->contact_lat)?$val->contact_lat:""?>">
                                <input id="lng" type="hidden" name="contact_lang" value="<?php echo isset($val->contact_lang)?$val->contact_lang:""?>">
                                <input id="searchLocation" type="text" name="address" class="form-control" value="" />
                                <span class="input-group-btn">
                                    <button id="geocodebutton" type="button" class="btn btn-primary btn-simpan" onclick="showAddress(); return false">
                                        <i class="fa fa-search"></i> Cari Lokasi
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <div id="gmaps" class="gmaps"></div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="contact_id" value="<?php echo isset($val->contact_id)?$val->contact_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Simpan" />
                            <a href="<?php echo $own_links.($val->contact_id!=''?'/view/'.$val->contact_id.'-'.changeEnUrl($val->contact_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
            var marker = new GMarker(center, {draggable: true});  
            gmaps.addOverlay(marker);
            document.getElementById("lat").value = center.lat().toFixed(5);
            document.getElementById('lng').value = center.lng().toFixed(5);

            GEvent.addListener(marker, "dragend", function() {
                var point = marker.getPoint();
                gmaps.panTo(point);
                document.getElementById("lat").value = point.lat().toFixed(5);
                document.getElementById('lng').value = point.lng().toFixed(5);

            });

            GEvent.addListener(gmaps, "moveend", function() {
                gmaps.clearOverlays();
                var center = gmaps.getCenter();
                var marker = new GMarker(center, {draggable: true});
                gmaps.addOverlay(marker);
                document.getElementById("lat").value = center.lat().toFixed(5);
                document.getElementById('lng').value = center.lng().toFixed(5);

                GEvent.addListener(marker, "dragend", function() {
                    var point =marker.getPoint();
                    gmaps.panTo(point);
                    document.getElementById("lat").value = point.lat().toFixed(5);
                    document.getElementById('lng').value = point.lng().toFixed(5);

                });

            });

        }

        function showAddress() {
            var address = document.getElementById('searchLocation').value;
            var gmaps = new GMap2(document.getElementById("gmaps"));
            gmaps.addControl(new GSmallMapControl());
            gmaps.addControl(new GMapTypeControl());
            if (geocoder) {
                geocoder.getLatLng(
                    address,
                    function(point) {
                        if (!point) {
                            alert(address + " not found");
                        } else {
                            document.getElementById("lat").value = point.lat().toFixed(5);
                            document.getElementById('lng').value = point.lng().toFixed(5);
                            gmaps.clearOverlays()
                            gmaps.setCenter(point, 12);
                            var marker = new GMarker(point, {draggable: true});  
                            gmaps.addOverlay(marker);

                            GEvent.addListener(marker, "dragend", function() {
                                var pt = marker.getPoint();
                                gmaps.panTo(pt);
                                document.getElementById('lat').value = pt.lat().toFixed(5);
                                document.getElementById('lng').value = pt.lng().toFixed(5);
                            });

                            GEvent.addListener(gmaps, "moveend", function() {
                                gmaps.clearOverlays();
                                var center = gmaps.getCenter();
                                var marker = new GMarker(center, {draggable: true});
                                gmaps.addOverlay(marker);
                                document.getElementById('lat').value = center.lat().toFixed(5);
                                document.getElementById('lng').value = center.lng().toFixed(5);

                                GEvent.addListener(marker, "dragend", function() {
                                    var pt = marker.getPoint();
                                    gmaps.panTo(pt);
                                    document.getElementById('lat').value = pt.lat().toFixed(5);
                                    document.getElementById('lng').value = pt.lng().toFixed(5);
                                });

                            });

                        }
                    }
                );
            }
        }
    </script>

</div>