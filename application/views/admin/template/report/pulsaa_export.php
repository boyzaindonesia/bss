<?php
$type = isset($_GET['type'])?$_GET['type']:'html';
if($type=="xls"){
  $filename = date("Y-m-d")."-transaksi-pulsa.xls";
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

<h3>Data Transaksi PPL</h3>

<h3>Jumlah : <?php echo $jml; ?> Orang</h3> 
<?
 	$total = 0;
 foreach($data as $r){
 	$total = $total + $r->transaction_price;
 }
?>
<h3>Total : <?php echo myNum($total); ?></h3> 

<table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
       <tr>
          <th width="30">No</th>	
          <th>Tanggal</th>
          <th>No Kwitansi</th>    
          <th>Member</th>
          <th>Email</th>
          <th>PPL</th>
          <th>Total</th>
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
              <td><?php echo myDate($r->transaction_date);?></td>
              <td><?php echo $r->transaction_invoice;?></td>
             	<td><?php echo $r->transaction_member;?></td>
             	<td><?php echo $r->transaction_email;?></td>
             	<td><?php echo $r->detail_trans_nama;?></td>
             	<td><?php echo myNum($r->transaction_price);?></td>
              	<td>
              	<?php if($r->transaction_pay_status=="ORDERING"){ 
        		echo "<div align='center' class='alert alert-danger alert-dismissable'>".$r->transaction_pay_status." </div>" ;             
              }elseif($r->transaction_pay_status=="PAID"){
              	echo "<div align='center' class='alert alert-info alert-dismissablee'>".$r->transaction_pay_status." </div>" ; 
              }else{
              	echo "<div align='center' class='alert alert-warning alert-dismissable'>".$r->transaction_pay_status." </div>" ; 
              }
              ?>
               
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
</form> 
  </table>

</body>
</html>