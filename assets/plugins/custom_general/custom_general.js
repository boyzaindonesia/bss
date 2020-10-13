var $ = jQuery.noConflict();

var customGeneral = function(){
    // this.autoScroll = function(className, offset) {
    //     if($(className).length != 0) {
    //         $('html, body').animate({
    //             scrollTop: $(className).offset().top + offset
    //         }, 1000);
    //     }
    // };

    this.autoCompleteMember = function (array, id) {
        var xhr;
        $(id).autocomplete({
            source: function(request, response) {
                var regex = new RegExp(request.term, "i");
                if(xhr){ xhr.abort(); }
                xhr = $.ajax({
                    type: 'POST',
                    url: MOD_URL+'ajax-function/autocomplete_member',
                    data: {'thisVal':request.term, 'thisAction':'check'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        // response(data);
                        response($.map(data.result, function (item) {
                            return {
                                label: item.name,
                                value: item
                            };
                        }));
                    }
                });
            },
            autoFocus: true,
            scroll: true,
            minLength: 1,
            delay: 800,
            appendTo: $(id+'_feedback'),
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                    ul.addClass('ui-autocomplete-feedback-member');

                var layout_item = '';
                    layout_item += '<div class="feedback">';
                        layout_item += '<span class="name">'+item.value.name+'</span><span> ('+item.value.phone+') </span><br>';
                        layout_item += '<span><small>'+item.value.address+'</small></span><br>';
                        layout_item += '<span><small>Kota '+item.value.city_name+' - '+item.value.province_name+' '+item.value.postal_code+'</small></span>';
                    layout_item += '</div>';

                    return $('<li>')
                    .append(layout_item)
                    .appendTo(ul);
                };
            },
            select: function( event, ui ) {
                event.preventDefault();
                // $(this).val(ui.item.value.name);
                // console.log(ui.item.value.name);

                $('[data-autocomplete-name]').val(ui.item.value.name);
                $('[data-autocomplete-email]').val(ui.item.value.email);
                $('[data-autocomplete-phone]').val(ui.item.value.phone);
                $('[data-autocomplete-address]').val(ui.item.value.address);
                $('[data-autocomplete-city]').val(ui.item.value.city).trigger("chosen:updated");
                $('[data-autocomplete-province]').val(ui.item.value.province);
                $('[data-autocomplete-province-name]').val(ui.item.value.province_name);
                // get_province_city(ui.item.value.city);
                // $('[data-autocomplete-city]').val(ui.item.value.city).attr('data-id',ui.item.value.city);
                // $('[data-autocomplete-city]').attr('data-id',ui.item.value.city);
                $('[data-autocomplete-postal-code]').val(ui.item.value.postal_code);
            },
            open: function() {
                $('[data-autocomplete-email]').val('');
                $('[data-autocomplete-phone]').val('');
                $('[data-autocomplete-address]').val('');
                $('[data-autocomplete-city]').val('').trigger("chosen:updated");
                $('[data-autocomplete-province]').val('');
                $('[data-autocomplete-province-name]').val('');
                // $('[data-autocomplete-city]').val('').attr('data-id','');
                // $('[data-autocomplete-city]').html('<option value="" selected>--- Pilih ---</option>');
                $('[data-autocomplete-postal-code]').val('');

                // $(this).data('ui-autocomplete').addClass( "ui-corner-top" );
            },
            close: function() {
                // $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        }).focus(function() {
            if($(this).val() == '') {
                $(this).autocomplete("search", "");
            }else{
                $(this).autocomplete("search", $(this).val());
            }
        });
    };

    this.autoCompletePrintAddress = function (array, id) {
        var xhr;
        $(id).autocomplete({
            source: function(request, response) {
                var regex = new RegExp(request.term, "i");
                if(xhr){ xhr.abort(); }
                xhr = $.ajax({
                    type: 'POST',
                    url: MOD_URL+'ajax-function/autocomplete_print_address',
                    data: {'thisVal':request.term, 'thisAction':'check'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        // response(data);
                        response($.map(data.result, function (item) {
                            return {
                                label: item.name,
                                value: item
                            };
                        }));
                    }
                });
            },
            autoFocus: true,
            scroll: true,
            minLength: 1,
            delay: 800,
            appendTo: $(id+'_feedback'),
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                    ul.addClass('ui-autocomplete-feedback-member');

                var layout_item = '';
                    layout_item += '<div class="feedback">';
                        layout_item += '<span class="name">'+item.value.name+'</span><span> ('+item.value.phone+') </span><br>';
                        layout_item += '<span><small>'+item.value.address+'</small></span><br>';
                        layout_item += '<span><small>Kota '+item.value.city_name+' - '+item.value.province_name+' '+item.value.postal_code+'</small></span>';
                    layout_item += '</div>';

                    return $('<li>')
                    .append(layout_item)
                    .appendTo(ul);
                };
            },
            select: function( event, ui ) {
                event.preventDefault();
                // $(this).val(ui.item.value.name);
                // console.log(ui.item.value.name);

                $('[data-autocomplete-name]').val(ui.item.value.name);
                $('[data-autocomplete-email]').val(ui.item.value.email);
                $('[data-autocomplete-phone]').val(ui.item.value.phone);
                $('[data-autocomplete-address]').val(ui.item.value.address);
                $('[data-autocomplete-city]').val(ui.item.value.city).trigger("chosen:updated");
                $('[data-autocomplete-province]').val(ui.item.value.province);
                $('[data-autocomplete-province-name]').val(ui.item.value.province_name);
                // get_province_city(ui.item.value.city);
                // $('[data-autocomplete-city]').val(ui.item.value.city).attr('data-id',ui.item.value.city);
                // $('[data-autocomplete-city]').attr('data-id',ui.item.value.city);
                $('[data-autocomplete-postal-code]').val(ui.item.value.postal_code);
            },
            open: function() {
                $('[data-autocomplete-email]').val('');
                $('[data-autocomplete-phone]').val('');
                $('[data-autocomplete-address]').val('');
                $('[data-autocomplete-city]').val('').trigger("chosen:updated");
                $('[data-autocomplete-province]').val('');
                $('[data-autocomplete-province-name]').val('');
                // $('[data-autocomplete-city]').val('').attr('data-id','');
                // $('[data-autocomplete-city]').html('<option value="" selected>--- Pilih ---</option>');
                $('[data-autocomplete-postal-code]').val('');

                // $(this).data('ui-autocomplete').addClass( "ui-corner-top" );
            },
            close: function() {
                // $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        }).focus(function() {
            if($(this).val() == '') {
                $(this).autocomplete("search", "");
            }else{
                $(this).autocomplete("search", $(this).val());
            }
        });
    };

    this.getProductCode = function (params) {
         var params   = {
                'thisAction'            : 'getdata'
            };
        var ProductCodeArray = [];
        $.ajax({
            type: 'POST',
            url: MOD_URL+'ajax-function/get_product_code',
            data: params,
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
                $.each(data.result, function(i, item) {
                    ProductCodeArray.push(item);
                });
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                alert('error ajax');
            }
        });
        return ProductCodeArray;
    };

    this.getProduct = function (params) {
            // var params   = {
            //     'product_status'        : '1',
            //     'product_approved'      : '1',
            //     'product_status_id'     : '!= 3',
            //     'thisAction'            : 'getdata'
            // };
        var ProductArray = [];
        $.ajax({
            type: 'POST',
            url: MOD_URL+'ajax-function/autocomplete_product',
            data: params,
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
                $.each(data.product_thumb, function(i, item) {
                    ProductArray.push(item);
                });
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                alert('error ajax');
            }
        });
        return ProductArray;
    };

    this.autoCompleteProduct = function (array, id, minLength = 1) {
        $(id).autocomplete({
            source: array,
            autoFocus: true,
            scroll: true,
            minLength: minLength,
            delay: 500,
            appendTo: $(id+'_feedback'),
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                    ul.addClass('ui-autocomplete-feedback-product');

                    var product_price = convertRp(item.price_sale);
                    if(item.price_discount != '0'){
                        product_price = '<span class="price-discount">'+convertRp(item.price_sale)+'</span><span class="text-danger">'+convertRP(item.price_discount)+'</span>';
                    }

                    var btn_action = '<div class="btn btn-danger btn-xs">Beli</div>';
                    if(item.status_id != '1'){
                        btn_action = '<div class="btn btn-primary btn-xs">'+item.status_name+'</div>';
                    }

                    var layout_item = '';
                        layout_item += '<div class="feedback btn-popup-product" data-id="'+item.id+'" data-href="'+item.href+'">';
                            layout_item += '<div class="feedback-image" style="background-image:url('+item.image+'"></div>';
                            layout_item += '<div class="feedback-desc">';
                                layout_item += '<h5 class="mt-5 mb-0">'+item.label+'</h5>';
                                layout_item += '<p class="mb-0"><small>'+item.root_category+'</small></p>';
                                layout_item += '<p class="mb-5">'+product_price+'</p>';
                                layout_item += btn_action;
                            layout_item += '</div>';
                        layout_item += '</div>';

                    return $('<li>')
                    .append(layout_item)
                    .appendTo(ul);
                };
            },
            select: function( event, ui ) {
                event.preventDefault();
                $(this).blur();
            }
        }).focus(function() {
            $(this).autocomplete("search", $(this).val());
            // if($(this).val() == '') {
            //     $(this).autocomplete("search", "");
            // }else{
            //     $(this).autocomplete("search", $(this).val());
            // }
        });
    };

    this.autoCompleteProductFront = function (id) {
        var xhr;
        $(id).autocomplete({
            source: function(request, response) {
                var regex = new RegExp(request.term, "i");
                if(xhr){ xhr.abort(); }
                xhr = $.ajax({
                    type: 'POST',
                    url: MOD_URL+'ajax-function/autocomplete_product',
                    data: {'keyword':request.term, 'thisAction':'getdata'},
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(data) {
                        response(data.product_thumb);
                    }
                });
            },
            autoFocus: true,
            scroll: true,
            minLength: 1,
            delay: 800,
            appendTo: $(id+'_feedback'),
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                    ul.addClass('ui-autocomplete-feedback-product');

                    var product_price = convertRp(item.price_sale);
                    if(item.price_discount != '0'){
                        product_price = '<span class="price-discount">'+convertRp(item.price_sale)+'</span><span class="text-danger">'+convertRP(item.price_discount)+'</span>';
                    }

                    var layout_item = '';
                        layout_item += '<a class="feedback" href="'+item.href_front+'">';
                            layout_item += '<div class="feedback-image" style="background-image:url('+item.image+'"></div>';
                            layout_item += '<div class="feedback-desc">';
                                layout_item += '<h4 class="mt-0 mb-0">'+item.label+'</h4>';
                                layout_item += '<h5 class="mb-0">'+item.category+'</h5>';
                                layout_item += '<p class="mb-0"><small>'+product_price+'</small></p>';
                            layout_item += '</div>';
                        layout_item += '</a>';

                    return $('<li>')
                    .append(layout_item)
                    .appendTo(ul);
                };
            }
        }).focus(function() {
            if($(this).val() == '') {
                $(this).autocomplete("search", "");
            }else{
                $(this).autocomplete("search", $(this).val());
            }
        });
    };

    this.autoCompleteCity = function (array, id) {
        $(id).autocomplete({
            source: array,
            autoFocus: true,
            scroll: true,
            minLength: 0,
            appendTo: $(id+'_feedback'),
            create: function () {
                $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                    var layout_item = '';
                        layout_item += '<div class="feedback">';
                            layout_item += '<span class="name">'+item.city_title+'</span><span> - '+item.province_title+'</span>';
                        layout_item += '</div>';

                    return $('<li>')
                    .append(layout_item)
                    .appendTo(ul);
                };
            },
            select: function( event, ui ) {
                event.preventDefault();
                $(this).val(ui.item.city_title);

                $('[data-autocomplete-province-2]').val(ui.item.province_id);
                $('[data-autocomplete-province-name-2]').val(ui.item.province_title);
            },
            open: function() {
                $('[data-autocomplete-province-2]').val('');
                $('[data-autocomplete-province-name-2]').val('');
            }
        }).focus(function() {
            if($(this).val() == '') {
                $(this).autocomplete("search", "");
            }else{
                $(this).autocomplete("search", $(this).val());
            }
        });
    };

    // this.autoCompleteNoDropdown = function (array, id) {
    //     $(id).autocomplete({
    //         source: array,
    //         autoFocus: true,
    //         scroll: true,
    //         minLength: 0,
    //         change: function (ev, ui) {
    //             if (!ui.item) {
    //                 var found = false;
    //                 jQuery.each(array, function(i,v) {
    //                     if($(id).val() == v.toLowerCase()) {
    //                         found = true;
    //                         $(id).val(v);
    //                         return false;
    //                     }
    //                 });
    //             }
    //         }
    //     });
    // };

    // this.postAutoComplete = function (array, id) {
    //     var postIdElementName = id + '_id';
    //     $(id).autocomplete({
    //         source: array,
    //         autoFocus: true,
    //         select: function (ev, ui) {
    //             if(ui.item){
    //                 $(postIdElementName).val(ui.item.id);
    //             }
    //         },
    //         change: function (ev, ui) {
    //             if (!ui.item) {
    //                 var found = false;
    //                 jQuery.each(array, function(i,v) {
    //                     if($(id).val().toLowerCase() == v.value.toLowerCase()) {
    //                         found = true;
    //                         $(id).val(v.value);
    //                         $(postIdElementName).val(v.id);
    //                         return false;
    //                     }
    //                 });

    //                 if(!found){
    //                     $(this).val('');
    //                 }
    //             }
    //         }
    //     });
    // };
}


$(window).load(function($) {

});

$(document).ready(function($) {
    var $winWidth = $(window).width();
    var $winHeight = $(window).height();

    $(document).on('search', 'input[type=search]', function(e){
        e.preventDefault();
        var $this = $(this);
        if(($this.val() == "") && ($this.attr('data-reset') == "true")) {
            $this.parents('form').find('input[name="searchAction"]').val('reset');
        }
        $this.parents('form').submit();
    });

});


function convertRp($angka='0'){
    var $return = "Rp 0";
    if($angka!='0'){
        $return = "Rp"+" "+($angka +"").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
    }
    return $return;
}
function convertRpToInt($angka='0'){
    var $return = '0';
    if($angka!='0'){
        $return = ($angka).split('.').join('');
    }
    return $return;
}
function convertGrToKg($angka='0'){
    var $return = '';
    if($angka!='0'){
        $return = ($angka / 1000);
    }
    return $return;
}
function convertGrToKgCeil($angka='0'){
    var $return = '';
    if($angka!='0'){
        $return = Math.ceil($angka / 1000);
    }
    return $return;
}

function swalShowLoading(){
    swal({
        title: "Loading!",
        text: "",
        type: "loading",
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: 'swal2-small'
    });
}
function swalHideLoading(){
    swal({
        title: "Loading!",
        text: "",
        type: "loading",
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: 'swal2-small',
        timer: 1000
    }).then(
        function () {},
        function (dismiss) {
            if (dismiss === 'timer') {
                // $this.removeClass('load');
            }
        }
    );
}
function swalSuccess($msg=''){
    swal({
        title: "Success!",
        text: $msg,
        type: "success",
        showConfirmButton: false,
        timer: 1500
    }).then(
    function () {},
    function (dismiss) {

    });
}

/* ---------------------------------------------------------------------- */
/*  Validate number only
/* ---------------------------------------------------------------------- */
// <input type="text" onkeypress ="validate(event);" />
// function validate(evt) {
//  var theEvent = evt || window.event;
//  var key = theEvent.keyCode || theEvent.which;
//  key = String.fromCharCode( key );
//  var regex = /[0-9]|\./;
//  if( !regex.test(key) ) {
//      theEvent.returnValue = false;
//      if(theEvent.preventDefault) theEvent.preventDefault();
//  }
// }
function validateUsername(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[a-z0-9]/;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}
// function validatePrice(evt) {
//  var theEvent = evt || window.event;
//  var key = theEvent.keyCode || theEvent.which;
//  key = String.fromCharCode( key );
//  var regex = /[0-9]|\./;
//  if( !regex.test(key) ) {
//      theEvent.returnValue = false;
//      if(theEvent.preventDefault) theEvent.preventDefault();
//  }
// }
// function validatePhone(evt) {
//  var theEvent = evt || window.event;
//  var key = theEvent.keyCode || theEvent.which;
//  key = String.fromCharCode( key );
//  var regex = /[0-9]|\-/;
//  if( !regex.test(key) ) {
//      theEvent.returnValue = false;
//      if(theEvent.preventDefault) theEvent.preventDefault();
//  }
// }
// function validateDate(evt) {
//  var theEvent = evt || window.event;
//  var key = theEvent.keyCode || theEvent.which;
//  key = String.fromCharCode( key );
//  var regex = /[0-9]|\//;
//  if( !regex.test(key) ) {
//      theEvent.returnValue = false;
//      if(theEvent.preventDefault) theEvent.preventDefault();
//  }
// }

