<?php get_data_table() ?>

<div class="the-box no-border">
    <form action="<?php echo $own_links;?>/save" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI SHORT LINK</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama <span class="req">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="short_link_name" value="<?php echo isset($val->short_link_name)?$val->short_link_name:'';?>" class="form-control" maxlength="255" required >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kode <span class="req">*</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo $this->short_url;?></span>
                                <input type="text" id="short_link_code" name="short_link_code" value="<?php echo isset($val->short_link_code)?$val->short_link_code:'';?>" class="form-control" maxlength="10" required >
                                <span class="input-group-btn btn-generate-code">
                                    <span class="btn btn-primary">Generate</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Link Eksternal</label>
                        <div class="col-sm-9">
                            <textarea name="short_link_url" class="form-control no-resize" rows="6"><?php echo isset($val->short_link_url)?$val->short_link_url:'';?></textarea>
                        </div>
                    </div>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" name="short_link_id" value="<?php echo isset($val->short_link_id)?$val->short_link_id:'';?>" />
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <a href="<?php echo $own_links.($val->short_link_id!=''?'/view/'.$val->short_link_id.'-'.changeEnUrl($val->short_link_name):'');?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.btn-generate-code', function(e){
                e.preventDefault();
                var $this   = $(this);
                if(!$this.hasClass('loading')){
                    $this.addClass('loading');
                    $.ajax({
                        type: 'POST',
                        url: MOD_URL+"ajax-function/generate_short_link_code",
                        data: {'thisAction':'getdata'},
                        async: false,
                        cache: false,
                        dataType: 'json',
                        success: function(data){
                            if(data.err == false){
                                $('#short_link_code').val(data.msg);
                            }
                            $this.removeClass('loading');
                        },
                        error: function(jqXHR){
                            var response = jqXHR.responseText;
                            swal({
                                title: "Error!",
                                html: response,
                                type: "error"
                            });
                            $this.removeClass('loading');
                        }
                    });
                }

            });
        });
    </script>

</div>