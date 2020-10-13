
<div class="alert alert-info" role="alert">
  		<h2><?php echo get_nama_prp($_GET['id']); ?></h2>
	</div>


<div style="float:left;">
<form id="form-validated" class="input" action="<?php echo $own_links;?>/upload/1" method="post" class="input" enctype="multipart/form-data">  
        <table class="table-form" style="float:right">
            <tr>
				<td>
		<div class="control-group">    
         Upload Nilai (.xls)
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
	
</div>
<br>

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


<form id="form-validated" method="post" action="<?php echo $own_links;?>?id=<?php echo $_GET['id'] ?>">
<table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">Normalisasi MU A</label>
          <div class="controls">
            <input type="text" id="mu_a_tambah" name="mu_a_tambah" placeholder="TAMBAH" class="input-small" value="<?php echo isset($_POST['mu_a_tambah'])?$_POST['mu_a_tambah']:"" ?>"/>
            <input type="text" id="mu_a_kali" name="mu_a_kali" placeholder="KALI" class="input-small" value="<?php echo isset($_POST['mu_a_kali'])?$_POST['mu_a_kali']:"" ?>"/>
          </div>
        </div>
        
      
      </td>
    </tr>
    
    <tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">Normalisasi MU D</label>
          <div class="controls">
            <input type="text" id="mu_d_tambah" name="mu_d_tambah" placeholder="TAMBAH" class="input-small" value="<?php echo isset($_POST['mu_d_tambah'])?$_POST['mu_d_tambah']:"" ?>"/>
            <input type="text" id="mu_d_kali" name="mu_d_kali" placeholder="KALI" class="input-small" value="<?php echo isset($_POST['mu_d_kali'])?$_POST['mu_d_kali']:"" ?>"/>
          </div>
        </div>
        
      
      </td>
    </tr>
    
    
 </table>
 

 
 <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">Normalisasi MU B</label>
          <div class="controls">
            <input type="text" id="mu_b_tambah" name="mu_b_tambah" placeholder="TAMBAH" class="input-small" value="<?php echo isset($_POST['mu_b_tambah'])?$_POST['mu_b_tambah']:"" ?>"/>
            <input type="text" id="mu_b_kali" name="mu_b_kali" placeholder="KALI" class="input-small" value="<?php echo isset($_POST['mu_b_kali'])?$_POST['mu_b_kali']:"" ?>"/>
          </div>
        </div>
        
      
      </td>
    </tr>
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">Normalisasi MU E</label>
          <div class="controls">
            <input type="text" id="mu_e_tambah" name="mu_e_tambah" placeholder="TAMBAH" class="input-small" value="<?php echo isset($_POST['mu_e_tambah'])?$_POST['mu_e_tambah']:"" ?>"/>
            <input type="text" id="mu_e_kali" name="mu_e_kali" placeholder="KALI" class="input-small" value="<?php echo isset($_POST['mu_e_kali'])?$_POST['mu_e_kali']:"" ?>"/>
          </div>
        </div>
        
      
      </td>
    </tr>

	
    
 </table>

 
 <table class="table-form" style="float:left">
	<tr valign="top">
      <td>
        
        <div class="control-group">    
          <label class="control-label" for="no_reg_iapi">Normalisasi MU C</label>
          <div class="controls">
            <input type="text" id="mu_c_tambah" name="mu_c_tambah" placeholder="TAMBAH" class="input-small" value="<?php echo isset($_POST['mu_c_tambah'])?$_POST['mu_c_tambah']:"" ?>"/>
            <input type="text" id="mu_c_kali" name="mu_c_kali" placeholder="KALI" class="input-small" value="<?php echo isset($_POST['mu_c_kali'])?$_POST['mu_c_kali']:"" ?>"/>
          </div>
        </div>
        
      
      </td>
    </tr>
    
	<tr valign="top">
      <td>
        
       <div class="control-group">    
          <label class="control-label" for="iuran">&nbsp;</label>
          <div class="controls">
         <input type="submit" class="btn btn-primary col-md-5" id="btn_search" name="btn_search" value="Submit">
  		
          
          </div>
        </div>
        
       </td>
    </tr>
	 </table>

  
   <table class="table-form" style="float:right">
	<tr valign="top">
      <td>
      <h3>Jumlah Peserta Lulus</h3>
        <div class="alert alert-success" role="alert">
  			<b>APKL : <?php echo $jml_a; ?> Orang</b>
		</div>
		<div class="alert alert-success" role="alert">
  			<b>AAEP : <?php echo $jml_b; ?> Orang</b>
		</div>
		
		<div class="alert alert-success" role="alert">
  			<b>BPL : <?php echo $jml_c; ?> Orang</b>
		</div>
              
      </td>
      <td>
      <h3>&nbsp;</h3>
      	<div class="alert alert-success" role="alert">
  			<b>AMTI : <?php echo $jml_d; ?> Orang</b>
		</div>
		
		<div class="alert alert-success" role="alert">
  			<b>E : <?php echo $jml_e; ?> Orang</b>
		</div>
		
		<div class="alert alert-info" role="alert">
  			<b>Total Lulus : <?php echo $jml_lulus; ?> Orang</b>
		</div>
      </td>
    </tr>
    
	 </table>
	 
 
 
 <div style="clear:both"></div>  
 
 
   
</form>
 
<form id="form-validated" class="input" action="<?php echo $own_links;?>/publish/1" method="post" class="input">  
	<div style="float:right">
	<input type="submit" class="btn btn-danger col-md-5" id="btn_publish" name="btn_publish" value="PUBLISH">
	</div>
<h2>Kelas </h2> 
  <table class="table table-striped table-bordered dTableR" id="dt_a">
  <thead>
      <tr>
          <th rowspan="2">No</th>	
		   <th rowspan="2">No Peserta</th>          
		   <th rowspan="2">Nama Peserta</th> 	  
          <th colspan="2">APKL</th>
          <th colspan="2">AAEP</th>
          <th colspan="2">BPL</th>
          <th colspan="2">AMTI</th> 
          <th colspan="2">E</th>
          <th rowspan="2" >Total Nilai </th>
          <th rowspan="2" >Total Skor </th>
          <th rowspan="2" >Total Skor Akhir </th>
          <th rowspan="2">Status</th>
      </tr>
      <tr>
        
          <th>Nilai</th>
          <th>NMR</th>
          <th>Nilai</th>
          <th>NMR</th>
          <th>Nilai</th>
          <th>NMR</th>
          <th>Nilai</th> 
          <th>NMR</th>   
          <th>Nilai</th>
          <th>NMR</th>
      </tr>
  </thead>
  <tbody>
      <?php
     // debugCode($peserta);
        if(count($peserta) > 0){
          $no=0;
          foreach($peserta as $r){
    			//debugCode($r);
          ?>
          
            <tr valign="top">
            
              <td><?php echo ++$no;?></td>
              <td><?php echo $r['no_reg_iapi'] ?></td>
			  <td><?php echo $r['nama'] ?></td>
			  <td><?php echo $r['nilai_a']; ?></td>
			  <td><?php echo $r['normalisasi_a']; ?></td>
			  <td><?php echo $r['nilai_b']; ?></td>
			  <td><?php echo $r['normalisasi_b']; ?></td>
			  <td><?php echo $r['nilai_c']; ?></td>
			  <td><?php echo $r['normalisasi_c']; ?></td>
			  <td><?php echo $r['nilai_d']; ?></td>
			  <td><?php echo $r['normalisasi_d']; ?></td>
			  <td><?php echo $r['nilai_e']; ?></td>
			  <td><?php echo $r['normalisasi_e']; ?></td>
			  <td><?php echo $r['total_nilai']; ?></td>
			  <td><?php echo $r['total_skor']; ?></td>
			  <td><?php echo $r['total_skor_akhir']; ?></td>
			  <td>
			  <?php if($r['total_skor_akhir']>75){ ?>
			  	<div class="alert alert-info" role="alert">
  					<b>L</b>
  					<input type="hidden" name="chk_item_acc[<?php echo $r['id_member'];?>]"  value="<?php echo $r['id_member'];?>" class="chk_item_acc" />
				</div>
			  <?php }else{ ?>
			  	<div class="alert alert-error" role="alert">
  					<b>TL</b> <input type="checkbox" name="chk_item_acc[<?php echo $r['id_member'];?>]"  value="<?php echo $r['id_member'];?>" class="chk_item_acc" />
				</div>	
			  <?php } ?>	
			</td>
             </tr>
        <?php } 
        }
        ?>       
    </tbody>
  </table>
<?php echo isset($paging)?$paging:'';?>
</div>
</form>

<?php js_picker();?>        
<?php gebo_choosen();?>
