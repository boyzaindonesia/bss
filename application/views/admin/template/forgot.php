<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

    <head>
    	<!-- Basic -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title><?php echo get_name_app("configuration_name");?> | Lupa Password</title>

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
            <h4>Lupa Password</h4>

            <?php if(isset($message) && trim($message)!=""){?>
                <div class="alert alert-danger alert-bold-border fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> <?php echo $message;?></div>
            <?php } ?>
            <?php if(isset($msg) && trim($msg)!=""){ $style = !empty($i)?"alert-danger":"alert-success"; $title_msg = !empty($i)?"Error!":"Success!"; ?>
                <div class="alert <?php echo $style;?> alert-bold-border fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong><?php echo $title_msg;?></strong> <?php echo $msg;?></div>
            <?php } ?>

            <form class="validator" action="" method="post" enctype="multipart/form-data">
                <div class="form-group has-feedback lg no-label">
                    <input type="email" name="email" class="form-control no-border input-lg rounded" placeholder="Enter your email address" autofocus required>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <input type="hidden" name="forgot" value="yes">
                    <button type="submit" class="btn btn-danger btn-lg btn-perspective btn-block">RESET PASSWORD</button>
                </div>
            </form>
            <p class="text-center"><strong><a href="<?php echo base_url();?>cms">Kembali ke login</a></strong></p>
        </div>
        <!-- END PAGE -->

    </body>
</html>