<?php js_picker() ?>
<ul class="nav nav-tabs item-color">
    <li class="<?php echo ($tab=='tab1'?'active':'') ?>"><a href="<?php echo $own_links ?>">Add</a></li>
    <li class="<?php echo ($tab=='tab2'?'active':'') ?>"><a href="<?php echo $own_links ?>/list_all">List</a></li>
</ul>


<?php include($content_layout) ?>