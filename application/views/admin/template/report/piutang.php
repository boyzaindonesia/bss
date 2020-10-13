<div class="btn-group" style="float:right; right:50px">
      <button class="btn btn-primary" type="button"> Download File
	        </button>
      <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
        <span class="caret"></span>
        <span class="sr-only">&nbsp;</span>
      </button>
      <ul class="dropdown-menu">
         <li><a href="<?php echo $own_links;?>/export_data?type=xls&status=<?php echo isset($_POST['status'])?$_POST['status']:"" ?>&tahun=<?php echo isset($_POST['tahun'])?$_POST['tahun']:"" ?>&kategori=<?php echo isset($_POST['kategori'])?$_POST['kategori']:"" ?>&iuran=<?php echo isset($_POST['iuran'])?$_POST['iuran']:"" ?>&no_reg_iapi=<?php echo isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:"" ?>" target="_blank"><i class="icon-list-alt"></i> Download Excel</a></li>
	     
      </ul>
    </div>

 <br> <br>
 
<div class="well" style="background-color:#fff;"> 

<form id="form-validated" method="post" action="">
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">No. Reg Iapi</label>
          <div class="controls">
            <input type="text" id="no_reg_iapi" name="no_reg_iapi" class="input-medium" value="<?php echo isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:"" ?>"/>
          </div>
        </div>
        
                
      </td>
    </tr>
 </table> 
    
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="kategori">Tipe Keanggotaan</label>
          <div class="controls">
          <select name="kategori" id="kategori" >
            <option value="">--Pilih--</option>
         	 <?php echo option_nama_keanggotaan(isset($_POST['kategori'])?$_POST['kategori']:""); ?>
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
          <label class="control-label" for="iuran">Jenis Iuran</label>
          <div class="controls">
          <select name="iuran" id="iuran" >
            <option value="">--Pilih--</option>
         	 <?php echo option_iuran(isset($_POST['iuran'])?$_POST['iuran']:""); ?>
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
          <label class="control-label" for="tahun">Tahun</label>
          <div class="controls">
          <select name="tahun" id="tahun" >
            <option value="">--Pilih--</option>
         	 <?php for($i=0; $i <=5; $i++){ 
         	 	$date = date('Y') - $i;
         	 ?>
         	 
         	 	<option <?php echo $_POST['tahun']==$date?"selected='selected'":""; ?>  value="<?php echo $date;?>"><?php echo $date;?></option>
         	 <?php } ?>
         	 
         	 
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
          <label class="control-label" for="status ">Status Pembayaran</label>
          <div class="controls">
          <select name="status" id="status" >
            <option value="">--Pilih--</option>
         	 	<option <?php echo $_POST['status']=="0"?"selected='selected'":""; ?>  value="0">Belum Bayar</option>
         	 	<option <?php echo $_POST['status']=="1"?"selected='selected'":""; ?>  value="1">Lunas</option>
   
         	 
         	 
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
 	$total = $total + $r->jumlah;
 }
?>
<h2>Total : <?php echo myNum($total); ?></h2> 
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
      //debugCode($data);
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
  </table>
<?php echo isset($paging)?$paging:'';?>
</div>
<!-- datatable -->



<?php js_validate(); ?>
<?php js_picker();?>        
<?php gebo_choosen();?>
