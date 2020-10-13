<?php
$type = isset($_GET['type'])?$_GET['type']:'html';
if($type=="xls"){
  $filename = date("Y-m-d")."-member.xls";
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

<h3>Data Membership</h3>

<h3>Jumlah : <?php echo $jml; ?> Orang</h3> 

<table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
      <tr>
      	  <th><input type="checkbox" name="select_all_acc" id="select_all_acc" value="1" /></th>	
          <th width="30">No</th>	
          <th>No Reg IAPI</th> 
          <th>No Reg Negara</th> 	  
          <th>Nama Member</th>
          <th>Telp</th>
          <th>Hp</th>
          <th>Email</th>   
          <th>Tempat Lahir</th>
          <th>Tanggal Lahir</th> 
          <th>Kode Pos</th> 
          <th>Alamat</th> 
          <th>Agama</th> 
          <th>Jenis Kelamin</th>  
          <th>Tanggal Daftar</th> 
          <th>Tipe Keanggotaan</th>
          <th>SKP Terstruktur</th>
          <th>SKP Tidak Terstruktur</th>
          <th>Status</th>
      </tr>
  </thead>
  <tbody>
      <?php 
        if(count($data) > 0){
          $no=0;
          foreach($data as $r){?>
            <tr valign="top">
              <td><input type="checkbox" name="chk_item_acc[<?php echo $r->id;?>]" id="chk_item_acc_<?php echo $r->id;?>" value="1" class="chk_item_acc" /></td>
              <td><?php echo ++$no;?></td>
              <td><?php echo $r->no_reg_iapi;?></td>
              <td><?php echo $r->no_reg_neg;?></td>			 
              <td><?php echo $r->nama;?></td>
              <td><?php echo $r->telpon;?></td>
              <td><?php echo $r->hp;?></td>
              <td><?php echo $r->email;?></td>
              <td><?php echo $r->tempat_lahir;?></td>
              <td><?php echo $r->tgl_lahir;?></td>
              <td><?php echo $r->kodepos;?></td>
              <td><?php echo $r->alamat;?></td>
              <td><?php echo $r->agama;?></td>
              <td><?php echo $r->jenis_kelamin;?></td>
              <td><?php echo $r->tanggal_daftar;?></td>
             	<td><?php echo get_kategori_nama($r->kategori);?></td>
             	<td><?php echo get_skp_wajib($r->id,date('Y')); ?></td>
              <td><?php echo get_skp_non_wajib($r->id,date('Y')); ?></td>
              <td><?php echo $r->status;?></td>
            
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
</form> 
  </table>

</body>
</html>