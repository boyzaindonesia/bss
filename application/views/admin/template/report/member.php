<div class="btn-group" style="float:right; right:50px">
      <button class="btn btn-primary" type="button"> Download File
	        </button>
      <button aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" type="button">
        <span class="caret"></span>
        <span class="sr-only">&nbsp;</span>
      </button>
      <ul class="dropdown-menu">
         <li><a href="<?php echo $own_links;?>/export_data?type=xls&status=<?php echo isset($_POST['status'])?$_POST['status']:"" ?>&kategori=<?php echo isset($_POST['kategori'])?$_POST['kategori']:"" ?>&iuran=<?php echo isset($_POST['iuran'])?$_POST['iuran']:"" ?>&no_reg_iapi=<?php echo isset($_POST['no_reg_iapi'])?$_POST['no_reg_iapi']:"" ?>&no_reg_negara=<?php echo isset($_POST['no_reg_negara'])?$_POST['no_reg_negara']:"" ?>" target="_blank"><i class="icon-list-alt"></i> Download Excel</a></li>
	      
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
          <label class="control-label" for="no_reg_negara">No. Reg Negara</label>
          <div class="controls">
            <input type="text" id="no_reg_negara" name="no_reg_negara" class="input-medium" value="<?php echo isset($_POST['no_reg_negara'])?$_POST['no_reg_negara']:"" ?>"/>
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
          <label class="control-label" for="status">Status</label>
          <div class="controls">
          <select name="status" id="status" class="validate[required]">
                	<option value="">--Pilih--</option>
                	<option value="Aktif" <?php echo $_POST['status']=="Aktif"?"selected='selected'":"";?> >Aktif</option>
                	<option value="Anggota Kehormatan" <?php echo $_POST['status']=="Anggota Kehormatan"?"selected='selected'":"";?> >Anggota Kehormatan</option>
                	<option value="Cabut Ijin" <?php echo $_POST['status']=="Cabut Ijin"?"selected='selected'":"";?>>Cabut Ijin</option>
                	<option value="Cuti Sementara" <?php echo $_POST['status']=="Cuti Sementara"?"selected='selected'":"";?>>Cuti Sementara</option>
                	<option value="Meninggal Dunia" <<?php echo $_POST['status']=="Meninggal Dunia"?"selected='selected'":"";?>>Meninggal Dunia</option>
                	<option value="Keluar" <?php echo $_POST['status']=="Keluar"?"selected='selected'":"";?>>Keluar</option>
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
  <table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
      <tr>
          <th width="30">No</th>	
          <th>No Reg IAPI</th> 
          <th>No Reg Negara</th> 	  
          <th>Nama Member</th>
          <th>Telp</th>
          <th>Hp</th>
          <th>Email</th>    
          <th>Tipe Keanggotaan</th>
          <th>Jenis Iuran</th>
          <th>SKP Terstruktur</th>
          <th>SKP Tidak Terstruktur</th>
          <th>Status</th>
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
              <td><?php echo $r->no_reg_iapi;?></td>
              <td><?php echo $r->no_reg_neg;?></td>			 
              <td><?php echo $r->nama;?></td>
              <td><?php echo $r->telpon;?></td>
              <td><?php echo $r->hp;?></td>
              <td><?php echo $r->email;?></td>
              <td><?php echo get_kategori_nama($r->kategori);?></td>
              <td><?php echo $r->tipe_iuran; ?> </td>
              <td><?php echo get_skp_wajib($r->id,date('Y')); ?></td>
              <td><?php echo get_skp_non_wajib($r->id,date('Y')); ?></td>
              <td><?php echo $r->status;?></td>
            </tr>
        <?php } 
        }
        ?>       
    </tbody>
  </table>
<?php echo isset($paging)?$paging:'';?>
</div>


<?php js_picker();?>        
<?php gebo_choosen();?>
