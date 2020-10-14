        <!-- Footer Section -->
        <footer class="footer section-padding">
            <!-- Footer Info -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-12 col-sm-12 mb-sm-45">
                        <div class="footer-block about-us-block">
                            <img src="<?php echo base_url();?>assets/front/images/logo_white_small.png" class="" alt="">
                            <p>Butik Sasha adalah Toko Online Hijab yang menjual bermacam-macam jilbab berkualitas premium dengan harga terjangkau.</p>
                            <ul class="footer-social-icon list-none-ib">
                                <li><a href="<?php echo $configuration->configuration_fb_link ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_tw_link ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_gplus_link ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="<?php echo $configuration->configuration_ig_link ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 mb-sm-45">
                        <div class="footer-block information-block">
                            <h6>Information</h6>
                            <ul>
                                <li><a href="<?php echo base_url() ?>about">Tentang Kami</a></li>
                                <li><a href="<?php echo base_url() ?>how-to-buy">Cara Berbelanja</a></li>
                                <li><a href="<?php echo base_url() ?>legal-information/terms-and-conditions">Syarat dan Ketentuan</a></li>
                                <li><a href="<?php echo base_url() ?>legal-information/privacy-policy">Kebijakan Privasi</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 mb-sm-45">
                        <div class="footer-block links-block">
                            <h6>My Account</h6>
                            <ul>
                                <li><a href="<?php echo base_url() ?>account">My Account</a></li>
                                <li><a href="<?php echo base_url() ?>list-orders">List Order</a></li>
                                <li><a href="<?php echo base_url() ?>wishlist">Wishlist</a></li>
                                <li><a href="<?php echo base_url() ?>confirm-payment">Konfirmasi Pembayaran</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4 mb-sm-45">
                        <div class="footer-block extra-block">
                            <h6>Marketplace</h6>
                            <ul>
                                <li><a href="<?php echo $configuration->configuration_shopee_link ?>" target="_blank">Shopee</a></li>
                                <li><a href="<?php echo $configuration->configuration_tokped_link ?>" target="_blank">Tokopedia</a></li>
                                <li><a href="<?php echo $configuration->configuration_bl_link ?>" target="_blank">Bukalapak</a></li>
                                <li><a href="<?php echo $configuration->configuration_laz_link ?>" target="_blank">Lazada</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <div class="footer-block contact-block">
                            <h6>Contact</h6>
                            <ul>
                                <li><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo $configuration->configuration_alamat ?></li>
                                <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:<?php echo $configuration->configuration_email ?>"><?php echo $configuration->configuration_email ?></a></li>
                                <li><i class="fa fa-whatsapp" aria-hidden="true"></i><a href="<?php echo $configuration->configuration_whatsapp_link ?>" target="_blank">0<?php echo ltrim($configuration->configuration_whatsapp, "62") ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer Info -->

            <!-- Footer Newsletter -->
            <div class="container">
                <div class="footer-newsletter">
                    <h4>Subscribe Newsletter</h4>
                    <form class="form-newsletter footer-newslettr-inner" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                        <input type="email" name="email" class="input-md fancy" title="Masukan Email.." placeholder="Masukan Email.." required>
                        <button type="submit" class="btn btn-md btn-color fancy">Subscribe</button>
                    </form>
                </div>
            </div>
            <!-- End Footer Newsletter -->

            <!-- Footer Copyright -->
            <div class="container">
                <div class="copyrights">
                    <p class="copyright">Copyright © 2018 - <?php echo date("Y"); ?> <a href="<?php echo base_url() ?>">Butik Sasha</a>. All Rights Reserved.</p>
                    <p class="payment">
                        <img src="<?php echo base_url();?>assets/front/img/payment_logos.png" alt="payment">
                    </p>
                </div>
            </div>
            <!-- End Footer Copyright -->
        </footer>
        <!-- End Footer Section -->

    </div>
    <!-- End wrapper =============================-->

    <!--Quickview Product Start -->
    <div id="quickview-wrapper">
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-product product-details-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="msg-marketplace" style="display:none;">
        <?php include('inc/msg_buy_to_marketplace.php'); ?>
    </div>

    <?php echo get_temp_div_cart_all_total(); ?>

    <div class="loading-body"><div></div></div>

    <!--==========================================-->
    <!-- JAVASCRIPT -->
    <!--==========================================-->

    <!-- bootstrap js -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/owl.carousel.js"></script>
    <!-- OWL carousel js -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/slick.js"></script>
    <!-- Slick Slider js -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/plugins/plugins-all.js"></script>
    <!-- Plugins All js -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/custom.js"></script>
    <!-- custom js -->
    <!-- end jquery -->

    <!-- Dewacode JS
    ============================================ -->
    <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/dewacode/sosmed.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/dewacode/shop.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/dewacode/script.js"></script>

    <!-- end jquery -->
    <script type="text/javascript">

        $(document).ready(function() {

            <?php if($this->jCfg['member']['member_login']==0){ ?>
                // cookies login
                var name_app = 'front_<?php echo changeEnUrl(get_name_app("configuration_name"));?>';
                var member_remember = $.cookie(name_app+'[member_remember]');
                if (member_remember) {
                    $('input[name="member_username"]').val($.cookie(name_app+'[member_username]'));
                    $('input[name="member_password"]').val($.cookie(name_app+'[member_password]'));
                    $('input[name="member_remember"]').attr("checked", true);
                }
                $('.login_form').submit(function() {
                    var expires_day = 365;
                    if ($(this).find('input[name="member_remember"]').is(':checked')) {
                        $.cookie(name_app+'[member_username]', $(this).find('input[name="member_username"]').val(), { expires: expires_day });
                        $.cookie(name_app+'[member_password]', $(this).find('input[name="member_password"]').val(), { expires: expires_day });
                        $.cookie(name_app+'[member_remember]', true, { expires: expires_day });
                    } else {
                        $.cookie(name_app+'[member_username]', '');
                        $.cookie(name_app+'[member_password]', '');
                        $.cookie(name_app+'[member_remember]', '');
                    }
                    return true;
                });
            <?php } ?>

        });
    </script>

</body>
</html>