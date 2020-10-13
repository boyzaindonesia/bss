$(document).ready(function(){

	if(window.location.hash) {
		var id = window.location.hash.substring(1);
		var toTop = $('.wrapper > .top-navbar').height();
		$('html,body').animate({ scrollTop: $("#"+id).offset().top - toTop}, 700);
	}

	/** SIDEBAR FUNCTION **/
	$('.sidebar-left ul.sidebar-menu li a').click(function() {
		"use strict";
		$('.sidebar-left li').removeClass('active');
		$(this).closest('li').addClass('active');
		var checkElement = $(this).next();
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				$(this).closest('li').removeClass('active');
				checkElement.slideUp('fast');
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('.sidebar-left ul.sidebar-menu ul:visible').slideUp('fast');
				checkElement.slideDown('fast');
			}
			if($(this).closest('li').find('ul').children().length == 0) {
				return true;
				} else {
				return false;
			}
	});

	if ($(window).width() < 1025) {
		$(".sidebar-left").removeClass("sidebar-nicescroller");
		$(".sidebar-right").removeClass("sidebar-nicescroller");
		$(".nav-dropdown-content").removeClass("scroll-nav-dropdown");
	}
	/** END SIDEBAR FUNCTION **/


	/** BUTTON TOGGLE FUNCTION **/
	$(".btn-collapse-sidebar-left").click(function(){
		"use strict";
		$(".top-navbar").toggleClass("toggle");
		$(".sidebar-left").toggleClass("toggle");
		$(".page-content").toggleClass("toggle");
		$(".icon-dinamic").toggleClass("rotate-180");
		setTimeout("reconstructIsotope();", 200 );
	});
	$(".btn-collapse-sidebar-right").click(function(){
		"use strict";
		$(".top-navbar").toggleClass("toggle-left");
		$(".sidebar-left").toggleClass("toggle-left");
		$(".sidebar-right").toggleClass("toggle-left");
		$(".page-content").toggleClass("toggle-left");
		setTimeout("reconstructIsotope();", 200 );
	});
	$(".btn-collapse-nav").click(function(){
		"use strict";
		$(".icon-plus").toggleClass("rotate-45");
		setTimeout("reconstructIsotope();", 200 );
	});
	/** END BUTTON TOGGLE FUNCTION **/

	/** BEGIN TOOLTIP FUNCTION **/
	$('.tooltips').tooltip({
	  selector: "[data-toggle=tooltip]",
	  container: "body"
	})
	$('.popovers').popover({
	  selector: "[data-toggle=popover]",
	  container: "body"
	})
	/** END TOOLTIP FUNCTION **/


	/** NICESCROLL AND SLIMSCROLL FUNCTION **/
    $(".sidebar-nicescroller").niceScroll({
		cursorcolor: "#121212",
		cursorborder: "0px solid #fff",
		cursorborderradius: "0px",
		cursorwidth: "0px"
	});
	$(".sidebar-nicescroller").getNiceScroll().resize();

	if($(".sidebar-menu .submenu li.active").length > 0){
		$(".sidebar-menu .submenu li.active").parents('.submenu').addClass('visible');
		$(".sidebar-menu .submenu li.active").parents('li').addClass('active');
	}
	$scrollTo = 0;
	if($(".sidebar-nicescroller .sidebar-menu li.active").length > 0){
		var parent_height = $(".sidebar-menu").parents('.sidebar-left').height();
		$('.sidebar-menu .submenu li.active');
		$scrollTo = $(".sidebar-nicescroller .sidebar-menu li.active").position().top;
		if(parent_height < $scrollTo){
			$(".sidebar-nicescroller").getNiceScroll(0).doScrollTop($scrollTo - (parent_height / 3), 200); // Scroll X Axis
		}
	}

    $(".right-sidebar-nicescroller").niceScroll({
		cursorcolor: "#111",
		cursorborder: "0px solid #fff",
		cursorborderradius: "0px",
		cursorwidth: "0px"
	});
	$(".right-sidebar-nicescroller").getNiceScroll().resize();

	$(function () {
		"use strict";
		$('.scroll-nav-dropdown').slimScroll({
			height: '350px',
			position: 'right',
			size: '4px',
			railOpacity: 0.3
		});
	});

	$(function () {
		"use strict";
		$('.scroll-chat-widget').slimScroll({
			height: '330px',
			position: 'right',
			size: '4px',
			railOpacity: 0.3,
			railVisible: true,
			alwaysVisible: true,
			start : 'bottom'
		});

		$('.scroll-chatbox').slimScroll({
			height: '200px',
			position: 'right',
			size: '4px',
			railOpacity: 0.3,
			railVisible: true,
			alwaysVisible: true,
			start : 'bottom'
		});
	});
	if ($(window).width() < 768) {
		$(".chat-wrap").removeClass("scroll-chat-widget");
		$(".chat-wrap").removeClass("scroll-chatbox");
	}
	/** END NICESCROLL AND SLIMSCROLL FUNCTION **/




	/** BEGIN PANEL HEADER BUTTON COLLAPSE **/
	$(function () {
		"use strict";
		$('.collapse').on('show.bs.collapse', function() {
			var id = $(this).attr('id');
			$('button.to-collapse[data-target="#' + id + '"]').html('<i class="fa fa-chevron-up"></i>');
		});
		$('.collapse').on('hide.bs.collapse', function() {
			var id = $(this).attr('id');
			$('button.to-collapse[data-target="#' + id + '"]').html('<i class="fa fa-chevron-down"></i>');
		});

		$('.collapse').on('show.bs.collapse', function() {
			var id = $(this).attr('id');
			$('a.block-collapse[href="#' + id + '"] span.right-icon').html('<i class="glyphicon glyphicon-minus icon-collapse"></i>');
		});
		$('.collapse').on('hide.bs.collapse', function() {
			var id = $(this).attr('id');
			$('a.block-collapse[href="#' + id + '"] span.right-icon').html('<i class="glyphicon glyphicon-plus icon-collapse"></i>');
		});
	});
	/** END PANEL HEADER BUTTON COLLAPSE **/

	/** sortTable custom **/
	// $('.sortTable thead .sorting').on('click', function() {
	// 	var $href = $(this).attr('data-href');
	// 	window.location = $href;
	// });

	/** BEGIN INPUT FILE **/
	if ($('.btn-file').length > 0){
		$(document)
			.on('change', '.btn-file :file', function() {
				"use strict";
				var input = $(this),
				numFiles = input.get(0).files ? input.get(0).files.length : 1,
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
				input.trigger('fileselect', [numFiles, label]);
		});
		$('.btn-file :file').on('fileselect', function(event, numFiles, label) {

			var input = $(this).parents('.input-group').find(':text'),
				log = numFiles > 1 ? numFiles + ' files selected' : label;

			if( input.length ) {
				input.val(log);
			} else {
				// if( log ) alert(log);
			}
		});
	}
	/** END INPUT FILE **/




	/** BEGIN DATEPICKER **/
	// if ($('.datepicker').length > 0){
	// 	$('.datepicker').datepicker()
	// }

	// if ($('#datepicker1').length > 0){
	// 	var nowTemp = new Date();
	// 	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

	// 	var checkin = $('#datepicker1').datepicker({
	// 	  onRender: function(date) {
	// 		return date.valueOf() < now.valueOf() ? 'disabled' : '';
	// 	  }
	// 	}).on('changeDate', function(ev) {
	// 	  if (ev.date.valueOf() > checkout.date.valueOf()) {
	// 		var newDate = new Date(ev.date)
	// 		newDate.setDate(newDate.getDate() + 1);
	// 		checkout.setValue(newDate);
	// 	  }
	// 	  checkin.hide();
	// 	  $('#datepicker2')[0].focus();
	// 	}).data('datepicker');
	// 	var checkout = $('#datepicker2').datepicker({
	// 	  onRender: function(date) {
	// 		return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
	// 	  }
	// 	}).on('changeDate', function(ev) {
	// 	  checkout.hide();
	// 	}).data('datepicker');
	// }
	/** END DATEPICKER **/

	/** BEGIN TIMEPICKER **/
	// if ($('.timepicker').length > 0){
	// 	$('.timepicker').timepicker();
	// }
	/** END TIMEPICKER **/

	/** BEGIN EXAMPLE SIMPLE WIZARD **/
	if ($('.NextStep').length > 0){
		$('.NextStep').click(function(){
		"use strict";
		  var nextId = $(this).parents('.tab-pane').next().attr("id");
		  $('[href=#'+nextId+']').tab('show');
		})
	}
	if ($('.PrevStep').length > 0){
		$('.PrevStep').click(function(){
		"use strict";
		  var prevId = $(this).parents('.tab-pane').prev().attr("id");
		  $('[href=#'+prevId+']').tab('show');
		})
	}
	/** END EXAMPLE SIMPLE WIZARD **/


	/** BEGIN SLIDER **/
	// if ($('#sl1').length > 0){
	// 	$('#sl1').slider({
	// 	  formater: function(value) {
	// 		return 'Current value: '+value;
	// 	  }
	// 	});
	// }
	// if ($('#sl2').length > 0){
	// 	$('#sl2').slider();
	// }
	// if ($('#eg').length > 0){
	// 	$('#eg input').slider();
	// }
	/** END SLIDER **/


	/** BEGIN MAGNIFIC POPUP **/
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
	/** END MAGNIFIC POPUP **/


	/** POPUP CUSTOM **/
    $(document).on('click', '.popup .popup-close, .popup .popup-container-close', function(e){
        e.preventDefault();
        var $this   = $(this);
        var $popup  = $this.parents('.popup');
        $popup.removeClass('active');
        $('html, body').css('overflow','');

		var removeContent = $this.attr('data-remove-content');
		if(removeContent=='' || removeContent==undefined || removeContent=='undefined' ){ removeContent = 'true'; }

		if(removeContent == 'true'){
	        setTimeout(function(){
		        $popup.find('.popup-content').html("");
	        }, 600);
	    }
    });


	/** TABLE CLICK **/
    $(document).on('click', '.table-click tbody tr', function(e){
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('selected') ) {
        	$this.removeClass('selected');
        } else {
        	$this.parents('tbody').find('tr.selected').removeClass('selected');
        	$this.addClass('selected');
        }
    });
    $(document).on('click', '.table-click-multiple tbody tr', function(e){
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('selected') ) {
        	$this.removeClass('selected');
        } else {
        	$this.addClass('selected');
        }
    });
    $(document).on('click', '.table-click-checkbox tbody tr', function(e){
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('selected') ) {
        	$this.removeClass('selected');
        	$this.find('input[type="checkbox"]').prop('checked', false);
        } else {
        	$this.addClass('selected');
        	$this.find('input[type="checkbox"]').prop('checked', true);
        }
    });

	/** COPY TO CLIPBOARD **/
	$(document).on('click','.btn-copy-clipboard',function(){
        var $this = $(this);
		var $temp = $("<textarea>");
		var brRegex = /<br\s*[\/]?>/gi;
		$("body").append($temp);
		var copyText = $this.attr('data-text-clipboard');
		$temp.val(copyText.replace(brRegex, "\r\n")).select();
		document.execCommand("copy");
		$temp.remove();
        notifyMessage("Copied: " + copyText, 'success');
    });


	/** BEGIN OWL CAROUSEL **/
	// if ($('#owl-lazy-load').length > 0){
	//   $("#owl-lazy-load").owlCarousel({
	// 	items : 5,
	// 	lazyLoad : true,
	// 	navigation : true
	//   });
	// }

	// if ($('#owl-lazy-load-autoplay').length > 0){
	//   $("#owl-lazy-load-autoplay").owlCarousel({
	// 	autoPlay: 3000,
	// 	items : 5,
	// 	lazyLoad : true
	//   });
	// }

	// if ($('#owl-lazy-load-gallery').length > 0){
	//   $("#owl-lazy-load-gallery").owlCarousel({
	// 	items : 5,
	// 	lazyLoad : true,
	// 	navigation : true
	//   });
	// }


	// var Owltime = 7;
	// var $progressBar,
	//   $bar,
	//   $elem,
	//   isPause,
	//   tick,
	//   percentTime;

	// if ($('#owl-single-progress-bar').length > 0){
	// 	$("#owl-single-progress-bar").owlCarousel({
	// 	  slideSpeed : 500,
	// 	  paginationSpeed : 500,
	// 	  singleItem : true,
	// 	  afterInit : progressBar,
	// 	  afterMove : moved,
	// 	  startDragging : pauseOnDragging
	// 	});
	// }

	// function progressBar(elem){
	//   $elem = elem;
	//   buildProgressBar();
	//   start();
	// }

	// function buildProgressBar(){
	//   $progressBar = $("<div>",{
	// 	id:"OwlprogressBar"
	//   });
	//   $bar = $("<div>",{
	// 	id:"Owlbar"
	//   });
	//   $progressBar.append($bar).prependTo($elem);
	// }

	// function start() {
	//   percentTime = 0;
	//   isPause = false;
	//   tick = setInterval(interval, 10);
	// };

	// function interval() {
	//   if(isPause === false){
	// 	percentTime += 1 / Owltime;
	// 	$bar.css({
	// 	   width: percentTime+"%"
	// 	 });
	// 	if(percentTime >= 100){
	// 	  $elem.trigger('owl.next')
	// 	}
	//   }
	// }

	// function pauseOnDragging(){
	//   isPause = true;
	// }

	// function moved(){
	//   clearTimeout(tick);
	//   start();
	// }
	/** END OWL CAROUSEL **/




	/** BEGIN REAL ESTATE DESIGN JS FUNCTION **/
	// var imagesync1 = $("#imagesync1");
	// var imagesync2 = $("#imagesync2");

	//   imagesync1.owlCarousel({
	// 	singleItem : true,
	// 	slideSpeed : 1000,
	// 	navigation: false,
	// 	pagination:false,
	// 	afterAction : syncPosition,
	// 	lazyLoad : true,
	// 	responsiveRefreshRate : 200
	//   });

	//   imagesync2.owlCarousel({
	// 	items : 5,
	// 	itemsDesktop      : [1199,5],
	// 	itemsDesktopSmall : [979,4],
	// 	itemsTablet       : [768,3],
	// 	itemsMobile       : [479,2],
	// 	pagination:false,
	// 	responsiveRefreshRate : 100,
	// 	afterInit : function(el){
	// 	  el.find(".owl-item").eq(0).addClass("synced");
	// 	}
	//   });

	//   function syncPosition(el){
	// 	var current = this.currentItem;
	// 	$("#imagesync2")
	// 	  .find(".owl-item")
	// 	  .removeClass("synced")
	// 	  .eq(current)
	// 	  .addClass("synced")
	// 	if($("#imagesync2").data("owlCarousel") !== undefined){
	// 	  center(current)
	// 	}
	//   }
	//  if ($('#imagesync2').length > 0){
	//   $("#imagesync2").on("click", ".owl-item", function(e){
	// 	e.preventDefault();
	// 	var number = $(this).data("owlItem");
	// 	imagesync1.trigger("owl.goTo",number);
	//   });
	//  }
	//   function center(number){
	// 	var imagesync2visible = imagesync2.data("owlCarousel").owl.visibleItems;
	// 	var num = number;
	// 	var found = false;
	// 	for(var i in imagesync2visible){
	// 	  if(num === imagesync2visible[i]){
	// 		var found = true;
	// 	  }
	// 	}

	// 	if(found===false){
	// 	  if(num>imagesync2visible[imagesync2visible.length-1]){
	// 		imagesync2.trigger("owl.goTo", num - imagesync2visible.length+2)
	// 	  }else{
	// 		if(num - 1 === -1){
	// 		  num = 0;
	// 		}
	// 		imagesync2.trigger("owl.goTo", num);
	// 	  }
	// 	} else if(num === imagesync2visible[imagesync2visible.length-1]){
	// 	  imagesync2.trigger("owl.goTo", imagesync2visible[1])
	// 	} else if(num === imagesync2visible[0]){
	// 	  imagesync2.trigger("owl.goTo", num-1)
	// 	}

	//   }

	//   if ($('#property-slide-1').length > 0){
	// 	  $("#property-slide-1").owlCarousel({
	// 		  navigation : false,
	// 		  pagination: false,
	// 		  slideSpeed : 300,
	// 		  paginationSpeed : 400,
	// 		  singleItem:true
	// 	  });
	//   }

	//   if ($('#property-slide-2').length > 0){
	// 	  $("#property-slide-2").owlCarousel({
	// 		  navigation : false,
	// 		  pagination: false,
	// 		  slideSpeed : 300,
	// 		  paginationSpeed : 400,
	// 		  singleItem:true
	// 	  });
	//   }

	//   if ($('#property-slide-3').length > 0){
	// 	  $("#property-slide-3").owlCarousel({
	// 		  navigation : false,
	// 		  pagination: false,
	// 		  slideSpeed : 300,
	// 		  paginationSpeed : 400,
	// 		  singleItem:true
	// 	  });
	//   }

	//   if ($('#property-slide-4').length > 0){
	// 	  $("#property-slide-4").owlCarousel({
	// 		  navigation : false,
	// 		  pagination: false,
	// 		  slideSpeed : 300,
	// 		  paginationSpeed : 400,
	// 		  singleItem:true
	// 	  });
	//   }

	//   if ($('#property-slide-5').length > 0){
	// 	  $("#property-slide-5").owlCarousel({
	// 		  navigation : false,
	// 		  pagination: false,
	// 		  slideSpeed : 300,
	// 		  paginationSpeed : 400,
	// 		  singleItem:true
	// 	  });
	//   }

	//   if ($('#property-slide-7').length > 0){
	// 	  $("#property-slide-7").owlCarousel({
	// 			navigation : false,
	// 			pagination: false,
	// 			slideSpeed : 1000,
	// 			paginationSpeed : 400,
	// 			singleItem:true,
	// 			autoPlay: 4000,
	// 			transitionStyle : 'goDown'
	// 	  });
	//   }

	//   if ($('#property-slide-8').length > 0){
	// 	  $("#property-slide-8").owlCarousel({
	// 			navigation : false,
	// 			pagination: false,
	// 			slideSpeed : 1000,
	// 			paginationSpeed : 400,
	// 			singleItem:true,
	// 			autoPlay: 3000,
	// 			transitionStyle : 'fadeUp'
	// 	  });
	//   }
	/** END REAL ESTATE DESIGN JS FUNCTION **/



	/** BEGIN BLOG DESIGN JS FUNCTION **/
	  // if ($('#blog-slide-1').length > 0){
		 //  $("#blog-slide-1").owlCarousel({
			// 	navigation : false,
			// 	pagination: false,
			// 	slideSpeed : 1000,
			// 	paginationSpeed : 400,
			// 	singleItem:true,
			// 	autoPlay: 3000,
			// 	transitionStyle : 'goDown'
		 //  });
	  // }
	  // if ($('#blog-slide-2').length > 0){
		 //  $("#blog-slide-2").owlCarousel({
			// 	navigation : false,
			// 	pagination: false,
			// 	slideSpeed : 1000,
			// 	paginationSpeed : 400,
			// 	singleItem:true
		 //  });
	  // }
	/** END BLOG DESIGN JS FUNCTION **/



	/** BEGIN STORE DESIGN JS FUNCTION **/
	// if ($('#store-item-carousel-1').length > 0){
	// $("#store-item-carousel-1").owlCarousel({
	// 	autoPlay: 4000,
	// 	items : 4,
	// 	itemsDesktop      : [1199,4],
	// 	itemsDesktopSmall : [979,3],
	// 	itemsTablet       : [768,2],
	// 	itemsMobile       : [479,1],
	// 	lazyLoad : true,
	// 	autoHeight : true
	//   });
	// }
	// if ($('#store-item-carousel-2').length > 0){
	//   $("#store-item-carousel-2").owlCarousel({
	// 		navigation : false,
	// 		pagination: false,
	// 		slideSpeed : 1000,
	// 		paginationSpeed : 400,
	// 		singleItem:true
	//   });
	// }
	// if ($('#store-item-carousel-3').length > 0){
	// 	$("#store-item-carousel-3").owlCarousel({
	// 		autoPlay: 4000,
	// 		items : 4,
	// 		itemsDesktop      : [1199,4],
	// 		itemsDesktopSmall : [979,3],
	// 		itemsTablet       : [768,2],
	// 		itemsMobile       : [479,1],
	// 		lazyLoad : true,
	// 		autoHeight : true,
	// 		navigation : false,
	// 		pagination: false
	// 	  });
	// }
	/** END STORE DESIGN JS FUNCTION **/



	/** BEGIN TILES JS FUNCTION **/
	  // if ($('#tiles-slide-1').length > 0){
		 //  $("#tiles-slide-1").owlCarousel({
			// 	navigation : true,
			// 	pagination: false,
			// 	slideSpeed : 1000,
			// 	paginationSpeed : 400,
			// 	singleItem:true,
			// 	autoPlay: 3000,
			// 	theme : "my-reminder",
			// 	navigationText : ["&larr;","&rarr;"],
		 //  });
	  // }
	  // if ($('#tiles-slide-2').length > 0){
		 //  $("#tiles-slide-2").owlCarousel({
			// 	navigation : false,
			// 	pagination: false,
			// 	slideSpeed : 1000,
			// 	paginationSpeed : 400,
			// 	singleItem:true,
			// 	autoPlay: 3000,
			// 	transitionStyle : 'backSlide',
			// 	stopOnHover: true
		 //  });
	  // }
	  // if ($('#tiles-slide-3').length > 0){
		 //  $("#tiles-slide-3").owlCarousel({
			// 	navigation : false,
			// 	pagination: false,
			// 	slideSpeed : 1000,
			// 	paginationSpeed : 400,
			// 	singleItem:true,
			// 	autoPlay: 3235,
			// 	stopOnHover: true
		 //  });
	  // }
	/** END TILES JS FUNCTION **/

	/** BEGIN MY PHOTOS COLLECTION FUNCTION **/
	// if ($('#photo-collection-1').length > 0){
	//   $("#photo-collection-1").owlCarousel({
	// 		navigation : false,
	// 		pagination: false,
	// 		slideSpeed : 1000,
	// 		paginationSpeed : 400,
	// 		singleItem:true,
	// 		autoPlay: 3000,
	// 		transitionStyle : 'fadeUp'
	//   });
	// }
	/** BEGIN MY PHOTOS COLLECTION FUNCTION **/


	/** AUTOMATIC REFRESH FUNCTION **/
     // var time = new Date().getTime();
     // $(document.body).bind("mousemove keypress", function(e) {
     //     time = new Date().getTime();
     // });
     // function refresh() {
     //     if(new Date().getTime() - time >= 1200000){ // 5minutes
     //         window.location.reload(true);
     //     } else {
     //         setTimeout(refresh, 20000);
     //     }
     // }
     // setTimeout(refresh, 20000);

    /** AUTOMATIC CHANGE NOTIF **/
  //    $('.nav-notif .dropdown .dropdown-toggle').on('click', function(){
		// var $this       = $(this);
		// var $dropdown   = $this.parents('.dropdown');
		// var $sc         = $dropdown.data('sc');
		// var $icon_count = $this.find('.icon-count');
		// var $count      = $icon_count.html();

		// if($dropdown.hasClass('open') && $count != ''){
		// 	$.ajax({
		// 		type: 'GET',
		// 		url: './func/change_notif.php',
		// 		data: 'sc='+$sc,
		// 		async: false,
		// 		cache: false,
		// 		dataType: 'json',
		// 		beforeSend: function(){

		// 		},
		// 		success: function(data){
		// 			if(data.error == false){
		// 			    $dropdown.find('.dropdown-menu .newnotif').removeClass('newnotif').addClass('unread');
		// 			    $icon_count.html('');
		// 			}
		// 		},
		// 		error: function(jqXHR){
		// 			var response = jqXHR.responseText;
		//             // console.log(jqXHR);
		// 			alert(response);
		// 		}
		// 	});
		// }
  //    });



	/** DEMO PANEL **/
	$("#demo-panel").click(function(){
		"use strict";
		$(".box-demo").toggleClass("tugel");
	});
	$(".change-theme").click(function(){
		"use strict";
		var bg = $(this).data('bg');
		var color = $(this).data('color');
		$("body, .logo-brand, .page-content").removeClass("default-color");
		$("body, .logo-brand, .page-content").removeClass("white-color");
		$("body, .logo-brand, .page-content").removeClass("primary-color");
		$("body, .logo-brand, .page-content").removeClass("info-color");
		$("body, .logo-brand, .page-content").removeClass("success-color");
		$("body, .logo-brand, .page-content").removeClass("danger-color");
		$("body, .logo-brand, .page-content").removeClass("warning-color");
		$(".sidebar-left").removeClass("default-color");
		$(".sidebar-left").removeClass("light-color");

		$(".nav-tabs").removeClass("nav-default");
		$(".nav-tabs").removeClass("nav-white");
		$(".nav-tabs").removeClass("nav-primary");
		$(".nav-tabs").removeClass("nav-info");
		$(".nav-tabs").removeClass("nav-success");
		$(".nav-tabs").removeClass("nav-danger");
		$(".nav-tabs").removeClass("nav-warning");

		$(".dropdown-menu").removeClass("default");
		$(".dropdown-menu").removeClass("white");
		$(".dropdown-menu").removeClass("primary");
		$(".dropdown-menu").removeClass("info");
		$(".dropdown-menu").removeClass("success");
		$(".dropdown-menu").removeClass("danger");
		$(".dropdown-menu").removeClass("warning");

		if(bg != ''){ $(".sidebar-left").addClass(bg+"-color"); }
		if(color != ''){
			$("body, .logo-brand, .page-content").addClass(color+"-color");
			$(".nav-tabs").addClass("nav-"+color);
			$(".dropdown-menu").addClass(color);
		}

		$.ajax({
            type: 'POST',
            url: MOD_URL+'admin/me/change_background',
            data: {'thisBg':bg,'thisColor':color,'thisAction':'save'},
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
            	notifyMessage('Update background & color success', 'success');
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                notifyMessage('Update background & color failed', 'danger');
            }
        });

	});
	/** END DEMO PANEL **/

	/** CUSTOM KEREHORE **/

	/* ---------------------------------------------------------------------- */
	/*	Isotope Code
	/* ---------------------------------------------------------------------- */

	// if ($('.isotope').length > 0){
	// 	var $isotope	 	= $('.isotope');
	// 	var $filter 		= $('.isotope-filter');

	// 	$(window).bind('resize', function(){
	// 		var selector = $filter.find('.active').attr('data-filter');
	// 		try {
	// 			$isotope.isotope({
	// 				filter	: selector,
	// 				animationOptions: {
	// 					duration: 750,
	// 					easing	: 'linear',
	// 					queue	: false,
	// 		   		}
	// 			});
	// 		  	return false;
	// 		} catch(err) {

	// 		}
	// 	});

	// 	// Isotope Filter
	// 	$filter.find('a').live('click', function(){
	// 		$filter.find('a').removeClass('active');
	// 		$(this).addClass('active');

	// 		var selector = $(this).attr('data-filter');
	// 		try {
	// 			$isotope.isotope({
	// 				filter	: selector,
	// 				animationOptions: {
	// 					duration: 750,
	// 					easing	: 'linear',
	// 					queue	: false,
	// 		   		}
	// 			});
	// 		  	return false;
	// 		} catch(err) {

	// 		}
	// 	});

	// 	// Run Isotope
	// 	$(window).load(function(){
	// 		try {
	// 			$isotope.isotope({
	// 				filter				: '*',
	// 				layoutMode   		: 'masonry',
	// 				animationOptions	: {
	// 				duration			: 750,
	// 				easing				: 'linear'
	// 			   }
	// 			});
	// 		} catch(err) {

	// 		}
	// 	});
	// }

    if($('.check-autocomplete').length > 0 ){
    	// <div class="col-lg-5 has-feedback">
	    // 	<input type="search" name="name" value="" class="form-control check-autocomplete" />
	    // 	<div class="form-autocomplete-feedback"></div>
    	// </div>

    	$('.check-autocomplete').each(function() {
			"use strict";
			var $id = $(this).attr("id");
			var $name = $(this).attr("name");
			if($id==''||$id==undefined||$id=='undefined'){ $(this).attr('id',$name);  }
		});

    	var $search = null;
		$(".check-autocomplete").keyup(function() {
	        var $this     = $(this);
	        var $id   	  = $this.attr('id');
			var $inputId  = document.getElementById($id);
	        var $thisVal  = $this.val();
	        var $feedback = $this.parents('.has-feedback').find(".form-autocomplete-feedback");
	        var $thisH    = $this.parents('.has-feedback').height();
	        var $thisW    = $this.parents('.has-feedback').width();
	        if($thisVal != '' && $thisVal.length >= 1){
	        	if ($search != null){ $search.abort(); }
                delay(function(){
		            $search = $.ajax({
		                type: 'POST',
		                url: OWN_LINKS+'/check_autocomplete',
		                data: {'thisVal':$thisVal,'thisAction':'check_autocomplete'},
		                async: false,
		                cache: false,
		                dataType: 'json',
		                success: function(data){
		                    $feedback.html(data.msg).css({'top':$thisH+'px','width':$thisW+'px'}).show();
		                    if($this.attr('required')){
							    $inputId.setCustomValidity('No matching records.');
							}
		                },
		                error: function(jqXHR){
		                    var response = jqXHR.responseText;
		                    alert('error ajax');
		                }
		            });
		        },300);
	        }
	        return false;
	    });

	    $(document).on('click', '.form-autocomplete-feedback .feedback', function(e){
	        e.preventDefault();
	        var $this   = $(this);
	        var $name   = $this.find('.name').html();
	        var $decoded = $("<div/>").html($name).text();
	        $this.parents('.has-feedback').find('.check-autocomplete').val($decoded);

	        var $id   	  = $this.parents('.has-feedback').find('.check-autocomplete').attr('id');
			var $inputId  = document.getElementById($id);
	        if($decoded!=''){ $inputId.setCustomValidity(''); }
	    });

	    $(document).on('click', function(e){
	        var $clicked = $(e.target);
	        if (!$clicked.hasClass("check-autocomplete")){
	            $(".form-autocomplete-feedback").fadeOut();
	        }
	    });

	    $('.check-autocomplete').click(function(){
	        var $this     = $(this);
	        var $feedback = $this.parents('.has-feedback').find(".form-autocomplete-feedback");
	        if($feedback.html() != '') {
	            $feedback.fadeIn();
	        }
	        if($this.val().length < 1){
	            $feedback.html("").fadeOut();
	        }
	    });
	}

	/**
	* pushState remove msg dan err
	*/
	function removeUrlMsg() {
		var href = window.location.href;
		if(href.indexOf("msg") > -1) {
		    history.pushState(null, null, href.replace(/&msg=.*(&?)/, '$1'));
		} else if(href.indexOf("err") > -1) {
		    history.pushState(null, null, href.replace(/&err=.*(&?)/, '$1'));
		}
	}
	removeUrlMsg();

	//check for navigation time API support
    if (window.performance) {
        console.info("window.performance work's fine on this browser");
    }
    if (performance.navigation.type == 1) {
        console.info( "This page is reloaded" );
  //       var href = window.location.href;
  //       if(href.indexOf("msg") > -1) {
		//     history.pushState(null, null, href.replace(/msg=.*(&?)/, '$1'));
		// } else if(href.indexOf("err") > -1) {
		//     history.pushState(null, null, href.replace(/err=.*(&?)/, '$1'));
		// }
    } else {
        // console.info( "This page is not reloaded");
    }

    if($('.form-control.check').length > 0 ){
    	// <div class="col-lg-5 has-feedback">
	    // 	<input type="text" name="user_name" value="" class="form-control check" data-check-id="" data-check-parent="" data-check-url="" data-check-rel="user_name" data-check-msg="Username belum ada." data-check-err="Username sudah ada." minlength="4" maxlength="25" required>
	    // 	<span class="fa form-control-feedback"></span>
    	// </div>
		$('.form-control.check').each(function() {
			"use strict";
			var $id = $(this).attr("id");
			var $name = $(this).attr("name");
			if($id==''||$id==undefined||$id=='undefined'){ $(this).attr('id',$name);  }
		});

		$('.form-control.check').focus( function(){
			$(this).parents('.has-feedback').find('.form-control-feedback').addClass('fa-spinner').removeClass('fa-check fa-times');
		}).blur( function(){
			var $error         	= false;
            var $msg            = '';
			var $hasfeedback   	= $(this).parents('.has-feedback');
			var $id 		   	= $(this).attr("id");
			var $inputId       	= document.getElementById($id);
			var $thisVal       	= $(this).val();
			var $thisChkId     	= $(this).attr('data-check-id');
			var $thisChkParent 	= $(this).attr('data-check-parent');
			var $thisChkRel    	= $(this).attr('data-check-rel');
			var $thisChkUrl    	= $(this).attr('data-check-url');
			var $thisMsg    	= $(this).attr('data-check-msg');
			var $thisErr 		= $(this).attr('data-check-err');

			var $minlength 		= $(this).attr('minlength');
			if($minlength==''||$minlength==undefined||$minlength=='undefined'){ $minlength=''; }

			if($thisChkUrl==''||$thisChkUrl==undefined||$thisChkUrl=='undefined'){ $thisChkUrl=OWN_LINKS+'/check_form'; }
			if($thisMsg==''||$thisMsg==undefined||$thisMsg=='undefined'){ $thisMsg=''; }
			if($thisErr==''||$thisErr==undefined||$thisErr=='undefined'){ $thisErr=''; }

	    	$hasfeedback.removeClass('has-success has-error');
			$hasfeedback.find('.form-control-feedback').removeClass('fa-spinner fa-check fa-times');
			$hasfeedback.find('.help-block').remove();
			if($thisVal != ''){
				if($minlength!=''){ if($thisVal.length < $minlength){ $error = true; } else { $error = false; } }

				if($(this).attr('type') == 'email'){
			    	var validEmail = isValidEmailAddress($thisVal);
					if(validEmail == false){
			        	$error = true;
		    			$msg   = 'Please enter valid email.';
		        		$hasfeedback.append('<p class="help-block">'+$msg+'</p>');
			        	$hasfeedback.addClass('has-error');
			        	$hasfeedback.find('.form-control-feedback').addClass('fa-times');

					    $inputId.setCustomValidity($msg);
					} else {
						$error = false;
					    $inputId.setCustomValidity('');
					}
				}

	    		if($error == false){
	    			$.ajax({
			            type: 'POST',
			            url: $thisChkUrl,
			            data: {'thisVal':$thisVal,'thisChkId':$thisChkId,'thisChkParent':$thisChkParent,'thisChkRel':$thisChkRel,'thisAction':'check_form'},
			            async: false,
			            cache: false,
			            dataType: 'json',
			            success: function(data){
			            	if(data.err == false){
				        		var $has    = 'has-success';
				        		var $result = 'fa-check';
				        		if($thisMsg==''){ $thisMsg = data.msg; }
				        		if($inputId){ $inputId.setCustomValidity(''); }
				        		if($thisMsg!=''){
					        		$hasfeedback.append('<p class="help-block">'+$thisMsg+'</p>');
					        	}
				        	} else {
				        		var $has    = 'has-error';
				        		var $result = 'fa-times';
				        		if($thisErr==''){ $thisErr = data.msg; }
							    if($inputId){ $inputId.setCustomValidity($thisErr); }
				        		if($thisErr!=''){
					        		$hasfeedback.append('<p class="help-block">'+$thisErr+'</p>');
					        	}
				        	}

				        	$hasfeedback.addClass($has);
				        	$hasfeedback.find('.form-control-feedback').addClass($result);
			            },
			            error: function(jqXHR){
			                var response = jqXHR.responseText;
				            alert('error ajax');
			            }
			        });
				}

			    return false;
			}
		});
	}

	function isValidEmailAddress(emailAddress) {
	    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	    return pattern.test(emailAddress);
	};

	// Confirm Password
	var password = document.getElementById("passwd")
	  , confirm_password = document.getElementById("confirm_passwd");

	function validatePassword(){
	  if(password.value != confirm_password.value) {
	    confirm_password.setCustomValidity("Passwords Don't Match");
	  } else {
	    confirm_password.setCustomValidity('');
	  }
	}
	if(password && confirm_password){
		password.onchange = validatePassword;
		confirm_password.onkeyup = validatePassword;
	}

	// $('input[type="search"]').on('search', function(e) {
 //        if(QueryString.keyword){
 //            if(this.value == '') {
 //                window.location.href = $(this).parents('form').attr('action');
 //            }
 //        }
 //    });
 //    // Get url parameter
 //    var QueryString = function () {
 //        // This function is anonymous, is executed immediately and
 //        // the return value is assigned to QueryString!
 //        var query_string = {};
 //        var query = window.location.search.substring(1);
 //        var vars = query.split("&");
 //        for (var i=0;i<vars.length;i++) {
 //            var pair = vars[i].split("=");
 //            // If first entry with this name
 //            if (typeof query_string[pair[0]] === "undefined") {
 //                query_string[pair[0]] = decodeURIComponent(pair[1]);
 //            // If second entry with this name
 //            } else if (typeof query_string[pair[0]] === "string") {
 //                var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
 //                query_string[pair[0]] = arr;
 //            // If third or later entry with this name
 //            } else {
 //                query_string[pair[0]].push(decodeURIComponent(pair[1]));
 //            }
 //        }
 //        return query_string;
 //    }();

    $(document).on('click', '.btn-remove-images', function(){
    	var $this = $(this),
		    $form_preview_images = $this.parents('.form-preview-images'),
			$right_action = $form_preview_images.find('.right-action'),
			$remove_images = $form_preview_images.find('input.remove_images'),
			$file_images = $form_preview_images.find('input[name="file_images"]'),
			$text_images = $form_preview_images.find('input[name="text_images"]'),
			$return_preview_images = $form_preview_images.find('.return-preview-images');

		$return_preview_images.attr('src', $remove_images.data('images-default'));
		$file_images.val('');
		$text_images.val('');
		$remove_images.val('1');
		$right_action.fadeOut(300);
    });

    $(document).on('click', '.btn-go-back', function(){
		if (history.length > 1){
		    window.history.back();
		} else {
			window.location.href = $(this).data('href');
		}
    });

  //   $(document).on('click', '.btn-newsletter-save-draft', function(){
  //   	$thisAction = $(this).parent().find('input[name="thisAction"]');
		// $thisAction.val('broadcast_save_draft');
  //   });

  //   $(document).on('change', 'select[name="newsletter_layout"]', function(e){
  //       e.preventDefault();
  //       var $this = $(this);
  //       var id = $this.find(':selected').val();
  //       var description = $(this).parents('form').find('.summernote-newsletter');
  //       $.ajax({
		// 	type: 'GET',
		// 	url: './func/newsletter.php',
		// 	data: 'thisId='+id+'&thisAction=get_layout',
		// 	async: false,
		// 	cache: false,
		// 	dataType: 'json',
		// 	success: function(data){
		// 		description.code(data.content);
		// 	},
		// 	error: function(jqXHR){
		// 		var response = jqXHR.responseText;
		// 		alert(response);
		// 	}
		// });
  //   });

    if($('textarea[maxlength]').length > 0 ){
		$('textarea[maxlength]').each(function() {
			"use strict";
			var maxLL = $(this).attr("maxlength");
			$(this).after('<p class="help-block"><em><span>'+ maxLL + '</span> Character.</em></p>');
		});
	    $('textarea[maxlength]').bind("keyup change", function(){
			maxLL = $(this).attr("maxlength");
			currentLengthInTextarea = $(this).val().length;
			$(this).parent().find('.help-block span').text(parseInt(maxLL) - parseInt(currentLengthInTextarea));

			if (currentLengthInTextarea > (maxLL)) {
				$(this).val($(this).val().slice(0, maxLength));
				$(this).parent().find('.help-block span').text(0);
			}
		});
    }

});

function reconstructIsotope(){
	if ($('.isotope').length > 0){
		$('.isotope').isotope({
			animationOptions: {
				duration: 200,
				easing	: 'linear',
				queue	: true,
			}
		});
	}
}

function notifyMessage($info, $type){
	var $message = '<div class="alert alert-'+$type+' fade in alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p><strong>Message!</strong></p><p>'+$info+'</p></div>';
	var alert_top = document.getElementById('alert-top');

	var xxx = setTimeout(function(){
		$('#alert-top').fadeOut(600);
		setTimeout('$("#alert-top").html("")',600);
        var href = window.location.href;
		if(href.indexOf("msg") > -1) {
		    history.pushState(null, null, href.replace(/msg=.*(&?)/, '$1'));
		} else if(href.indexOf("err") > -1) {
		    history.pushState(null, null, href.replace(/err=.*(&?)/, '$1'));
		}
	},4000);

	if(alert_top){
		$('#alert-top').html($message);
		if($('#alert-top').css('display','block')){
			clearTimeout(xxx);
			$('#alert-top').fadeOut(10);
			var xxx = setTimeout(function(){
				$('#alert-top').fadeOut(600);
				setTimeout('$("#alert-top").html("")',600);
		        var href = window.location.href;
				if(href.indexOf("msg") > -1) {
				    history.pushState(null, null, href.replace(/msg=.*(&?)/, '$1'));
				} else if(href.indexOf("err") > -1) {
				    history.pushState(null, null, href.replace(/err=.*(&?)/, '$1'));
				}
			},4000);
		}
		$('#alert-top').fadeIn(600);
	} else {
		$('body').append('<div id="alert-top"></div>');
		$('#alert-top').html($message).fadeIn(600);
	}
}

// function reloadImages(id,goPost){
// 	$('#portfolio').addClass('loading').html('');
// 	$.ajax({
// 		type: 'GET',
// 		url: './func/'+goPost+'.php',
// 		data: 'thisId='+id+'&thisAction=reloadImages',
// 		async: false,
// 		cache: false,
// 		dataType: 'json',
// 		success: function(data){
// 			$('#portfolio').html(data.content);

// 			setTimeout("reconstructIsotope();", 500 );
// 			if ($('.magnific-popup-wrap').length > 0){
// 				$('.magnific-popup-wrap').each(function() {
// 					"use strict";
// 					$(this).magnificPopup({
// 						delegate: 'a.zooming',
// 						type: 'image',
// 						removalDelay: 300,
// 						mainClass: 'mfp-fade',
// 						gallery: {
// 						  enabled:true
// 						}
// 					});
// 				});
// 			}
// 		},
// 		error: function(jqXHR){
// 			var response = jqXHR.responseText;
// 			alert(response);
// 		}
// 	});

// 	$('#portfolio').removeClass('loading');
// }

function previewImages(input) {
	var thisParent  = $('input[name="'+input.name+'"]').parents('.form-preview-images');
	var thisReturn  = thisParent.find('img.return-preview-images');
	var thisReturnA = thisParent.find('a.return-preview-images');
	var thisReturnR = thisParent.find('input.remove_images').val('');
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			if(!thisReturn.data('old-src')){
				thisReturn.data('old-src', thisReturn.attr('src'));
				thisReturnA.attr('href', thisReturn.attr('src'));
			}
			thisReturn.attr('src', e.target.result);
			thisReturnA.attr('href', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	} else {
		if(thisReturn.data('old-src')){
			thisReturn.attr('src', thisReturn.data('old-src'));
			thisReturnA.attr('href', thisReturn.attr('old-src'));
		}
	}
}

function goBack(button) {
    window.history.back();
}

function changeStatus(action,url) {
	if(url!=''){
		$.ajax({
	        type: 'GET',
	        url: url+"/"+action.checked,
	        // data: {'thisVal':action.checked,'thisAction':'update'},
	        async: false,
	        cache: false,
	        dataType: 'json',
	        success: function(data){
            	notifyMessage('Update status success', 'success');
	        },
	        error: function(jqXHR){
	            var response = jqXHR.responseText;
            	notifyMessage('Update status failed', 'danger');
	        }
	    });

		if(action.checked == true){ $val = 1; } else { $val = 0; }
		$('#'+action.id).parent().find('.onoffswitch-inner > span').html($val);
	}
}
