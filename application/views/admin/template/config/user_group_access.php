<div class="the-box no-border">
    <form action="" method="post" enctype="multipart/form-data">
        <div class="panel panel-dark panel-block-color">
            <div class="panel-heading">
                <h3 class="panel-title text-uppercase">INFORMASI AKSES GRUP</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-th-block table-dark">
                        <colgroup>
                            <col width="1">
                            <col>
                            <col width="1">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="nobr" rowspan="2" >No</th>
                                <th class="nobr" rowspan="2" >Nama Module</th>
                                <th class="nobr text-center" colspan="<?php echo count($actions);?>">Action</th>
                            </tr>
                            <tr style="font-size:11px; font-weight:normal;">
                                <?php if(count($actions)>0){
                                    foreach($actions as $m){
                                        ?>
                                        <th width="10px" style="padding:5px;"><div data-toggle="tooltip" data-original-title="<?php echo $m->ac_action_name;?>"><?php echo $m->ac_action_name;?></div></th>
                                        <?php } 
                                    }
                                    ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($access) > 0){
                                $i = 1;
                                foreach($access as $r){
                                    ?>
                                    <tr>
                                        <td class="nobr text-center"><?php echo $i ?>.</td>
                                        <td><?php echo $r['module_name'];?></td>
                                        <?php 
                                        if(count($r['action'])>0){
                                            foreach($r['action'] as $acc){
                                                if($acc['show']==1){
                                                    $chk = ($acc['value']==1)?'checked="checked"':'';
                                                    $name_chk = "acc_name[".$r['id_module']."][".$acc['id']."]";
                                                    echo "<td align='center'><input type='checkbox' data-toggle='tooltip' data-original-title='".$acc['name']."' $chk name='$name_chk' value='".$acc['id']."' style='cursor:pointer;'></td>";
                                                } else {
                                                    echo "<td align='center'>-</td>";
                                                }
                                            } 
                                        }
                                        ?>
                                    </tr> 
                                    <?php 
                                    $i += 1;
                                    } 
                                }     
                                ?>
                        </tbody>
                    </table>
                    <div class="form-group form-action clearfix">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="submit" name="simpan" class="btn btn-danger" value="Save" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                            <a href="<?php echo $own_links;?>"><div class="btn btn-default pull-right">Back</div></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>