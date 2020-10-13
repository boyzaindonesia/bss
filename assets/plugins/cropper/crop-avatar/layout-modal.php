<?php
    $id    = (!empty($_POST['id']))?'temp-'.$_POST['id']:"";
    $title = (!empty($_POST['title']))?$_POST['title']:"Ganti Foto";
    $url   = (!empty($_POST['url']))?$_POST['url']:"#";

    // <div class="col-lg-5 crop-avatar" data-title-crop="Ganti Foto Profile" data-url-crop="">
    //     <div class="avatar-open avatar-view" data-toggle="tooltip" data-original-title="Change the avatar" data-placement="bottom">
    //         <img src="" alt="" class="media-object img-responsive">
    //     </div>
    // </div>
?>
<div class="modal fade crop-avatar-modal" id="<?php echo $id ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="avatar-form" action="<?php echo $url ?>" enctype="multipart/form-data" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $title ?></h4>
                </div>
                <div class="modal-body">
                    <div class="avatar-body">
                        
                        <div class="avatar-upload">
                            <input type="hidden" class="avatar-src" name="avatar_src">
                            <input type="hidden" class="avatar-data" name="avatar_data">
                            <label for="avatarInput">Local upload</label>
                            <input type="file" class="avatar-input" name="avatar_file">
                        </div>

                        <div class="row">
                            <div class="col-md-9">
                                <div class="avatar-wrapper"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="avatar-preview preview-lg"></div>
                                <div class="avatar-preview preview-md"></div>
                                <div class="avatar-preview preview-sm"></div>
                            </div>
                        </div>

                        <div class="row avatar-btns">
                            <div class="col-md-9">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">Rotate Left</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15">-15deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-30">-30deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">-45deg</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">Rotate Right</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="15">15deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="30">30deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">45deg</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block avatar-save">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="loading" tabindex="-1"></div>
</div>