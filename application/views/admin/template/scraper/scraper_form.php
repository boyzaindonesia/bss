<div class="the-box no-border">
    <div class="btn-toolbar">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form id="ajax_form" action="<?php echo $own_links;?>/save_step_1" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <legend>Input</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Scrape dari</label>
                        <div class="col-sm-9">
                            <?php
                                $scraper_source      = get_source(false,false, $this->uri->segment(4));
                                $scraper_source_id   = $scraper_source['id'];
                                $scraper_source_name = $scraper_source['name'];
                                $scraper_source_url  = $scraper_source['url'];
                            ?>
                            <input type="hidden" name="scraper_source_id" value="<?php echo $scraper_source_id ?>" />
                            <div class="form-control"><?php echo $scraper_source_name ?></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Inject HTML</label>
                        <div class="col-sm-9">
                            <textarea name="scraper_html" class="form-control" rows="6"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <button type="button" class="btn btn-danger btn-test-scrape">Test Scrape</button>
                            <span> Inspect Element > Copy > Copy outerHTML</span>
                            <div id="result_scrape" class="result_scrape" style="display: none;">
                                <span></span>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $(document).on('click', '.btn-test-scrape', function(e){
                                    e.preventDefault();
                                    var $this    = $(this);
                                    var scraper_html  = $('textarea[name="scraper_html"]').val();
                                    var result_scrape = $('#result_scrape');
                                    result_scrape.find('span').prop('outerHTML', scraper_html);
                                    // result_scrape.html(scraper_html);

                                    setTimeout(function(){

                                        var isi = result_scrape.find('.row-shop-product #showcase-container').html();
                                        // $('#result_scrape').html(isi);
                                        alert(isi);
                                    }, 2000);

                                    // jQuery.fn.outerHTML = function(s) {
                                    //     return s
                                    //         ? this.before(s).remove()
                                    //         : jQuery("<p>").append(this.eq(0).clone()).html();
                                    // };
                                });
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Link <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <?php
                                if($scraper_source_id == 1){
                                    $scarp_url = 'https://www.tokopedia.com/butiksasha';
                                    $scarp_url_catalog = 'https://www.tokopedia.com/butiksasha/etalase/pashmina';
                                } else if($scraper_source_id == 2){
                                    $scarp_url = 'https://www.bukalapak.com/u/butiksasha';
                                    $scarp_url_catalog = '';
                                } else if($scraper_source_id == 3){
                                    $scarp_url = 'https://www.shopee.co.id/butiksasha';
                                    $scarp_url_catalog = '';
                                }
                            ?>
                            <input type="text" name="scraper_link" value="<?php echo $scarp_url ?>" class="form-control" required>
                            <div class="scraper-link-help">
                                <span class="help-block">
                                    <?php if($scarp_url != ''){ ?>
                                    <a href="<?php echo $scarp_url ?>" target="_blank"><?php echo $scarp_url ?></a>
                                    <?php } ?>
                                    <?php if($scarp_url != '' && $scarp_url_catalog != ''){ ?>
                                    atau
                                    <?php } ?>
                                    <?php if($scarp_url_catalog != ''){ ?>
                                    <a href="<?php echo $scarp_url_catalog ?>" target="_blank"><?php echo $scarp_url_catalog ?></a>
                                    <?php } ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Toko</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_old_name" value="Butik Sasha" class="form-control" required>
                        </div>
                        <label class="col-sm-1 control-label">No Telp</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_old_phone" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Mulai dari</label>
                        <div class="col-sm-1">
                            <input type="text" name="scraper_start" value="1" class="form-control" onkeypress ="validate(event);">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Jumlah Produk</label>
                        <div class="col-sm-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="chk_scraper_count" value="1" onclick="enabledScraperCount();">
                                    Semuanya
                                </label>
                            </div>
                            <input type="text" name="scraper_count" value="3" class="form-control" onkeypress="validate(event);">
                        </div>
                        <script type="text/javascript">
                            function enabledScraperCount(){
                                if($('input[name="chk_scraper_count"]').is(':checked')){
                                    $('input[name="scraper_count"]').removeAttr('required').attr('disabled','disabled');
                                } else {
                                    $('input[name="scraper_count"]').attr('required','required').removeAttr('disabled').focus();
                                }
                            }
                        </script>
                    </div>

                    <legend>Output</legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Format Upload</label>
                        <div class="col-sm-9">
                            <div class="form-control"><?php echo $scraper_source_name ?></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Toko Baru</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_new_name" value="" class="form-control">
                        </div>
                        <label class="col-sm-1 control-label">No Telp</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_new_phone" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tambah Kata Didepan Judul</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_add_frontname" value="" class="form-control">
                        </div>
                        <label class="col-sm-2 control-label">Dibelakang Judul</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_add_endname" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tambah Kata Didepan Deskripsi</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_add_frontdesc" value="" class="form-control">
                        </div>
                        <label class="col-sm-2 control-label">Dibelakang Deskripsi</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_add_enddesc" value="" class="form-control">
                        </div>
                    </div>

                    <input type="hidden" name="scraper_category" value="" class="form-control">
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label">ID Kategori Produk</label>
                        <div class="col-sm-3">
                            <input type="text" name="scraper_category" value="" class="form-control">
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Foto</label>
                        <div class="col-sm-2">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsFoto" value="1" checked>
                                    Link Original
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsFoto" value="2">
                                    Download ke Local
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Mark Up</label>
                        <div class="col-sm-1">
                            <div class="input-group">
                                <input type="text" name="scraper_markup" value="30" maxlength="4" class="form-control" onkeypress ="validate(event);">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Diskon</label>
                        <div class="col-sm-1">
                            <div class="input-group">
                                <input type="text" name="scraper_discount" value="0" maxlength="3" class="form-control" onkeypress ="validate(event);">
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="scraper_id" value="" />
                            <input type="hidden" name="token" value="<?php echo _encrypt(isset($this->user_id)?$this->user_id:''); ?>" />
                            <button type="submit" class="btn btn-danger">Scrape</button>
                            <div class="btn btn-info btn-scrape-2">Scrape Cara 2</div>
                            <a href="<?php echo $own_links.($val->scraper_id!=''?'/view/'.$val->scraper_id.'-'.changeEnUrl($val->scraper_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style type="text/css">
    .popup-scraper-progress { position: relative; top: 0px; left: 0px; width: 100%; min-height:300px;}
    .popup-scraper-progress .img-scraper-loading { position: absolute; top: 0px; left: 0px; width: 100%; height:100%; background-image: url(<?php echo base_url() ?>assets/admin/images/loader.gif); background-repeat: no-repeat; background-position: center;background-size: contain; }

    .progress-count { position: absolute; top: 25px; right: 0px; font-size: 12px; line-height: 16px; }
    .popup-scraper-result { position: relative; top: 0px; left:0px; width: 100%; height: 230px; overflow-x: scroll; }
    .popup-scraper-result ul { list-style: none; padding: 0px; margin: 0px; }
    .popup-scraper-result ul li { position: relative; top: 0px; left: 0px; background-color: #f1f1f1; padding: 5px; margin-bottom: 3px; }
    .popup-scraper-result ul li .no-product { position: absolute; top: 5px; left: 5px; z-index: 5; }
    .popup-scraper-result ul li .img-product { position: absolute; top: 5px; left: 35px; width: 40px; height: auto; display: block; z-index: 5; }
    .popup-scraper-result ul li .desc-product { position: relative; top: 0px; left: 0px; padding-left: 85px; height: 40px; }
    .popup-scraper-status { position: relative; top: 0px; left: 0px; width: 100%; text-align: center; }
    .popup-scraper-export { position: relative; top: 0px; left: 0px; width: 100%; text-align: center; }
    .popup-scraper-export .img-export-loading { position: relative; top: 0px; left: 50%; margin-left:-25px; width: 50px; height:50px; background-image: url(<?php echo base_url() ?>assets/admin/images/loader.gif); background-repeat: no-repeat; background-position: center;background-size: contain; }
    .popup .popup-container .popup-scraper-export.padding { padding: 25px 0px; }
    .popup .popup-container .popup-scraper-export .popup-close { top:0px; right: 0px; }
</style>

<div class="popup popup-run-scraper">
    <div class="popup-container">
        <div class="popup-content">

        </div>
    </div>
</div>

<div class="popup popup-get-content">
    <div class="popup-container">
        <div class="popup-content">

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.btn-scrape-2', function(e){
            e.preventDefault();
            var $this    = $(this);
            $('.popup-get-content').addClass('active');
            var thisUrl = $('input[name="scraper_link"]').val();

            // $.get( thisUrl, function( data ) {
            //   $('.popup-get-content').html( data );
            //   alert( "Load was performed." );
            // });
            $.ajax({
               url: thisUrl,
               xhrFields: {
                  withCredentials: true
               },
                success: function(data){

                      $('.popup-get-content').html( data );
                }
            });
        });

        var c,r;
        var form = $('#ajax_form');
        var oldTitleBtn = form.find('button[type="submit"]').html();
        var popup_run_scraper = $('.popup-run-scraper');

        $(document).on('submit', '#ajax_form', a );

        function a(e){
            if (typeof e !== 'undefined') e.preventDefault();
            form.find('button[type="submit"]').attr('disabled','disabled').html('Please wait...');
            popup_run_scraper.find('.popup-content').html('<div class="popup-scraper-progress"><div class="img-scraper-loading"></div></div><h4 class="popup-scraper-status">Mohon tunggu, sedang mengumpulkan informasi...</h4><div class="popup-scraper-export"></div>');

            $('html, body').css('overflow','hidden');
            popup_run_scraper.addClass('active');

            setTimeout(function(){
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    async: false,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(d){
                        if(!d.e){
                            r = d.r;
                            c = r.c;
                            popup_run_scraper.find('.popup-scraper-status').html('Sebanyak '+c+' produk siap di scrape. <br/>Scrape data produk dilakukan..');

                            popup_run_scraper.find('.popup-scraper-progress').html('<div class="progress no-rounded progress-striped active relative mb-20"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"><span class="sr-only">0% Complete (danger)</span></div></div><div class="progress-count">0 dari '+c+'</div><div class="relative"><h4>Result Scrape</h4><div class="popup-scraper-result"><ul class="clearfix"></ul></div></div>');

                            alert("Total: "+c);
                            setTimeout(function(){
                                b(r);
                            }, 1000);
                        } else {
                            alert("Message Error: "+d.m);
                            form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);
                            $('html, body').css('overflow','');
                            popup_run_scraper.removeClass('active');
                            setTimeout(function(){
                                popup_run_scraper.find('.popup-content').html("");
                            }, 300);
                        }
                    },
                    error: function(jqXHR){
                        var response = jqXHR.responseText;
                        alert(response);
                        form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);
                        $('html, body').css('overflow','');
                        popup_run_scraper.removeClass('active');
                        setTimeout(function(){
                            popup_run_scraper.find('.popup-content').html("");
                        }, 300);
                    }
                });

            }, 500);

            return false;
        }

        function b(r){
            var n;
            var i = 1;
            var time = 1000;
            $.each(r.p, function(k,v){
                setTimeout( function(){
                    $.ajax({
                        type: 'POST',
                        url: OWN_LINKS+'/save_step_2',
                        data: {t: r.t, h: r.h, p: v, n: n, i: i},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        beforeSend: function(){

                        },
                        success: function(d){
                            if(!d.e){
                                n = d.r.n;
                                $(d.r.l).prependTo(popup_run_scraper.find('.popup-scraper-result ul'));
                            } else {
                                console.log(d.m);
                            }
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            alert(response);
                            form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);
                        }
                    });

                    var percentage = calcPercent(i,r.c);
                    popup_run_scraper.find('.progress-bar').attr('aria-valuenow', percentage).css('width',percentage+'%');
                    popup_run_scraper.find('.progress-bar .sr-only').html(percentage+'% Complete (danger)');
                    popup_run_scraper.find('.progress-count').html(i + ' dari ' + r.c);

                    if(i >= r.c){
                        popup_run_scraper.find('.popup-scraper-status').html('Data produk sudah berhasil di scrape. <br/>Selanjutnya akan dilakukan export data ke .xls atau .csv');
                        setTimeout( function(){
                            popup_run_scraper.find('.popup-scraper-export').html('<div class="img-export-loading"></div><div class="relative text-center">Export data sedang dilakukan...</div>');

                            setTimeout( function(){
                                window.open(MOD_URL+'admin/scraper/export', '_blank');
                                $btn_download = '';
                                if(r.h.optionsFoto == 2){
                                    $btn_download = '<div class="btn btn-info btn-download" style="margin-left:5px;">Download Images</div>';
                                    setTimeout( function(){
                                        window.open(MOD_URL+'admin/scraper/download', '_blank');
                                    }, 1000);
                                }
                                popup_run_scraper.find('.popup-scraper-status').html('Scrape data produk '+r.h.scraper_old_name+' berhasil dilakukan. <br/>Total data produk sebanyak '+r.c);
                                popup_run_scraper.find('.popup-scraper-export').html('<div class="popup-export"><div class="btn btn-primary btn-export">Export xls</div>'+$btn_download+'</div><div class="popup-close" data-remove-content="true"><div class="btn btn-warning">Close</div></div>');
                            }, 1000);

                        }, 1000);
                    }

                    i += 1;
                }, time);
                time += 1500;
            });
        }

        $(document).on('click', '.popup-run-scraper .popup-close', function(e){
            e.preventDefault();
            form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);
        });

        $(document).on('click', '.popup-run-scraper .popup-export .btn-export', function(e){
            e.preventDefault();
            window.open(MOD_URL+'admin/scraper/export', '_blank');
        });

        $(document).on('click', '.popup-run-scraper .popup-export .btn-download', function(e){
            e.preventDefault();
            window.open(MOD_URL+'admin/scraper/download', '_blank');
        });

        function calcPercent(value, total) {
            var jadi = '0';
            if($.isNumeric(value) && $.isNumeric(total)){
                jadi = ((parseInt(value) / parseInt(total)) * 100).toFixed(2);
            }
            return jadi;
        }

    });

</script>
