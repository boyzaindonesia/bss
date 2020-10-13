<?php
$type = isset($_GET['type'])?$_GET['type']:'html';
if($type=="xls"){
  $filename = date("Y-m-d")."-piutang.xls";
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
<?
 	$total = 0;
 foreach($data as $r){
 	$total = $total + $r->jumlah;
 }
?>
<h3>Total : <?php echo myNum($total); ?></h3> 

<table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
       <tr>
          <th width="30">No</th>	
          <th>No Reg IAPI</th> 
          <th>Nama Member</th>
          <th>Keanggotaan</th>
          <th>kategori Iuran</th>
          <th>Invoice</th>    
          <th>Periode</th>
          <th>Tahun</th>
          <th>Status Pembayaran</th>

      </tr>
  </thead>
  <tbody>
      <?php
        if(count($data) > 0){
          $no=0;
          foreach($data as $r){?>
            <tr valign="top">
              <td><?php echo ++$no;?></td>
              <td><?php echo $r->no_reg_iapi;?></td>
              <td><?php echo $r->nama;?></td>
              <td><?php echo get_kategori_nama($r->kategori);?></td>
              <td><?php echo $r->jenis;?></td>
              <td><?php echo $r->kwintansi;?></td>
              <td><?php echo $r->periode_mulai;?> s/d <?php echo $r->periode_selesai;?></td>
             
              <td><?php echo $r->tahun;?></td>
              <td><?php echo $r->status=="0"?"Belum Bayar":"Lunas";?></td>
              
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
</form> 
  </table>

</body>
</html>