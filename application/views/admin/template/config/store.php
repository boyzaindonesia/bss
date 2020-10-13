<div class="the-box no-border">
    <div class="btn-toolbar toolbar-btn-action">
        <?php isset($links)?getLinksBtn($links):'';?>
    </div>

    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI TOKO</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="store_name" value="<?php echo isset($store->store_name)?$store->store_name:""?>" class="form-control" maxlength="255" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Hp <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="store_phone" value="<?php echo isset($store->store_phone)?$store->store_phone:""?>" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Logo</label>
                        <div class="col-sm-5 form-preview-images">
                            <div style="position: relative; display: inline-block;">
                                <?php if( isset($store->store_logo) && trim($store->store_logo)!="" ){?>
                                <img src="<?php echo get_image(base_url()."assets/collections/logo/thumb/dark/".$store->store_logo);?>" class="item-gallery img-responsive return-preview-images">
                                <?php } ?>
                            </div>
                            <div class="input-group" style="margin-top: 20px;">
                                <span class="input-group-btn">
                                    <span class="btn btn-default btn-file">
                                        Browse… <input type="file" name="store_logo" onChange="previewImages(this);" accept="image/*" value="<?php echo isset($store->store_id)?$store->store_id:""?>" />
                                    </span>
                                </span>
                                <input type="text" name="text_images" class="form-control" readonly>
                            </div>
                            <p class="help-block"><?php echo $this->image_size_str;?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Default Produk</label>
                        <div class="col-sm-9">
                            <input type="text" name="store_product" value="<?php echo isset($store->store_product)?$store->store_product:""?>" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ucapan Terima Kasih di label</label>
                        <div class="col-sm-9">
                            <textarea name="store_noted_thanks" class="form-control no-resize" rows="6"><?php echo isset($store->store_noted_thanks)?$store->store_noted_thanks:""?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="store_id" value="<?php echo isset($store->store_id)?$store->store_id:""?>">
                            <input type="submit" class="btn btn-danger" value="Update" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="panel panel-dark panel-block-color">
        <div class="panel-heading">
            <h3 class="panel-title text-uppercase">SOSIAL MEDIA</h3>
        </div>
        <div class="panel-body">
            <div class="btn-toolbar">
                <div class="btn-group margin-bottom">
                    <div class="btn btn-success btn-popup" data-id="0"><i class="fa fa-plus"></i> Tambah</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-th-block table-dark">
                    <colgroup>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col>
                        <col width="1">
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="nobr text-center"><i class="fa fa-sort-amount-asc"></i></th>
                            <th class="nobr text-center">No</th>
                            <th class="nobr text-center">Tipe</th>
                            <th>Nama</th>
                            <th class="nobr text-center">Tampil</th>
                            <th class="nobr text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="dragsort">
                    <?php 
                    $store_sosmed = get_store_sosmed($store->store_id);
                    if(count($store_sosmed) > 0){
                        $i = 1;
                        foreach($store_sosmed as $k => $r){ ?>
                        <tr class="dragsortitem" data-itemid="<?php echo $r->store_sosmed_id;?>">
                            <td class="nobr text-center"><div class="btn btn-drag bg-dark btn-xs" title="Drag to move position"><i class="fa fa-sort"></i></div></td>
                            <td class="nobr text-center"><?php echo $i ?>.</td>
                            <td class="nobr">
                                <?php 
                                $store_cat_sosmed = get_store_cat_sosmed($r->store_cat_sosmed_id);
                                echo $store_cat_sosmed->store_cat_sosmed_name; 
                                ?>
                            </td>
                            <td><?php echo $r->store_sosmed_name;?></td>
                            <td class="nobr text-center">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="switch_sidebar_<?php echo $r->store_sosmed_id ?>" class="onoffswitch-checkbox" id="switch_sidebar_<?php echo $r->store_sosmed_id ?>" onclick="changeStatus(this,'<?php echo $own_links.'/change_status/'.$r->store_sosmed_id;?>')" value="1" <?php if($r->store_sosmed_status == "1"){ echo 'checked'; } ?> >
                                    <label class="onoffswitch-label" for="switch_sidebar_<?php echo $r->store_sosmed_id ?>">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </td>
                            <td class="nobr">
                                <button type="button" class="btn btn-info btn-xs btn-popup" data-id="<?php echo $r->store_sosmed_id;?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-danger btn-xs btn-delete" data-id="<?php echo $r->store_sosmed_id;?>" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                        <?php
                            $i += 1;
                        }
                    } else {
                        echo '<tr><td colspan="6">Tidak ditemukan di database.</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="form-group form-action clearfix">
            <div class="col-sm-9 col-sm-offset-3">
                <a href="<?php echo site_url('admin/me');?>" class="btn btn-default pull-right"><div>Back</div></a>
            </div>
        </div>
    </div>

    <?php js_select2() ?>

    <!-- dragsort -->
    <?php js_dragsort() ?>

</div>

<div class="popup popup-sosmed">
    <!-- <div class="popup-container-close"></div> -->
    <div class="popup-container">
        <div class="popup-close" data-remove-content="true"><div class="btn"><i class="fa fa-times"></i></div></div>
        <div class="popup-content">
            
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {

    $(document).on('click', '.btn-popup', function(e){
        e.preventDefault();
        var $this    = $(this);
        var $thisVal = $this.attr('data-id');
        $('html, body').css('overflow','hidden');
        if($thisVal != ''){
            $.ajax({
                type: 'POST',
                url: OWN_LINKS+'/views',
                data: {'thisVal':$thisVal,'thisAction':'view'},
                async: false,
                cache: false,
                dataType: 'json',
                success: function(data){
                    $('.popup-sosmed').addClass('active');
                    $('.popup-sosmed .popup-content').html(data.content);
                },
                error: function(jqXHR){
                    var response = jqXHR.responseText;
                    swal({
                        title: "Error!",
                        html: response,
                        type: "error"
                    });
                }
            });
        }
    });

    $(document).on('submit', 'form.form_save_sosmed', save_sosmed ); 

    function save_sosmed(e){
        if (typeof e !== 'undefined') e.preventDefault();
        var $this = $(this);
        var form = $this;

        swal({
            title: "Loading!",
            text: "",
            type: "loading",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: 'swal2-small'
        });

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            async: false,
            cache: false,
            dataType: 'json',
            beforeSend: function(){

            },
            success: function(data){
                if(data.err == false){

                    swal({
                        title: "Success!",
                        text: data.msg,
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(
                    function () {},
                    function (dismiss) {
                        // console.log('close');
                        // if (dismiss === 'timer') {
                        //     console.log('I was closed by the timer')
                        // }

                        setTimeout(function(){
                            $('.popup-sosmed .popup-close').trigger('click');
                            window.location.reload(true);
                        },300);
                    });

                } else {
                    swal({
                        title: "Error!",
                        html: data.msg,
                        type: "error"
                    });
                }
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                swal({
                    title: "Error!",
                    html: response,
                    type: "error"
                });
            }
        }); 

        return false;
    }

    $(document).on('click', '.btn-delete', function(e){
        e.preventDefault();
        var thisId  = $(this).attr('data-id');

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {

            $.ajax({
                type: 'POST',
                url: OWN_LINKS+'/deletes',
                data: {'thisId':thisId,'thisAction':'delete'},
                async: false,
                cache: false,
                dataType: 'json',
                success: function(data){
                    swal({
                        title: "Success!",
                        text: data.msg,
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(
                    function () {},
                    function (dismiss) {
                        // if (dismiss === 'timer'){ }
                        setTimeout(function(){
                            window.location.reload(true);
                        },300);
                    });

                },
                error: function(jqXHR){
                    var response = jqXHR.responseText;
                    swal({
                        title: "Error!",
                        html: response,
                        type: "error"
                    });
                }
            });

        }, function (dismiss) {
          // dismiss can be 'cancel', 'overlay',
          // 'close', and 'timer'
          if (dismiss === 'cancel'){ }
        });
    });

});
</script>
