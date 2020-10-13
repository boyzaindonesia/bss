<div class="btn-group" style="float:right; right:50px">
      <button class="btn btn-primary" type="button"> Download File
	        </button>
      <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
        <span class="caret"></span>
        <span class="sr-only">&nbsp;</span>
      </button>
      <ul class="dropdown-menu">
         <li><a href="<?php echo $own_links;?>/export_data?type=xls&status=<?php echo isset($_POST['status'])?$_POST['status']:"" ?>&date_start=<?php echo isset($_POST['date_start'])?$_POST['date_start']:"" ?>&date_end=<?php echo isset($_POST['date_end'])?$_POST['date_end']:"" ?>" target="_blank"><i class="icon-list-alt"></i> Download Excel</a></li>
	     
      </ul>
    </div>

 <br> <br>
 
<div class="well" style="background-color:#fff;"> 

<form id="form-validated" method="post" action="">
    
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="date_start">Tanggal Mulai</label>
          <div class="controls">
          <input type="text"  name="date_start" value="" id="date_start" class="form-control picker">
          </div>
        </div>
        
       </td>
    </tr>
 </table> 
 
 <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="date_end">Tanggal Akhir</label>
          <div class="controls">
          <input type="text"  name="date_end" value="" id="date_end" class="form-control picker">
          </div>
        </div>
        
       </td>
    </tr>
 </table>
 
    <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="status ">Status Pembayaran</label>
          <div class="controls">
          <select name="status" id="status" >
            <option value="">--Pilih--</option>
         	 <option <?php echo $_POST['status']=="ORDERING"?"selected='selected'":""; ?>  value="ORDERING">ORDERING</option>
         	 <option <?php echo $_POST['status']=="CONFIRMATION"?"selected='selected'":""; ?>  value="CONFIRMATION">CONFIRMATION</option>
         	 <option <?php echo $_POST['status']=="PAID"?"selected='selected'":""; ?>  value="PAID">PAID</option> 
          </select>	 
          
          </div>
        </div>
        
       </td>
    </tr>
 </table>
 
  <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="iuran">&nbsp;</label>
          <div class="controls">
         <input type="submit" class="btn btn-primary col-md-5" id="btn_search" name="btn_search" value="Cari !">
  		
          
          </div>
        </div>
        
       </td>
    </tr>
 </table>
 
 
 
 <div style="clear:both"></div>  
 
 
   
</form>

 
 
<h2>Jumlah : <?php echo $jml; ?> Orang</h2> 
<?
 	$total = 0;
 foreach($data as $r){
 	$total = $total + $r->transaction_price;
 }
?>
<h2>Total : <?php echo myNum($total); ?></h2> 
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
     // debugCode($data);
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
  </table>
<?php echo isset($paging)?$paging:'';?>
</div>
<!-- datatable -->



<?php js_picker();?>        
<?php gebo_choosen();?>
