                    
                </div><!-- /.container-fluid -->
                
                <footer>
                    &copy; 2017 <a href="<?php echo base_url();?>" target="_blank"><?php echo get_name_app("configuration_name");?></a>. All Rights Reserved.
                </footer>
                
            </div>
        </div>
        <!-- END PAGE -->
       
        <!-- MAIN APPS JS -->
        <script src="<?php echo base_url();?>assets/admin/js/apps.js"></script>
        <script src="<?php echo base_url();?>assets/admin/js/script.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/chat/chat.js"></script>
        <script src="<?php echo base_url();?>assets/plugins/timeago/jquery.timeago.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                <?php if(isset($_GET['swal_type']) && $_GET['swal_type']!=''){ ?>
                    swal({
                        title: "<?php echo $_GET['swal_title'] ?>",
                        html: "<ul><?php echo $_GET['swal_msg'] ?></ul>",
                        type: "<?php echo $_GET['swal_type'] ?>"
                    });
                <?php } ?>
            });
        </script>
    </body>
</html>