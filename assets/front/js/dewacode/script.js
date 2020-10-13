var $ = jQuery.noConflict();

// $(window).load(function() {

// });

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

    $(".ajax_send_verification").on('click', function(){
        $.ajax({
            type: 'POST',
            url: MOD_URL+'account-send-verification',
            data: {'thisAction':'send'},
            async: false,
            cache: false,
            dataType: 'json',
            beforeSend: function(){

            },
            success: function(data){
                alert(data.msg);
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                console.log(jqXHR);
                alert('error ajax');
            }
        });
    });

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

    if($('.single-product-name').length) {
        $('.single-product-name').each(function(){
            var $this = $(this);
            var awal = $this.width();
            var akhir = $this.find('a').width();
            var selisih = parseInt(akhir) - parseInt(awal);
            var times = 1000;
            if(selisih > 0){
                if(selisih > 50){ times = 5000; }
                else if(selisih > 35){ times = 3500; }
                else if(selisih > 20){ times = 2500; }

                var right = function () {
                    $this.delay(1000).animate({'margin-left': '-'+selisih+'px'}, times, left);
                }
                var left = function () {
                    $this.delay(1000).animate({'margin-left': '0px'}, times, right);
                }
                right();
            }
        });
    }

    $(document).on('submit', '.form-newsletter', function(e){
        e.preventDefault();
        var $this   = $(this);
        var form    = $this;

        swalShowLoading();
        setTimeout(function(){
            $.ajax({
                type: 'POST',
                url: MOD_URL+"ajax-function/subscribe/",
                data: form.serialize(),
                async: false,
                cache: false,
                dataType: 'json',
                beforeSend: function(){

                },
                success: function(data){
                    if(data.err == false){
                        swalSuccess(data.msg);
                        form[0].reset();
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
        }, 400);
    });

    $(document).on('submit', '.form-contact', function(e){
        e.preventDefault();
        var $this   = $(this);
        var form    = $this;

        swalShowLoading();
        setTimeout(function(){
            $.ajax({
                type: 'POST',
                url: MOD_URL+"ajax-function/sendcontact/",
                data: form.serialize(),
                async: false,
                cache: false,
                dataType: 'json',
                beforeSend: function(){

                },
                success: function(data){
                    if(data.err == false){
                        swalSuccess(data.msg);
                        form[0].reset();
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
        }, 400);
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
