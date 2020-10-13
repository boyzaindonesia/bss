<ul class="sidebar-menu">
    <?php
    if($this->jCfg['user']['level']!="grader"){
        geboMenu($this->jCfg['menu'],true);
    }
    ?>

    <li class="">
        <a href="javascript:void(0);">
            <i class="fa fa-tablet icon-sidebar"></i>
            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
            Aplikasi
        </a>
        <ul class="submenu">
            <li class=""><a href="<?php echo site_url("admin/me/device_apps");?>">Smartphone</a></li>
        </ul>
    </li>

    <li class="">
        <a href="javascript:void(0);">
            <i class="fa fa-external-link-square icon-sidebar"></i>
            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
            Documentation
        </a>
        <ul class="submenu">
            <li class=""><a href="<?php echo site_url("admin/me/user_guide");?>">User guide</a></li>
        </ul>
    </li>
</ul>