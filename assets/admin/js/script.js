var $ = jQuery.noConflict();

$(window).load(function($) {

});

$(document).ready(function($) {

	var $winWidth = $(window).width();
	var $winHeight = $(window).height();

    // $(document).on('submit', 'form.ajax_process_orders', ajax_process_orders );

    $(document).on('submit', 'form.ajax_default', ajax_default );

	$(document).on('click touchstart', '.btn-preview-newsletter', function(e){
		e.preventDefault();
		// alert('a');
		// var newsletter_subject = $('input[name="newsletter_subject"]').val();
		// var newsletter_desc = $('textarea[name="newsletter_desc"]').html();

		// $('.newsletter_subject').html(newsletter_subject);
		// $('.newsletter_desc').html(newsletter_desc);
	});

	$(document).scroll(function(){
		var pos = $(window).scrollTop();

	});

	$(window).resize(function(){
		var $winWidth = $(window).width();
		var $winHeight = $(window).height();

	});

	/**
	* Ajax ajax_process_orders
	*/
	// function ajax_process_orders(e){
	// 	if (typeof e !== 'undefined') e.preventDefault();
	// 	var form = $(this);
	// 	var formAction = form.attr('action');
	// 	var oldTitleBtn = form.find('button[type="submit"]').html();

	// 	form.find('button[type="submit"]').attr('disabled','disabled').html('Please wait...');

	// 	$.ajax({
	// 		type: 'POST',
	// 		url: formAction,
	// 		data: form.serialize(),
	// 		async: false,
	// 		cache: false,
	// 		dataType: 'json',
	// 		beforeSend: function(){

	// 		},
	// 		success: function(data){
	// 			if(data.error == false){
	// 				// alert(data.msg);
	// 			    history.pushState("", document.title, window.location.pathname + window.location.search);
	// 				$('html,body').animate({ scrollTop: 0}, 300, function(){
	// 		            if(data.href == ''){
	// 		            	alert(data.msg);
	// 		            	window.location.reload(true);
	// 		            } else {
	// 						window.location = data.href;
	// 					}
	// 				});
	// 			} else {
	// 				alert(data.msg);
	// 	            // window.location.reload(true);
	// 			}
	// 		},
	// 		error: function(jqXHR){
	// 			var response = jqXHR.responseText;
	//             // console.log(jqXHR);
	// 			alert(response);
	// 		}
	// 	});

	// 	form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);

	// 	return false;
	// }


	/**
	* Ajax ajax_default
	*/
	function ajax_default(e){
	    if (typeof e !== 'undefined') e.preventDefault();
	    var $this = $(this);
	    var form  = $this;
		var formAction  = form.attr('action');
		var oldTitleBtn = form.find('button[type="submit"]').html();

		form.find('button[type="submit"]').attr('disabled','disabled').html('Please wait...');

	    swal({
	        title: "Loading!",
	        text: "",
	        type: "loading",
	        showConfirmButton: false,
	        allowOutsideClick: false,
	        customClass: 'swal2-small'
	    });

	    setTimeout(function(){
	        $.ajax({
	            type: 'POST',
	            url: form.attr('action'),
	            data: form.serialize(),
	            async: false,
	            cache: false,
	            dataType: 'json',
	            beforeSend: function(){

	            },
	            success: function(data){
	                if(data.err == false){

	                    swal({
	                        title: "Success!",
	                        text: data.msg,
	                        type: "success",
	                        showConfirmButton: false,
	                        timer: 1500
	                    }).then(
	                    function () {},
	                    function (dismiss) {
	                        setTimeout(function(){
	                            window.location.reload(true);
	                        },300);
	                    });

	                } else {
	                    swal({
	                        title: "Error!",
	                        html: data.msg,
	                        type: "error"
	                    });
	                }
	            },
	            error: function(jqXHR){
	                var response = jqXHR.responseText;
	                swal({
	                    title: "Error!",
	                    html: response,
	                    type: "error"
	                });
	            }
	        });

			form.find('button[type="submit"]').removeAttr('disabled').html(oldTitleBtn);
	        return false;
	    },400);
	}

    $(document).on('change', '[data-province]', get_province_city );

});

function get_province_city(){
	var province = $('[data-province]');
	var thisVal  = $('option:selected', province).val();
	$('[data-city]').attr('data-id','');

    $.ajax({
		type: 'POST',
        url: MOD_URL+'ajax-function/get_province_city',
        data: {'thisVal':thisVal, 'thisAction':'check'},
		async: false,
		cache: false,
		dataType: 'json',
		beforeSend: function(){
			$('[data-city]').html('<option value="" selected>--- Pilih ---</option>');
		},
		success: function(data){
			$('[data-city]').append(data.result);

			setTimeout(function(){
				var thisId   = $('[data-city]').attr('data-id');
				if(thisId=='' || thisId==undefined || thisId=='undefined' ){ thisId = ''; }
				$('[data-city]').val(thisId);
			}, 200);
		},
		error: function(jqXHR){
			var response = jqXHR.responseText;
			alert(response);
		}
	});
}

