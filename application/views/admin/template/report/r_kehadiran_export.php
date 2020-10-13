<?php
$type = isset($_GET['type'])?$_GET['type']:'html';
if($type=="xls"){
  $filename = date("Y-m-d")."-kehadiran.xls";
  header('Content-type: application/ms-excel');
  header('Content-Disposition: attachment; filename='.$filename);
}
?>

<html>
<head>
  <title>Report</title>
   <link rel="stylesheet" href="<?php echo themeUrl();?>css/export_style.css" />
</head>
<body>

<h3>Data Kehadiran</h3>

<h2><?php echo $data[0]->detail_trans_nama; ?></h2><br>
  
  <h3>Jumlah : <?php echo $jml; ?> Orang</h
<table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
      <tr>
          <th width="30">No</th>
          <th>ID Transaksi</th>		  
          <th>Peserta</th>
          <th>Status Pembayaran</th>
          <th>Status Hadir</th>
          <th>Realisasi SKP</th>
          <th>Absen 1</th>
          <th>Absen 2</th>
          <th>Absen 3</th>
          
      </tr>
  </thead>
  <tbody>
      <?php
      //debugCode($data);
        if(count($data) > 0){
          $no=0;
          foreach($data as $r){?>
            <tr valign="top">
              <td><?php echo ++$no;?></td>			 
             	<td><?php echo $r->id;?></td>
             	<td><?php echo $r->transaction_member;?></td>
             	<td><?php echo $r->transaction_pay_status;?></td>
             	<td><?php echo $r->detail_status;?></td>
             	<td><?php echo $r->detail_realisasi_skp;?></td>
             	<td><input type="checkbox" name="chk_item_acc[<?php echo $r->id;?>]" id="chk_item_acc_<?php echo $r->id;?>" value="1" class="chk_item_acc" /></td>
             	<td><input type="checkbox" name="chk_item_acc[<?php echo $r->id;?>]" id="chk_item_acc_<?php echo $r->id;?>" value="1" class="chk_item_acc" /></td>
             	<td><input type="checkbox" name="chk_item_acc[<?php echo $r->id;?>]" id="chk_item_acc_<?php echo $r->id;?>" value="1" class="chk_item_acc" /></td>
            
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
  </table>

</body>
</html>