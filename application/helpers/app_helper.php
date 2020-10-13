<?php
function getCI(){
	$CI =& get_instance();
	return $CI;
}

function getHeader(){
	$CI =getCI();
	$CI->load->view($CI->jCfg['theme'].'/header');
}

function getFooter(){
	$CI =getCI();
	$CI->load->view($CI->jCfg['theme'].'/footer');
}

function getFormSearch(){
	$CI =getCI();
	$CI->load->view($CI->jCfg['theme'].'/form-search');
}

function getTinymce(){
	$CI =getCI();
	$CI->load->view($CI->jCfg['theme'].'/tinymce');
}

function getView($file="",$par=array()){
	$CI =getCI();
	$CI->load->view($CI->jCfg['theme']."/".$file,$par);
}

function themeUrl(){
	$CI =getCI();
	return base_url().APPPATH."views/".$CI->jCfg['theme']."/";
}

function pTxt($key='',$sep='-'){
	return str_replace($sep,' ', trim($key));
}

function myNum($num=0,$curr="Rp"){
	$curr2 = strtolower($curr);
	if($curr2=="rp"){
		return $curr.". ".number_format($num,0,",",".");
	}elseif($curr2=="$" || $curr2=="e"){
		return number_format($num,0,".",",")." ".$curr;
	}else{
		return $curr.". ".number_format($num,0,",",".");
	}
}

function cfg($o='mt_app_name'){
	$CI =getCI();
	$return = '';

	$logic = '';
	if(is_array($CI->config->item($o))){
		$logic = count($CI->config->item($o))>0?1:"";
	}else{
		$logic = $CI->config->item($o);
	}

	if(trim($logic)!=""){
		$return = $CI->config->item($o);
	}else{
		$v = $CI->db->get_where("mt_app_config",array(
				'config_name' => $o
			))->row();
		if(count($v)>0)
			$return = $v->config_value;
	}

	return $return;
}

function debugCode($r=array(),$f=TRUE){
	echo "<pre>";
	print_r($r);
	echo "</pre>";

	if($f==TRUE)
		die;
}

function get_new_image($p=array()){
	$CI =getCI();
	$no_image = base_url()."assets/collections/no_image.jpg";
	$return = $no_image;

	$url_source_no_image = base_url()."assets/images/no_image.jpg";
	$p['url'] = trim($p['url'])==""?$url_source_no_image:$p['url'];

	if( trim($p['url']) != ""){
		$img_source = "./".str_replace(base_url(),"",$p['url']);
		$width = isset($p['width'])?$p['width']:0;
		$height = isset($p['height'])?$p['height']:0;

		if( file_exists($img_source) && !is_dir($img_source)){
			//get file source info.
			$finfo = pathinfo($img_source);
			$n_width = $width==0?'ori':$width;

			$new_image_name = $finfo['filename']."_".$n_width.".".$finfo['extension'];
			if($height>0){
				$new_image_name = $finfo['filename']."_".$n_width."_".$height.".".$finfo['extension'];
			}

			$new_path 	= "./assets/images/".$new_image_name;

			if(!file_exists($new_path) && !is_dir($new_path) ){
				$CI->load->library('image_lib');
				$quality = isset($p['quality'])?$p['quality']:'100%';

				$v = array(
						"width"                 => $width,
						"height"                => $height,
						"quality"               => $quality,
						"source_image"  		=> $img_source,
						"new_image"             => $new_path
				);
				$img = getimagesize($v['source_image']);
				$realWidth      = $img[0];
				$realHeight 	= $img[1];

				if( $height > 0){

					//resize
					$oriW = $v['width'];
					$oriH = $v['height'];
					$x = $v['width']/$realWidth;
					$y = $v['height']/$realHeight;
					if($x < $y) {
						$v['width'] = round($realWidth*($v['height']/$realHeight));
					} else {
						$v['height'] = round($realHeight*($v['width']/$realWidth));
					}

					$CI->image_lib->initialize($v);
					if(!$CI->image_lib->resize()){
							//debugCode($this->image_lib->display_errors());
							//echo "eror resize ".$new_image_name;
							$return = base_url()."assets/images/no_image.jpg";
					}
					$CI->image_lib->clear();

					// CROP..
					$config = null;
					$config['image_library'] = 'GD2';
					$im = getimagesize($v['new_image']);
					$toCropLeft = ($im[0] - ($oriW *1))/2;
					$toCropTop = ($im[1] - ($oriH*1))/2;

					$config['source_image'] = $v['new_image'];
					$config['width'] = $oriW;
					$config['height'] = $oriH;
					$config['x_axis'] = $toCropLeft;
					$config['y_axis'] = $toCropTop;
					$config['maintain_ratio'] = false;
					$config['new_image'] = $v['new_image'];

					$CI->image_lib->initialize($config);

					if(!$CI->image_lib->crop()){
						die("Error Crop..");
					}
					$CI->image_lib->clear();

				}else{
					$CI->image_lib->initialize($v);
					$v['width']		= $v['width']==0?$realWidth:$v['width'];
					$v['height'] 	= $v['width']==0?round($realHeight*($v['width']/$realWidth)):$v['width'];
					//resize...
					if(!$CI->image_lib->resize()){
							//debugCode($this->image_lib->display_errors());
							//echo "eror resize ".$new_image_name;
							$return = base_url()."assets/images/no_image.jpg";
					}
					$CI->image_lib->clear();
				}

				$return = base_url()."assets/images/".$new_image_name;
			}else{
				$return = base_url()."assets/images/".$new_image_name;
				//$p['url'] = $url_source_no_image;
				//get_new_image($p);
			}
		}

	}
	return $return;
}



function biaya_kirim($tujuan="",$berat=""){

	$requestUrl = 'http://api.sicepat.com/customer/tariff?api-key=391274a334f7527a6c1f340532aeaddd&origin=CGK&destination='.$tujuan.'&weight='.$berat.'';
			$response  = file_get_contents($requestUrl);
			$jsonobj  = json_decode($response);
			$resultArray = object2array($jsonobj);
			//debugCode($resultArray);
			foreach($resultArray[sicepat][results] as $r ){
				$tarif = $r[tariff];
			}
	return $tarif;
}

function object2array($object){
    $return = NULL;

    if(is_array($object))
    {
        foreach($object as $key => $value)
            $return[$key] = object2array($value);
    }
    else
    {
        $var = get_object_vars($object);

        if($var)
        {
            foreach($var as $key => $value)
                $return[$key] = ($key && !$value) ? NULL : object2array($value);
        }
        else return $object;
    }

    return $return;
}

function get_image($url="",$noimage=""){
	if(trim($noimage)==""){
		$no_image = base_url()."assets/collections/images/no_image.jpg";
	}else{
		$no_image = base_url()."assets/collections/images/".$noimage;
	}
	$img = "";
	if(trim($url)!=""){
		$nurl = "./".str_replace(base_url(),"",$url);
		if(file_exists($nurl) && !is_dir($nurl)){
			$img = $url;
		}else
			$img = $no_image;
	}else
		$img = $no_image;

	return $img;
}


function _ac($c='index'){
	if(trim($c)!==''){
		$CI  = getCI();
		$acc = $CI->jCfg['access'];
		if(isset($acc[$c])){
			return TRUE;
		}else
			return FALSE;
	}else{
		return FALSE;
	}
}

function get_info_message(){
	 if( isset($_GET['msg']) ){
	 		$type= isset($_GET['type_msg'])?$_GET['type_msg']:'danger';
	 		if($type=='error'){ $type = 'danger'; }
	 	?>
	 	<div id="alert-top" style="display: block;">
		 	<div class="alert alert-<?php echo $type;?> fade in alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><strong>Message!</strong></p>
				<p><?php echo urldecode($_GET['msg']);?></p>
			</div>

			<script type="text/javascript">
				function hidden_msg(){
					$('#alert-top').fadeOut(600);
					setTimeout('$("#alert-top").html("")',600);

					var href = window.location.href;
					if(href.indexOf("msg") > -1) {
					    history.pushState(null, null, href.replace(/msg=.*(&?)/, '$1'));
					} else if(href.indexOf("err") > -1) {
					    history.pushState(null, null, href.replace(/err=.*(&?)/, '$1'));
					}
				}
				setTimeout('hidden_msg()',4000);
			</script>
		</div>
		<div style="display: block;">
		 	<div class="alert alert-<?php echo $type;?> fade in alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p><strong>Message!</strong></p>
				<p><?php echo urldecode($_GET['msg']);?></p>
			</div>
		</div>
	<?php }
}


function changecharnum($txtName){
	$name = str_replace(" ","",trim(strtolower($txtName)));
	$name = str_replace("_","",trim($name));
	$name = str_replace("-","",trim($name));
	$name = str_replace("&","",trim($name));
	if ($name!='' && preg_match('/[^\w\d_-]/si',$name)) {
		$name = str_replace(' ','',$name);
		if (preg_match('/[^\w\d_-]/si',$name))	{
			$name = preg_replace('/[^\w\d_-]/si','',$name);
		}
	}
	return $name;
}
function changeEnUrl($txtName){
	$name = str_replace(" ","-",trim(strtolower($txtName)));
	$name = str_replace("&","and",trim($name));
	if ($name!='' && preg_match('/[^\w\d_-]/si',$name)) {
		$name = str_replace(' ','-',$name);
		if (preg_match('/[^\w\d_-]/si',$name))	{
			$name = preg_replace('/[^\w\d_-]/si','',$name);
		}
	}
	return $name;
}
function changeDeUrl($txtName){
	$name = str_replace("-"," ",trim($txtName));
	return $name;
}
function isUniqueURL($url,$table) {
    if (isset($url) && $url) {
        //verification values in BD
		$CI =getCI();
        $v = $CI->db->get_where($table,array(
				'url' => $url
			))->row();
		if(count($v)>0){
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function generateUniqueURL($title,$table){
    $int = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, +2);
    if($title==''){ $title = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, +6); }
    $url = changeEnUrl($title);
    if (isUniqueURL($url,$table)) {
    	$title = $title.'-'.$int;
        return generateUniqueURL($title,$table);
    } else {
        return $url;
    }
}

function _encrypt($key=""){
	$CI = getCI();
	$CI->load->library('encrypt');
	$nid = "gila-#".$key."#balap-".date("Ymdh");
	return $CI->encrypt->encode($nid);
}
function _decrypt($key=""){
	$CI = getCI();
	$CI->load->library('encrypt');
	$nid = $CI->encrypt->decode($key);
	$nid_arr = explode("#",$nid);
	return $nid_arr[1];
}

function get_breadcrumb($par=array()){
	if( count($par) > 1){
		echo '<ol class="breadcrumb default square rsaquo sm">';
		if(count($par) > 0){
			foreach ($par as $key => $value) {
				if( isset($value['url']) && trim($value['url'])!="" ){
					echo "<li>";
					if(strtolower($value['title'])=="home"){
						echo "<a href='".site_url("admin/me")."'><i class='fa fa-home'></i> Dashboard</a>";
					}else{
						echo "<a href='".$value['url']."'>".$value['title']."</a>";
					}
					echo "</li>";
				}else{
					echo "<li class='active'>".$value['title']."</li>";
				}
			}
		}
		echo '</ol>';
	}
}

function getLinks($links=array()){
	// debugCode($links);
	$CI =getCI();
	$uri =  $CI->uri->segment(3);
	if(count($links)>0){
		rsort($links);
		foreach($links as $v){
			if($v['action']!="bug"){
				if(trim($uri)==''||trim($uri)=='search'||trim($uri)=='access'||trim($uri)=='view'){
					$fc = 'index';
				}else{
					$fc = (trim($uri)=='edit'||trim($uri)=='add'||trim($uri)=='upload_excel'||trim($uri)=='print_mail'||trim($uri)=='print_nota')?'add':$uri;
				}
				$class_css = $v['action']=="index"?"list":$v['action'];
				$icon = $v['action']=="add"?'<i class="fa fa-plus"></i>':'<i class="fa fa-th-list"></i>';
				// debugCode($uri);
			?>
				<li class="<?php echo ($fc==$v['action'])?'active':'';?>"><a href="<?php echo $v['link'];?>" data-toggle="tooltip" data-original-title="<?php echo ucwords($v['title']);?>"><?php echo $icon." ".ucwords($class_css);?></a></li>
			<?php
			}
		}
	}
}

function getLinksBtn($links=array()){
	// debugCode($links);
	$CI =getCI();
	$uri =  $CI->uri->segment(3);
	if(count($links)>0){
		rsort($links);
        $result = '';
		foreach($links as $v){
			if($v['action']!="bug" && $v['action']!="index"){
				if(trim($uri)==''||trim($uri)=='search'){
					$fc = 'index';
				}else{
					$fc = (trim($uri)=='access'||trim($uri)=='duplicate'||trim($uri)=='edit'||trim($uri)=='add'||trim($uri)=='upload_excel'||trim($uri)=='print_mail'||trim($uri)=='print_nota')?'add':$uri;
				}
				// debugCode($uri);

				if($fc != 'add' && $fc != 'detail'){
				$result .= '<div class="btn-group"><a href="'.$v['link'].'" class="btn '.$v['button'].'"><i class="fa '.$v['image'].'"></i> '.ucwords($v['title']).'</a></div>';
				}
			}
		}

		echo $result;
	}
}

function get_header_table($obj=array()){
	$CI = getCI();
	if( count($obj) > 0 ){
		$direction = $CI->jCfg['search']['order_dir']=="ASC"?"DESC":"ASC";
		foreach ($obj as $key => $value) {
			if(trim($key)!=""){
			echo "<th><a href='".$CI->own_link."/sort?sort_by=".$key."&sort_dir=".$direction."&next=".current_url()."'>".$value."</a></th>";
			}
		}
	}
}

function cat_search($m=array()){
	$CI =getCI();
	if(count($m) > 0 ){
		foreach ($m as $key => $value) {
			$s = "";
			if($key==$CI->jCfg['search']['colum'])
				$s="selected='selected'";

			echo "<option value='".$key."' $s >".$value."</option>";
		}
	}
}

function cat_perpage($m=array()){
	$CI =getCI();
	echo '<select name="per_page" class="fr" id="per_page" style="width:60px;">';
	if(count($m) > 0 ){
		foreach ($m as $key => $value) {
			$s = "";
			if($value==$CI->jCfg['search']['per_page'])
				$s="selected='selected'";

			echo "<option value='".$value."' $s >".$value."</option>";
		}
	}
	echo "</select>";
	?>
	<script type="text/javascript">

	$('#per_page').change(function(){
		val = $(this).val();
		document.location = "<?php echo $CI->own_link;?>/per_page?per_page="+val+"&next=<?php echo current_url();?>";
	});
	</script>
	<?php
}

function cat_status($m=array()){
	$CI =getCI();
	echo '<select name="data_status" class="fr" id="data_status" style="width:110px; margin-right:15px;">';
	if(count($m) > 0 ){
		echo "<option value='' >ALL</option>";
		foreach ($m as $key => $value) {
			$s = "";
			if($value==$CI->jCfg['search']['status'])
				$s="selected='selected'";

			echo "<option value='".$value."' $s >".$value."</option>";
		}
	}
	echo "</select>";
	?>
	<script type="text/javascript">

	$('#data_status').change(function(){
		val = $(this).val();
		document.location = "<?php echo $CI->own_link;?>/set_status?status="+val+"&next=<?php echo current_url();?>";
	});
	</script>
	<?php
}

function get_data_table(){
?>
	<link href="<?php echo base_url()?>assets/plugins/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/datatable/js/bootstrap.datatable.js"></script>
    <script text="javascript">
		$(document).ready(function() {
		    $('#datatable').dataTable();
		    $('.datatable').dataTable({
				"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
	            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ]
		    });
		    $('.datatable-50').dataTable({
				"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
	            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
	            "iDisplayLength": 50
		    });
		    $('.datatable-all').dataTable({
				"lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
	            "columnDefs": [ {"targets": 'no-sort', "orderable": false} ],
	            "iDisplayLength": -1
		    });
		});
	</script>
	<?php
}
function js_validate(){
	?>
	<link href="<?php echo base_url()?>assets/plugins/validator/formValidation.min.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/plugins/validator/formValidation.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/validator/bootstrapValidator.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.formValidation').formValidation({
				framework: 'bootstrap',
				// trigger: 'blur',
				// locale: 'id_ID',
				err: {
		            container: 'tooltip'
		        },
				icon: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				}
            })
            .on('click', 'input[name="btn_reset"]', function() {
            	$('.formValidation').data('formValidation').resetForm();
		    });
		});
	</script>
	<?php
}

function js_isotope(){
?>
<style type="text/css">
	.isotope .isotope-item {
		padding-left: 10px;
		padding-right: 10px;
	}
</style>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/isotope/isotope.pkgd.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var $isotope        = $('.isotope');
        var $filter         = $('.isotope-filter');

        $(window).bind('resize', function(){
            var selector = $filter.find('.btn-isotope-filter > .active').attr('data-filter');
            try {
                $isotope.isotope({
                    filter  : selector,
                    animationOptions: {
                        duration: 750,
                        easing  : 'linear',
                        queue   : false,
                    }
                });
                return false;
            } catch(err) {

            }
        });

        // Isotope Filter
        $filter.find('.btn-isotope-filter > div').click(function() {
            $filter.find('.btn-isotope-filter > div').removeClass('active');
            $(this).addClass('active');

            var selector = $(this).attr('data-filter');
            try {
                $isotope.isotope({
                    filter  : selector,
                    animationOptions: {
                        duration: 750,
                        easing  : 'linear',
                        queue   : false,
                    }
                });
                return false;
            } catch(err) {

            }
        });

        // Run Isotope
        $(window).load(function(){
            try {
                $isotope.isotope({
                    filter              : '*',
                    layoutMode          : 'masonry',
                    animationOptions    : {
                    duration            : 750,
                    easing              : 'linear'
                   }
                });
            } catch(err) {

            }
        });
    });
</script>
<?php
}

function js_magnific_popup(){
	?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/magnific-popup/magnific-popup.min.css">
	<script src="<?php echo base_url()?>assets/plugins/magnific-popup/magnific-popup.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($('.magnific-popup-wrap').length > 0){
		        $('.magnific-popup-wrap').each(function() {
		        "use strict";
		            $(this).magnificPopup({
		                delegate: 'a.zooming',
		                type: 'image',
		                removalDelay: 300,
		                mainClass: 'mfp-fade',
		                gallery: {
		                  enabled:true
		                }
		            });
		        });
		    }

		    if ($('.inline-popups').length > 0){
		        $('.inline-popups').magnificPopup({
		          delegate: 'a',
		          removalDelay: 500,
		          callbacks: {
		            beforeOpen: function() {
		               this.st.mainClass = this.st.el.attr('data-effect');
		            }
		          },
		          midClick: true
		        });
		    }

		    if ($('.magnific-popup-ajax').length > 0){
		        $('.magnific-popup-ajax').magnificPopup({
		          delegate: 'a',
		          removalDelay: 500,
		          callbacks: {
		            beforeOpen: function() {
		               this.st.mainClass = this.st.el.attr('data-effect');
		            },
		            elementParse: function(item) {
		                var id = item.src;
		                var url = $('a[href='+id+']').attr('data-ajax-url');

		                if(!$(id).hasClass('loaded')){
		                    $.ajax({
		                        type: 'POST',
		                        url: url,
		                        data: {'thisAction':'save'},
		                        async: false,
		                        cache: false,
		                        dataType: 'json',
		                        beforeSend: function(){

		                        },
		                        success: function(data){
		                            $(id).find('.popup-ajax-result').html(data.content);
		                            $(id).find('table').addClass('datatable').dataTable({
		                                "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
		                            });
		                            $(id).addClass('loaded');
		                        },
		                        error: function(jqXHR){
		                            var response = jqXHR.responseText;
		                            // console.log(jqXHR);
		                            alert(response);
		                        }
		                    });
		                }
		            }
		          },
		          midClick: true
		        });
		    }

		    if ($('.magnific-popup-youtube').length > 0){
		        $('.magnific-popup-youtube').each(function() {
		        "use strict";
		            $(this).magnificPopup({
		                delegate: 'a.zooming',
		                disableOn: 700,
		                type: 'iframe',
		                mainClass: 'mfp-fade',
		                removalDelay: 160,
		                preloader: false,

		                fixedContentPos: false
		            });
		        });
		    }
		});
	</script>
	<?php
}

function js_prettify(){
?>
	<link href="<?php echo base_url()?>assets/plugins/prettify/prettify.min.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/plugins/prettify/prettify.js"></script>
    <script text="javascript">
		$(window).load(function() {
		    "use strict";
			prettyPrint();
		});
	</script>
<?php
}

function js_owl_carousel(){
?>
    <link href="<?php echo base_url()?>assets/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/plugins/owl-carousel/owl.theme.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/plugins/owl-carousel/owl.transitions.min.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/plugins/owl-carousel/owl.carousel.min.js"></script>
<?php
}

function js_chosen(){
?>
    <link href="<?php echo base_url()?>assets/plugins/chosen/chosen.min.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/plugins/chosen/chosen.jquery.min.js"></script>
    <script text="javascript">
		$(document).ready(function() {
		    $(function () {
				"use strict";
				var configChosen = {
				  '.chosen-select'           : {},
				  '.chosen-select-deselect'  : {allow_single_deselect:true},
				  '.chosen-select-no-single' : {disable_search_threshold:10},
				  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
				  '.chosen-select-width'     : {width:"100%"}
				}
				for (var selector in configChosen) {
				  $(selector).chosen(configChosen[selector]);
				}
			});
		});
	</script>
<?php
}

function js_chosen_city(){
?>
    <script text="javascript">
		$(document).ready(function() {
		    $(document).on('change', '.chosen-city', function(e){
                e.preventDefault();
                var $this = $(this);
                var id = $this.find(':selected').val();
                var province      = $this.find(':selected').attr('data-province');
                var province_name = $this.find(':selected').attr('data-province-name');
                var country       = $this.find(':selected').attr('data-country');
                var country_name  = $this.find(':selected').attr('data-country-name');
				if(province==''||province==undefined||province=='undefined'){ province=''; }
				if(province_name==''||province_name==undefined||province_name=='undefined'){ province_name=''; }
				if(country==''||country==undefined||country=='undefined'){ country=''; }
				if(country_name==''||country_name==undefined||country_name=='undefined'){ country_name=''; }
                $this.parents('form').find('input[name="province"]').val(province);
                $this.parents('form').find('input[name="province_name"]').val(province_name);
                $this.parents('form').find('input[name="country"]').val(country);
                $this.parents('form').find('input[name="country_name"]').val(country_name);
            });
            $('.chosen-city').change();
		});
	</script>
<?php
}

function js_select2(){
?>
    <link href="<?php echo base_url();?>assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <script src="<?php echo base_url();?>assets/plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            if ($('.select2-tokenizer').length > 0){
                $(".select2-tokenizer").select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                });
            }
        });
    </script>

<?php
}

function js_mask(){
?>
	<script src="<?php echo base_url()?>assets/plugins/mask/jquery.mask.min.js"></script>
    <script text="javascript">
		$(document).ready(function() {
		    $(function () {
				"use strict";
				$('.cc_masking').mask('0000-0000-0000-0000');
				$('.cc_security_masking').mask('0000');
				$('.date_masking').mask('00/00/0000');
				$('.time_masking').mask('00:00:00');
				$('.date_time_masking').mask('00/00/0000 00:00:00');
				$('.phone_us_masking').mask('(000) 000-0000');
				$('.cpf_masking').mask('000.000.000-00', {reverse: true});
				$('.money_masking').mask('000.000.000.000.000,00', {reverse: true});
				$('.money2_masking').mask("#.##0,00", {reverse: true, maxlength: false});
				$('.moneyRp_masking').mask("#.##0", {reverse: true, maxlength: false});
				$('.ip_address_masking').mask('0ZZ.0ZZ.0ZZ.0ZZ', {translation: {'Z': {pattern: /[0-9]/, optional: true}}});
				$('.ip_address_masking').mask('099.099.099.099');
				$('.percent_masking').mask('##0,00%', {reverse: true});
			});
		});
	</script>
<?php
}

function js_dragsort(){
?>
    <script src="<?php echo base_url()?>assets/plugins/dragsort/jquery.dragsort-0.5.2.min.js"></script>
    <script text="javascript">
		$(document).ready(function() {
		    $(".dragsort").dragsort({ dragSelector: ".btn-drag", dragEnd: saveOrder });
	    	$(".dragsort .btn-drag").css('cursor','move');
		    function saveOrder(e) {
		    	var $this = $(this),
		    		dragsort = $this.parents('.dragsort'),
		    		url = dragsort.data('url-dragsort'),
		    		item = dragsort.find('.dragsortitem'),
		            data = item.map(function() { return $(this).data("itemid"); }).get();
		           // alert(data);

		        if(url==''||url==undefined){
		    		url = OWN_LINKS+'/change_position';
		        }
		        $.post(url, { "ids[]": data });
		    	$(".dragsort .btn-drag").css('cursor','move');
		    };
		});
	</script>
<?php
}

function js_upload_drag(){
?>
    <link rel='stylesheet' type='text/css' href="<?php echo base_url()?>assets/plugins/upload/css/style_upload.css"/>
    <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/upload/js/jquery.knob.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/upload/js/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/upload/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/upload/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/plugins/upload/js/script.js"></script>
    <script text="javascript">
		$(document).ready(function() {


		});
	</script>
<?php
}

function js_cropper(){
?>
    <link href="<?php echo base_url();?>assets/plugins/cropper/dist/cropper.min.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/plugins/cropper/crop-avatar/main.css" rel="stylesheet">
	<script src="<?php echo base_url();?>assets/plugins/cropper/dist/cropper.min.js"></script>
	<script src="<?php echo base_url();?>assets/plugins/cropper/crop-avatar/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if ($('.crop-avatar').length > 0){
				var i = 1;
				$('.crop-avatar').each(function() {
					"use strict";
					$(this).attr('id','crop-avatar-'+i);
					i += 1;
				});
			}
		});
	</script>
<?php
}

function js_hight_chart(){
	?>
		<script src="<?php echo base_url();?>assets/plugins/cart/js/highcharts.js"></script>
		<script src="<?php echo base_url();?>assets/plugins/cart/js/modules/exporting.js"></script>
	<?php
}

function get_coordinates(){
	?>
	<!-- <div class="get_coordinates" data-top="" data-left="" data-click="false" >
        <img src="" alt="" class="media-object img-responsive">
    </div> -->
    <script type="text/javascript">
        $(function() {
            if ($('.get_coordinates').length > 0){
                $('.get_coordinates').each(function() {
                    "use strict";
                    var $this = $(this),
                        $old_top  = $this.data('top'),
                        $old_left = $this.data('left'),
                        $old_top_div  = '10px',
                        $old_left_div = '10px';

                    if($old_top!=''&&$old_top!=undefined){ $old_top_div = $old_top+'%'; }
                    if($old_left!=''&&$old_left!=undefined){ $old_left_div = $old_left+'%'; }

                    $this.css({'position':'relative'});
                    $this.append('<div style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%;"></div>');
                    $this.append('<div class="icon_coordinates" style="position: absolute; top: '+$old_top_div+'; left: '+$old_left_div+'; width: 20px; height: 20px; margin-left: -10px; margin-top: -10px; border-radius: 100%; cursor:move; background-color: #E9573F; z-index: 99;"></div>');
                    $this.append('<input type="hidden" name="pos_top" value="'+$old_top+'"/>');
                    $this.append('<input type="hidden" name="pos_left" value="'+$old_left+'"/>');

                    $this.click(function(e) {
                        var $this = $(this),
	                        $click     = $this.data('click'),
                            $width     = $this.width(),
                            $height    = $this.height(),
                            $offset    = $this.offset(),
                            $relativeX = parseFloat(e.pageX - $offset.left),
                            $relativeY = parseFloat(e.pageY - $offset.top),
                            $percentX  = parseFloat(($relativeX/$width)*100),
                            $percentY  = parseFloat(($relativeY/$height)*100);

	                    if($click!=false){
	                        $this.find('.icon_coordinates').css({'left':$percentX+'%','top':$percentY+'%'});
	                        $this.find('input[name="pos_left"]').val($percentX);
	                        $this.find('input[name="pos_top"]').val($percentY);
	                    }
                    });

                });
            }
        });
    </script>
	<?php
}

function js_blastnotif(){
	$CI = getCI();
	if(isset($CI->jCfg['member']['member_id']) && $CI->jCfg['member']['member_id'] != ''){
?>
	<script src="<?php echo base_url()?>assets/plugins/blastnotif/blastnotif.js"></script>
<?php
	}
}

function js_picker(){
	?>
		<link href="<?php echo base_url();?>assets/plugins/datepicker/datepicker.min.css" rel="stylesheet" />
		<script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
		$(document).ready(function(){
			if ($('.datepicker').length > 0){
				$('.datepicker').datepicker();
			}
			if ($('#datepickerstart').length > 0){
				// var nowTemp = new Date();
				// var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
				var checkin = $('#datepickerstart').datepicker({
				  onRender: function(date) {
					// return date.valueOf() < now.valueOf() ? 'disabled' : '';
				  }
				}).on('changeDate', function(ev) {
				  // if (ev.date.valueOf() > checkout.date.valueOf()) {
					var newDate = new Date(ev.date)
					newDate.setDate(newDate.getDate() + 1);
					checkout.setValue(newDate);
				  // }
				  checkin.hide();
				  $('#datepickerend')[0].focus();
				}).data('datepicker');
				var checkout = $('#datepickerend').datepicker({
				  onRender: function(date) {
					return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
				  }
				}).on('changeDate', function(ev) {
				  checkout.hide();
				}).data('datepicker');
			}

			if ($('#datepickerstartsearch').length > 0){
				// var nowTemp = new Date();
				// var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
				var checkin = $('#datepickerstartsearch').datepicker({
				  onRender: function(date) {
					// return date.valueOf() < now.valueOf() ? 'disabled' : '';
				  }
				}).on('changeDate', function(ev) {
				  // if (ev.date.valueOf() > checkout.date.valueOf()) {
					var newDate = new Date(ev.date)
					newDate.setDate(newDate.getDate() + 1);
					checkout.setValue(newDate);
				  // }
				  checkin.hide();
				  $('#datepickerendsearch')[0].focus();
				}).data('datepicker');
				var checkout = $('#datepickerendsearch').datepicker({
				  onRender: function(date) {
					return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
				  }
				}).on('changeDate', function(ev) {
				  checkout.hide();
				  $('#datepickerendsearch').parents('form').submit();
				}).data('datepicker');
			}
		});
		</script>
	<?php
}
function convDatepickerEnc($vardate){ return date("d-m-Y", strtotime($vardate) ); }
function convDatepickerDec($vardate){ return date("Y-m-d", strtotime($vardate) ); }

function js_timepicker(){
	?>
	<link href="<?php echo base_url();?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
	<script src="<?php echo base_url();?>assets/plugins/timepicker/bootstrap-timepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if ($('.timepicker').length > 0){
				$('.timepicker').timepicker({ 'timeFormat': 'h:i A' });
			}
		});
	</script>
	<?php
}
function convTimepickerEnc($vardate){ return date("h:i A", strtotime($vardate) ); }
function convTimepickerDec($vardate){ return date("H:i", strtotime($vardate) ); }

/* ecommerde */
function get_count_message(){
	$CI = getCI();
	$m = $CI->db->get_where('mt_message',array(
		'notify 		 !=' => 0,
		'message_istrash !=' => 1
	))->result();
	return count($m);
}
function get_count_testimonial(){
	$CI = getCI();
	$m = $CI->db->get_where('mt_testimonial',array(
		'notify 		 	 !=' => 0,
		'testimonial_istrash !=' => 1
	))->result();
	return count($m);
}

function get_count_lapor($db="",$status="",$istrash=""){
	$CI = getCI();
	$m = $CI->db->get_where($db,array(
	"status" => $status,
	"istrash"=>	$istrash
		))->result();

	return count($m);
}

function get_count_tr($db="",$status="",$istrash=""){
	$CI = getCI();
	$m = $CI->db->get_where($db,array(
	"transaction_pay_status" => $status,
	"transaction_istrash"	=>	$istrash
		))->result();

	return count($m);
}

function get_style(){
	?>
	<style>
		*{font-size:12px; font-family:tahoma; padding:0px; margin:0px;}
		.tabel{ width:100%;}.tabel tr td{padding:4px;}
		h3{font-weight:normal; font-size:18px; border-bottom:1px solid #999999; padding:4px;}
		body{margin:0px; padding:0px;}
	</style>
	<?php
}

function link_action($links=array(),$id="",$name=""){
	if(count($links)>0){
		foreach($links as $m){
			// $property = "";
			$confirm  = "";
			if($m['type']=='simple'){
				// $property = " class='ttip_ts act_modal' ";
			}elseif($m['type']=='confirm'){
				$confirm = 'onClick="return confirm('."'Are you sure delete ?'".');"';
			}else{

			}
		?>
		<a href="<?php echo $m['link']."/".$id.($name!=""?"-".$name:"");?>" <?php echo $confirm ?> class="btn <?php echo $m['button'];?> btn-xs" data-toggle="tooltip" data-original-title="<?php echo ucwords($m['title']);?>"><i class="fa <?php echo $m['image'];?>"></i></a>
		<?php
		}
	}
}

function check_action($links=array(),$action=""){ // Buat Check / Cetak Button Add Edit Delete
	$result = false;
	if(count($links)>0){
		foreach($links as $m){
			if($m['action'] == $action){
				$result = true;
			}
		}
	}
	return $result;
	// if(check_action($links_table_item,'edit')){ //ACTION }
}

function get_group($id=""){
	$CI = getCI();
	$nama = '';
	$m = $CI->db->get_where("mt_app_acl_group",array(
			'ag_id'	=> $id
		))->row();
	if( count($m) > 0 ){
		$nama = $m->ag_group_name;
	}
	return $nama;
}
function _ajax_cek($par=array()){
	$CI =getCI();

	if(isset($par['ext']) && trim($par['ext'])!=""){
		$validateValue	= $par['ext'].$_POST['validateValue'];
	}else{
		$validateValue	= $_POST['validateValue'];
	}

	if(isset($par['replace_dot'])){
		$validateValue	= str_replace(".","",$validateValue);
	}

	$validateId		= $_POST['validateId'];
	$validateError	= $_POST['validateError'];

	$arrayToJs = array();
	$arrayToJs[0] = $validateId;
	$arrayToJs[1] = $validateError;

	$CI->DATA->table=$par['table'];
	$cek = $CI->DATA->_cek(array(
		$par['field'] => $validateValue
	));
	$tmp = "";
	if($cek > 0){
		$arrayToJs[2] = "false";
		$tmp = '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';
	}else{
		$arrayToJs[2] = "true";
		$tmp = '{"jsonValidateReturn":'.json_encode($arrayToJs).'}';
	}
	return $tmp;
}


function _get_menu($menu=array()){
	$CI  = getCI();
	if(count($menu)<0) return array();
	$mnn="";
	foreach($menu as $mn){
		$mnx 	= preg_split("/>/",$mn['acc_menu']);
		$count	= count($mnx);
		$t		= "\$mnn";
		for($i=0;$i<$count;$i++){
			if(($count-1)==$i)
				$t .= "[]=array('menu'=>'".$mnx[$i]."','id'=>'".$mn['acc_id']."','class_group'=>'".$mn['acc_group']."','menu_group'=>'".$mn['acc_menu_group']."','group'=>'".$mn['acc_group_controller']."','name'=>'".$mn['acc_controller_name']."','css_class'=>'".$mn['acc_css_class']."');";
			else
				$t .= "['".$mnx[$i]."']";
		}
		eval($t);
	}

	return $mnn;

}

function geboMenu($m,$top=true){
	// debugCode($m);
	$CI  = getCI();
	$c 	= count($m);
	$uris = $CI->uri->segment(2);
	//if( count($m) > 0 && trim($m)!=""){
		foreach($m as $k=>$v){

			if(is_array($v) && !isset($v['menu']) && !isset($v['id']) && !isset($v['name']) ){
				$css_class = isset($v[0]['css_class']) && trim($v[0]['css_class'])!=""?$v[0]['css_class']:'fa-folder';
				$menu_group = isset($v[0]['menu_group']) && trim($v[0]['menu_group'])!=""?$v[0]['menu_group']:'';
				if($menu_group!=''){ echo '<li class="static text-uppercase">'.$menu_group.'</li>'; }

				echo '<li class="'.($v['name']==$uris?'active':'').'" data-id="'.$v['name'].'">
						<a href="javascript:void(0);">
				            <i class="fa '.$css_class.' icon-sidebar"></i>
				            <i class="fa fa-angle-right chevron-icon-sidebar"></i>
				            '.$k.'
				        </a>
				        <ul class="submenu '.($v['name']==$uris?'visible':'').'">';
				        geboMenu($v,false);
			        echo '</ul>
				    </li>';
			} else {
				$badge = '';
				$notif = get_notif_count($v['name']);
				if($notif['count'] > 0){
					$badge ='<span class="badge badge-'.$notif['color'].' span-sidebar">'.$notif['count'].'</span>';
				}

				if($v['menu_group']!=''&&$top==true){ echo '<li class="static text-uppercase">'.$v['menu_group'].'</li>'; }

				echo '<li class="'.($v['name']==$uris?'active':'').'" data-id="'.$v['name'].'">
						<a href="'.site_url($v['group'].'/'.$v['name']).'" >
						'.($top==true?'<i class="fa '.$v['css_class'].' icon-sidebar"></i>':'').'
						'.$v['menu'].' '.$badge.'</a>
					  </li>';
			}

		}
	//}
}

function get_notif_count($id=''){
	$CI = getCI();
    $user_id  = isset($CI->jCfg['user']['id'])?$CI->jCfg['user']['id']:'';
    $store_id = get_user_store($user_id);
	$return = array();
	$count  = 0;
	$color  = 'danger';
	switch ($id) {
		case 'product_new_stock':
			$count = count(get_purchase_detail_status('0'));
			$color = 'danger';
			break;
		case 'notif_update_produk':
			$count = count(get_notif_update_product('1',$store_id));
			$color = 'danger';
			break;

		default: break;
	}
	$return = array('count' => $count, 'color' => $color);
	return $return;
}

function getCity($id=""){
	$CI  = getCI();
	$city = "";
	if(trim($id)!=""){
		$q = $CI->db->query("
			SELECT * FROM mt_app_city WHERE city_id='".$id."'
			LIMIT 0,1
		")->row();
		if(count($q) > 0){
			$city = $q->city_title;
		}
	}

	return $city;
}

function get_user_list($par=array()){
	$CI = getCI();
	$CI->db->where("is_trash !=",1);
	$CI->db->order_by("user_name","ASC");
	return $CI->db->get("mt_app_user")->result();
}
function get_user_photo($id=""){
	$CI = getCI();
	$user_photo = "";
	if(trim($id)!=""){
		$q = $CI->db->query("
			SELECT * FROM mt_app_user WHERE user_id='".$id."'
			LIMIT 0,1
		")->row();
		if(count($q) > 0){
			$user_photo = $q->user_photo;
		}
	}
	return $user_photo;
}
function get_user_photo_chat($user=""){
	$CI = getCI();
	$user_photo = "";
	if(trim($user)!=""){
		$q = $CI->db->query("
			SELECT * FROM mt_app_user WHERE user_name='".$user."'
			LIMIT 0,1
		")->row();
		if(count($q) > 0){
			$user_photo = $q->user_photo;
		}
	}
	return $user_photo;
}
function get_member_photo($id=""){
	$CI = getCI();
	$member_photo = "";
	if(trim($id)!=""){
		$q = $CI->db->query("
			SELECT * FROM mt_member WHERE member_id='".$id."'
			LIMIT 0,1
		")->row();
		if(count($q) > 0){
			$member_photo = $q->member_photo;
		}
	}
	return $member_photo;
}




function sicepat($province="" , $city="" , $subdistrict=""){
	$CI  = getCI();

	$q = $CI->db->get_where("mt_sicepat",array(
			"province"		=> $province,
			"city"			=> $city,
			"subdistrict"	=> $subdistrict
		))->row();
	return $q;
}

function get_sicepat(){
	$CI  = getCI();

	$CI->db->order_by('province','ASC');
	$CI->db->group_by('province');
	$q = $CI->db->get("mt_sicepat")->result();

	return $q;
}

function get_sicepat_city($city=""){
	$CI  = getCI();

	$CI->db->order_by('city','ASC');
	$CI->db->group_by('city');
	$q = $CI->db->get_where("mt_sicepat",array(
			"province"	=> $city
		))->result();

	return $q;
}

function get_sicepat_dist($city=""){
	$CI  = getCI();

	$CI->db->order_by('subdistrict','ASC');
	$CI->db->group_by('subdistrict');
	$q = $CI->db->get_where("mt_sicepat",array(
			"city"	=> $city
		))->result();

	return $q;
}



function get_status_member($id=""){
	switch ($id) {
	    case '0': $status = 'Menunggu Verifikasi';
	    		  $class  = 'alert-info';
	    		  $alert  = 'Info !';
	    		  $msg    = 'Akun anda belum di verifikasi, silahkan cek email anda untuk melakukan verifikasi atau <a href="'.base_url().'account-verification?type=create" class="alert-link">kirim ulang kode verifikasi</a>';
	    	break;
	    case '1': $status = 'Aktif';
	    		  $class  = 'alert-success';
	    		  $alert  = 'Success !';
	    		  $msg    = 'Selamat akun anda sudah diverifikasi.';
	    	break;
	    case '2': $status = 'Dibekukan';
	    		  $class  = 'alert-warning';
	    		  $alert  = 'Warning !';
	    		  $msg    = 'Maaf akun anda dibekukan.';
	    	break;
	    case '9': $status = 'Banned';
	    		  $class  = 'alert-danger';
	    		  $alert  = 'Error !';
	    		  $msg    = 'Maaf akun anda dibanned.';
	    	break;
	    default:  $status = '';
	    		  $class  = '';
	    		  $alert  = '';
	    		  $msg    = '';
	    	break;
	}
	$result = array( 'status'	=> $status,
                     'class' 	=> $class,
                     'alert' 	=> $alert,
                     'msg' 		=> $msg
                  );
    return $result;
	// $result = get_status_member($id);
    // echo 'Hasil: '.$result['status'];
}

function get_status_default($id=""){
	switch ($id) {
	    case '0': $status = 'No Aktif';
	    		  $class  = 'alert-danger';
	    		  $alert  = 'Info !';
	    		  $msg    = '';
	    	break;
	    case '1': $status = 'Aktif';
	    		  $class  = 'alert-success';
	    		  $alert  = 'Success !';
	    		  $msg    = '';
	    	break;
	    default:  $status = '';
	    		  $class  = '';
	    		  $alert  = '';
	    		  $msg    = '';
	    	break;
	}
	$result = array( 'status'	=> $status,
                     'class' 	=> $class,
                     'alert' 	=> $alert,
                     'msg' 		=> $msg
                  );
    return $result;
	// $result = get_status_default($id);
    // echo 'Hasil: '.$result['status'];
}

function checkIsBlacklist($email){
	$desc   = '';
	$date   = '';
	$found  = FALSE;

	$CI = getCI();
	$m1 = $CI->db->get_where("mt_member_blacklist",array(
		"member_blacklist_email"	=> $email,
		"member_blacklist_status"	=> '9',
		"member_blacklist_istrash"	=> '0'
	))->row();
	if(count($m1)>0){
		$desc   = $m1->member_blacklist_desc;
		$date   = $m1->member_blacklist_date;
		$found  = TRUE;
    }

	$result = array( 'email'	=> $email,
                     'desc' 	=> $desc,
                     'date' 	=> $date,
                     'found' 	=> $found
                  );
    return $result;
	// checkIsBlacklist($this->user_login_email)['found'];
}

function checkIsBlacklistOnLoad(){
	$CI  = getCI();
	$user_login 	  = isset($CI->jCfg['member']['member_id'])?$CI->jCfg['member']['member_id']:'';
    $user_login_email = isset($CI->jCfg['member']['member_email'])?$CI->jCfg['member']['member_email']:'';
	if(trim($user_login_email)!=""){
		if(checkIsBlacklist($user_login_email)['found']){
			$v = $CI->db->get_where("mt_member_notif",array(
				'member_id' 		  => $user_login,
				'member_notif_action' => '9',
				'member_notif_logout' => '1',
				'member_notif_status' => '1'
			),1,0)->row();
			if(count($v)==0){
				$CI->jCfg['member'] = '';
				$CI->_releaseSession();

				$data['msg'] = checkIsBlacklist($user_login_email)['desc'];
				redirect(base_url().'login?msg='.urlencode($data['msg']));
			}
		}
	}
}
