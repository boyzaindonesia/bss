<script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/tinymce/jquery.tinymce.js"></script>

<script language="javascript">

	$(document).ready(function() {

		$('textarea.tinymce').tinymce({

			// Location of TinyMCE script
			script_url : "<?php echo base_url(); ?>assets/plugins/tinymce/tinymce.js",
			mode : "exact",
			theme : "advanced",
			//remove_script_host : false,
			convert_urls : false,
			height:400,
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,youtubeIframe,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,xhtmlxtras",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,absolute",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,code,|,preview,|,forecolor,backcolor,ins,attribs",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",// youtubeIframe ->nambahin youtube 
			theme_advanced_toolbar_location : "top", //top,bottom,exsternal
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			file_browser_callback : "ajaxfilemanager",
			extended_valid_elements:"iframe[src|title|width|height|allowfullscreen|frameborder|class|id]",

			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}

		});

	});

	function ajaxfilemanager(field_name, url, type, win) {
		var ajaxfilemanagerurl = "<?php echo base_url(); ?>assets/plugins/tinymce/plugins/ajaxfilemanager/ajaxfilemanager.php";
		switch (type) {
			case "image":
			break;
			case "media":
			break;
			case "flash": 
			break;
			case "file":
			break;
			default:
			return false;
		}

		tinyMCE.activeEditor.windowManager.open({
			url: "<?php echo base_url(); ?>assets/plugins/tinymce/plugins/ajaxfilemanager/ajaxfilemanager.php",
			width: 682,
			height: 400,
			inline : "yes",
			close_previous : "no"
		},{
			window : win,
			input : field_name
		});
	}
</script>