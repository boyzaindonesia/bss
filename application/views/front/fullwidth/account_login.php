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
                            <span>Login & Register</span>
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
                    <div class="col-md-6">
                        <div class="form-border-box">
                            <form class="login_form" method="post" enctype="multipart/form-data">
                                <h2 class="normal"><span>Login</span></h2>
                                <p>Jika kamu sudah memiliki akun Butik Sasha, silahkan login.</p>
                                <?php
                                if((isset($msg)&&$msg!='') || isset($_GET['msg'])){
                                    $msg   = (isset($_GET['msg'])?$_GET['msg']:$msg);
                                    $type  = (isset($_GET['type_msg'])?$_GET['type_msg']:'error');
                                    $class = ($type=="success"?'alert-success':'alert-danger');
                                    $alert = ($type=="success"?'Success ! ':'Error ! ');
                                    echo notifyMessage($class,$alert,$msg);
                                }
                                ?>
                                <div class="form-field-wrapper">
                                    <label>Email/Username <span class="required">*</span></label>
                                    <input id="author-email" class="input-md form-full-width" name="member_username" value="" size="30" aria-required="true" required="" type="email">
                                </div>
                                <div class="form-field-wrapper">
                                    <label>Kata sandi <span class="required">*</span></label>
                                    <input id="author-pass" class="input-md form-full-width" name="member_password" value="" size="30" aria-required="true" required="" type="password">
                                </div>
                                <!-- <div class="form-field-wrapper">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="checkbox no-margin">
                                                <label><input type="checkbox" name="member_remember">Ingatkan lagi</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <a href="<?php echo base_url(); ?>reset-password" class="forgot-pass">Lupa kata sandi ?</a>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="form-field-wrapper">
                                    <input type="hidden" name="next" value="<?php echo ($_GET['next']?$_GET['next']:'') ?>" />
                                    <input type="hidden" name="thisAction" value="login" />
                                    <button type="button" class="btn btn-md btn-black btn-popup-buy-marketplace modal-view" data-toggle="modal" data-target="#productModal">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-border-box">
                            <h2 class="normal"><span>New Customers</span></h2>
                            <p>Belum punya akun Butik Sasha? Daftar di sini</p>
                            <div class="form-field-wrapper">
                                <a href="<?php echo base_url(); ?>register" class="btn btn-md btn-color">Create An Account</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Page Content -->

    </div>
    <!-- End Page Content Wraper -->

    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function(){
                $('.btn-popup-buy-marketplace').trigger('click');
            }, 400);
        });
    </script>

<?php include('footer.php'); ?>