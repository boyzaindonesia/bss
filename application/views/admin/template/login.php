<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

    <head>
    	<!-- Basic -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title><?php echo get_name_app("configuration_name");?> | Login</title>

        <meta name="author" content="dewacode"/>
        <meta name="description" content=""/>
        <meta name="keywords" content="">
        <meta name="Resource-type" content="Document" />
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/collections/images/favicon.ico" />

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

       <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url();?>assets/admin/css/bootstrap.min.css" rel="stylesheet">

        <!-- MAIN CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/admin/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url();?>assets/admin/css/style-responsive.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
        <script src="<?php echo base_url();?>assets/admin/js/jquery.min.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/bootstrap.min.js"></script>

        <!-- PLUGINS -->
        <script src="<?php echo base_url();?>assets/plugins/cookie/jquery.cookie.min.js"></script>

    </head>

    <body class="login tooltips bg-dark">

        <!-- BEGIN PAGE -->
        <div class="login-header text-center">
            <h3>CMS <?php echo get_name_app("configuration_name");?></h3>
        </div>
        <div class="login-wrapper">
            <h4>Login</h4>

            <!-- <div class="alert alert-danger alert-bold-border fade in alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>DEMO!</strong> <br/> Email: admin@admin.com <br/>Password: 123456
            </div> -->

            <?php if(isset($message) && trim($message)!=""){?>
                <div class="alert alert-danger alert-bold-border fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> <?php echo $message;?></div>
            <?php } ?>
            <?php if(isset($msg) && trim($msg)!=""){ $style = !empty($i)?"alert-danger":"alert-success"; $title_msg = !empty($i)?"Error!":"Success!"; ?>
                <div class="alert <?php echo $style;?> alert-bold-border fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><?php echo $title_msg;?></strong> <?php echo $msg;?></div>
            <?php } ?>

            <form id="login_form" class="validator" action="" method="post" enctype="multipart/form-data">
                <div class="form-group has-feedback lg no-label">
                    <input type="text" id="username" name="username" class="form-control no-border input-lg rounded" placeholder="Enter username" autofocus required>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback lg no-label">
                    <input type="password" id="password" name="password" class="form-control no-border input-lg rounded" placeholder="Enter password" minlength="6" required>
                    <span class="fa fa-unlock-alt form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label><input type="checkbox" name="remember"> Ingatkan lagi</label>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" name="login" value="yes">
                    <button type="submit" class="btn btn-danger btn-lg btn-perspective btn-block">LOGIN</button>
                </div>
            </form>
            <p class="text-center"><strong><a href="<?php echo base_url();?>cms/forgot">Lupa password?</a></strong></p>
            <p class="text-center"><strong>atau</strong></p>
            <p class="text-center"><strong><a href="javascript:;">Belum terdaftar?</a> Silahkan hubungi administrator</strong></p>
        </div>
        <!-- END PAGE -->

        <script type="text/javascript">

            $(document).ready(function() {

                // cookies login
                var name_app = '<?php echo changeEnUrl(get_name_app("configuration_name"));?>';
                var remember = $.cookie(name_app+'[remember]');
                if (remember) {
                    $('input[name="username"]').val($.cookie(name_app+'[username]'));
                    $('input[name="password"]').val($.cookie(name_app+'[password]'));
                    $('input[name="remember"]').attr("checked", true);
                }
                $('#login_form').submit(function() {
                    var expires_day = 365;
                    if ($('input[name="remember"]').is(':checked')) {
                        $.cookie(name_app+'[username]', $('input[name="username"]').val(), { expires: expires_day });
                        $.cookie(name_app+'[password]', $('input[name="password"]').val(), { expires: expires_day });
                        $.cookie(name_app+'[remember]', true, { expires: expires_day });
                    } else {
                        $.cookie(name_app+'[username]', '');
                        $.cookie(name_app+'[password]', '');
                        $.cookie(name_app+'[remember]', '');
                    }
                    return true;
                });

            });
        </script>
    </body>
</html>