

<div class="btn-group" style="float:right; right:50px">
      <button class="btn btn-primary" type="button"> Download File
	        </button>
      <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
        <span class="caret"></span>
        <span class="sr-only">&nbsp;</span>
      </button>
      <ul class="dropdown-menu">
         <li><a href="<?php echo $own_links;?>/export_data?type=xls&nama_ppl=<?php echo isset($_POST['nama_ppl'])?$_POST['nama_ppl']:"" ?>&topik=<?php echo isset($_POST['topik'])?$_POST['topik']:"" ?>&tanggal=<?php echo isset($_POST['tanggal'])?$_POST['tanggal']:"" ?>" target="_blank"><i class="icon-list-alt"></i> Download Excel</a></li>
	      
      </ul>
    </div>

 <br> <br>
<div class="well" style="background-color:#fff;"> 


<form id="form-validated" method="post" action="">
<!--
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="topik">Topik</label>
          <div class="controls">
            <select name="topik" id="topik" >
            <option value="">--Pilih--</option>
         	 <?php// echo option_katalog(isset($_POST['topik'])?$_POST['topik']:""); ?>
          </select>	 
          </div>
        </div>
        
      
      </td>
    </tr>
 </table> 
 -->  
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="nama_ppl">Nama PPL</label>
          <div class="controls">
            <select name="nama_ppl" style="width:700px" id="nama_ppl" >
            <option value="">--Pilih--</option>
         	 <?php echo option_kelas(isset($_POST['nama_ppl'])?$_POST['nama_ppl']:""); ?>
          </select>	 
          </div>
        </div>
        
                
      </td>
    </tr>
 </table> 
 
     


  <div style="clear:both"></div>  
  
  <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="kota">Kota</label>
          <div class="controls">
           <select name="kota" style="width:180px"   id="kota" >
            <option value="">--Pilih--</option>
         	 <?php echo option_province(isset($_POST['kota'])?$_POST['kota']:""); ?>
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
          <label class="control-label" for="pembayaran">Status Pembayaran</label>
          <div class="controls">
           <select name="pembayaran" style="width:180px"   id="pembayaran" >
            <option value="">--Pilih--</option>
         	<option value="PAID" <?php echo  $_POST['pembayaran']=="PAID"?"selected='selected'":"" ?>>PAID</option>
         	<option value="CONFIRMATION" <?php echo $_POST['pembayaran']=="CONFIRMATION"?"selected='selected'":"" ?>>CONFIRMATION</option> 
         	<option value="ORDERING" <?php echo $_POST['pembayaran']=="ORDERING"?"selected='selected'":"" ?>>ORDERING</option> 
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
          <label class="control-label" for="hadir">Status Hadir</label>
          <div class="controls">
           <select name="hadir" style="width:180px"   id="hadir" >
            <option value="">--Pilih--</option>
         	<option value="Not Started" <?php echo  $_POST['hadir']=="Not Started"?"selected='selected'":"" ?>>Not Started</option>
         	<option value="Completed" <?php echo $_POST['hadir']=="Completed"?"selected='selected'":"" ?>>Completed</option> 
         	
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
          <label class="control-label" for="tanggal">Tanggal PPL</label>
          <div class="controls">
          <input type="text" style="width:80px"  name="tanggal" value="<?php echo isset($_POST['tanggal'])?$_POST['tanggal']:"" ?>" id="tanggal" class="form-control picker"> 
          
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

<form id="form-validated" class="input" action="<?php echo $own_links;?>/upload_nilai" method="post" class="input" enctype="multipart/form-data">  
        <table class="table-form" style="float:right">
            <tr>
				<td>
		<div class="control-group">    
         UPDATE SKP dan ABSEN(.xls)
          <div class="controls">
            <input type="file" name="file_xls" id="file_xls" size="30" />
            <input type="submit" class="btn btn-primary col-md-5" id="btn_search" name="btn_search" value="Upload">
          </div>
        </div>

				
				</td>
				<td></td>
			</tr>
		
		</table>
	</form>
	
<div class="well" style="background-color:#fff;"> 

  <h2><?php echo $data[0]->detail_trans_nama; ?></h2><br>
  
  <h3>Jumlah : <?php echo $jml; ?> Orang</h3>
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
             	<td><input type="checkbox" disabled="disabled" <?php echo $r->absen1==1?"checked='checked'":"" ?> /></td>>
             	<td><input type="checkbox" disabled="disabled" <?php echo $r->absen2==1?"checked='checked'":"" ?> /></td>
             	<td><input type="checkbox" disabled="disabled" <?php echo $r->absen3==1?"checked='checked'":"" ?> /></td>
             
             
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
  </table>
<?php echo isset($paging)?$paging:'';?>
</div>
<!-- datatable -->
<?php js_validate(); ?>
<?php js_picker();?>        
<?php gebo_choosen();?>
