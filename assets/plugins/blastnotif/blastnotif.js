var $ = jQuery.noConflict();
$(document).ready(function() {
	
	function check_member_notif(){
		$.ajax({
            type: 'POST',
            url: MOD_URL+'check-member-notif',
            data: {'thisAction':'check'},
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
                if(data.found){
                	alert(data.msg);
	                if(data.url!=''){ window.location.href = data.url; }

	                if(data.repeat){ 
	                	setInterval( function(){ 
							alert(data.msg);
						},539);
	                }
                }
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                alert(jqXHR);
                console.log(response);
            }
        });
	}
	setInterval( function(){ 
		check_member_notif();
	},9339);

	check_member_notif();

	// function reload_notif(thisId,thisAction){
	// 	$.ajax({
	// 		type: 'POST',
 //            url: MOD_URL+'reload-notif',
 //            data: {'thisId':thisId,'thisAction':thisAction},
 //            async: false,
 //            cache: false,
 //            dataType: 'json',
	// 		beforeSend: function() {
				
	// 		},
	// 		success: function(data) {
	// 			if(data.status == 1){
	// 				if(tampungId == '1'){
	// 					$idDiv = 'notif-message-content';
	// 				} else if(tampungId == '2'){
	// 					$idDiv = 'notif-testimonial-content';
	// 				} else if(tampungId == '3'){
	// 					$idDiv = 'notif-payment-content';
	// 				}
	// 				$('#'+$idDiv+' .dropdown-menu > ul').html(data.success);
					
	// 				if(data.countNotif == 0){
	// 					$('#'+$idDiv+' .notif').removeClass('active').html("");
	// 				} else {
	// 					$('#'+$idDiv+' .notif').addClass('active').html(data.countNotif);
						
	// 					if($('body').find('#ns-notification').length > 0){
	// 						$('#ns-notification').html(data.notif);
	// 					} else {
	// 						  var elDiv =  document.createElement("ul");
	// 							  elDiv.setAttribute("id", 'ns-notification');
	// 							  elDiv.setAttribute("class", 'ns-notification');
	// 						  $('body').append( elDiv );
	// 						  $('#ns-notification').html(data.notif);
	// 					}
	// 					setTimeout( function(){ 
	// 						$('#ns-notification > li').removeClass('show').addClass('hide');
	// 						setTimeout( function(){ 
	// 							$('#ns-notification > li').remove();
	// 							clearTimeout(this);
	// 						},1300);
	// 						clearTimeout(this);
	// 					},10000);						
	// 				}
					
	// 			}
	// 		}
	// 	});
	// }
	// setInterval( function(){ 
	// 	reload_notif('1','reload');
	// },10000);
	// // setInterval( function(){ 
	// // 	reload_notif('2','reload');
	// // },8889);
	// setInterval( function(){ 
	// 	reload_notif('3','reload');
	// },9999);
	
	// reload_notif('1','load');
	// // var xx0 = setTimeout( function(){
	// // 	reload_notif('2','load');
	// // 	clearTimeout(xx0);
	// // },100);
	// var xx1 = setTimeout( function(){
	// 	reload_notif('3','load');
	// 	clearTimeout(xx1);
	// },159);

});