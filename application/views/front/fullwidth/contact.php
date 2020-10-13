<?php include('header.php'); ?>

<!-- Page Content Wraper -->
    <div class="page-content-wraper">
        <!-- Bread Crumb -->
        <section class="breadcrumb">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumb-link">
                            <a href="<?php echo base_url()?>">Home</a>
                            <span><?php echo $this->page ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Bread Crumb -->

        <!-- Page Content -->
        <section class="content-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-xs-30">
                        <h2 class="normal"><span>Stay In Touch</span></h2>
                        <p class="mb-25">Jika kamu mempunyai pertanyaan, silahkan hubungi kami.</p>
                        <h6>Company Address</h6>
                        <p class="mb-25">
                            Depok - Jawa barat
                            <br>
                            ID 16454,
                            <br>
                            Indonesia
                        </p>
                        <h6>Contact Information</h6>
                        <ul class="Contact-information mb-25">
                            <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:<?php echo $configuration->configuration_email ?>"><?php echo $configuration->configuration_email ?></a></li>
                            <li><i class="fa fa-whatsapp" aria-hidden="true"></i><a href="<?php echo $configuration->configuration_whatsapp_link ?>" target="_blank">0<?php echo ltrim($configuration->configuration_whatsapp, "62") ?></a></li>
                        </ul>
                        <hr />
                        <div class="product-share mtb-30">
                            <ul class="list-none">
                                <li><a href="<?php echo $configuration->configuration_fb_link ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_tw_link ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_gplus_link ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_ig_link ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>

                    </div>
                    <!-- <div class="col-md-6 offset-md-2">
                        <h2 class="normal"><span>Contact Us</span></h2>
                        <?php
                        if((isset($msg)&&$msg!='') || isset($_GET['msg'])){
                            $msg   = (isset($_GET['msg'])?$_GET['msg']:$msg);
                            $type  = (isset($_GET['type_msg'])?$_GET['type_msg']:'error');
                            $class = ($type=="success"?'alert-success':'alert-danger');
                            $alert = ($type=="success"?'Success ! ':'Error ! ');
                            echo notifyMessage($class,$alert,$msg);
                        }
                        ?>
                        <form class="Contact-form" action="/send_message" method="post" autocomplete="off" enctype="multipart/form-data">
                            <div class="form-field-wrapper">
                                <label>Nama <span class="required">*</span></label>
                                <input class="input-md form-full-width" name="message_name" value="" size="30" aria-required="true" required="" type="text">
                            </div>
                            <div class="form-field-wrapper">
                                <label>Email <span class="required">*</span></label>
                                <input class="input-md form-full-width" name="message_email" value="" size="30" aria-required="true" required="" type="email">
                            </div>
                            <div class="form-field-wrapper">
                                <label>Hp <span class="required">*</span></label>
                                <input class="input-md form-full-width" name="message_phone" value="" size="30" aria-required="true" required="" type="text">
                            </div>
                            <div class="form-field-wrapper">
                                <label>Subject <span class="required">*</span></label>
                                <input class="input-md form-full-width" name="message_subject" value="" size="30" aria-required="true" required="" type="text">
                            </div>
                            <div class="form-field-wrapper">
                                <label>Pesan <span class="required">*</span></label>
                                <textarea class="form-full-width" name="message_desc" cols="45" rows="8" aria-required="true" required=""></textarea>
                            </div>
                            <div class="form-field-wrapper">
                                <input type="hidden" name="thisAction" value="send_message">
                                <input class="submit btn btn-md btn-color" value="Submit" type="submit">
                            </div>
                        </form>
                    </div> -->
                </div>
            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

<?php include('footer.php'); ?>