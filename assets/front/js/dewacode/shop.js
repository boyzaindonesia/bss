var $ = jQuery.noConflict();

$(document).ready(function() {
    $(document).on('click', '.quantity-btn', js_get_price_grosir );
    $(document).on('keyup change', '.cart-qty', js_get_price_grosir );

    $(document).on('click', '.cart-add-btn', function(e){
        e.preventDefault();
        swal({
            title: "Loading!",
            text: "",
            type: "loading",
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: 'swal2-small'
        });
        var $this   = $(this);
        var form    = $this.parents('form');
        var countChecked = form.find('input[type=checkbox].form-cart-checkbox:checked').length;
        if(countChecked == 0){
            swal({
                title: "Error!",
                text: "Ceklis warna dan isi jumlahnya.",
                type: "error"
            });
        } else {
            form.submit();
        }
    });

    $(document).on('submit', 'form.form-cart', addToCart );
    function addToCart(e){
        if (typeof e !== 'undefined') e.preventDefault();
        var $this = $(this);
        var form = $this;

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
                if(data.cart_err == false){

                    swal({
                        title: "Success!",
                        text: "Berhasil menambahkan ke keranjang!",
                        type: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(
                    function () {},
                    function (dismiss) {
                        // console.log('close');
                        // if (dismiss === 'timer') {
                        //     console.log('I was closed by the timer')
                        // }

                        setTimeout(function(){
                            $('#productModal button.close').trigger('click');
                        },300);
                    });

                    js_cart_load();

                } else {
                    var data_msg = '';
                    if(data.cart_msg != ''){
                        $.each( data.cart_msg, function( key, value ) {
                            data_msg += '<li>'+value+'</li>';
                        });
                    }
                    $this.find('.input-group').removeClass('has-error');
                    if(data.cart_err_stock == true){
                        $.each( data.cart_msg_stock, function( key, value ) {
                            // $this.find('input[name="cart-qty['+value.id+']"]').val(value.qty);
                            $this.find('input[name="cart-qty['+value.id+']"]').parents('.input-group').addClass('has-error');
                            data_msg += '<li>'+value.msg+'</li>';
                        });
                    }

                    swal({
                        title: "Error!",
                        html: "<ul>"+data_msg+"</ul>",
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

        return false;
    }

});

function js_get_price_grosir(e){
    if (typeof e !== 'undefined') e.preventDefault();
    var $this           = $(this);
    var cart_item       = $this.parents('.cart-item');
    var cart_id         = cart_item.attr('data-id');
    var cart_qty        = cart_item.find('.cart-qty').val();
    if(cart_qty == 'null' || cart_qty == null){ cart_qty = 0; }

    var temp_cart_price        = 0;
    var cart_grosir_price_span = cart_item.find('.data-grosir-price').html();
    if(cart_grosir_price_span != ''){
        var json = $.parseJSON(cart_grosir_price_span);
        $(json).each(function(i,val){
            if(parseInt(val.qty) <= parseInt(cart_qty)){
                temp_cart_price = val.price;
            }
        });
        if(temp_cart_price == 0){
            temp_cart_price = cart_item.find('.data-normal-price').html();
        }
        cart_item.find('.cart-price-span').html(convertRp(temp_cart_price));
    }
}

/*-------------------------------------------
    Modal Buy To Marketplace
-------------------------------------------- */
$(document).on('click touchstart', '.btn-popup-buy-marketplace:not(.load)', function(e){
    e.preventDefault();
    var $this  = $(this);
    $this.addClass('load');
    var productModal  = $('#productModal'),
        productModalContent = productModal.find('.modal-product');
    var isi = $('#msg-marketplace').html();
        productModalContent.html(isi);
        $this.removeClass('load');
});

/*-------------------------------------------
    Modal Product
-------------------------------------------- */
$(document).on('click touchstart', '.btn-popup-product:not(.load)', function(e){
    e.preventDefault();
    var $this  = $(this);
    var id    = $this.attr('data-id');
    var name  = $this.attr('data-name');
    var image = $this.attr('data-image');

    getDefaultProductModal();
    swalShowLoading();
    $this.addClass('load');

    var productModal  = $('#productModal'),
        productModalContent = productModal.find('.modal-product'),
        productImages = productModal.find('.product-images'),
        productName   = productModal.find('.product-name');

    productName.html(name);
    productImages.find('img').attr('src',image);

    $.ajax({
        type: 'POST',
        url: MOD_URL+'ajax-product-modal',
        data: {'thisVal':id,'thisAction':'get_product'},
        async: false,
        cache: false,
        dataType: 'json',
        success: function(data){
            productModalContent.html(data.content);
            swalHideLoading();
            $this.removeClass('load');

            if($('#product-slider-modal').length) {
                $('#product-slider-modal').lightSlider({
                    gallery:true,
                    item:1,
                    loop:false,
                    thumbItem:4,
                    auto: false,
                    slideMargin:0,
                    enableDrag: false,
                    controls: true,
                    currentPagerPosition:'left',
                    onSliderLoad: function(el) {
                        el.lightGallery({
                            selector: '#product-slider-modal .hoverIcons .eye',
                            enableDrag: false
                        });
                    }
                });
            }
        },
        error: function(jqXHR){
            var response = jqXHR.responseText;
            console.log(response);
            setTimeout(function(){
                $this.removeClass('load');
            }, 500);
        }
    });
});

function getDefaultProductModal(){
    var layout = '<form action="#" method="post" enctype="multipart/form-data">';
            layout += '<div class="product-images">';
                layout += '<div class="main-image images">';
                    layout += '<ul class="modal-images-slider">';
                        layout += '<li><img src="'+MOD_URL+'assets/collections/images/loading-product.jpg" alt=""></li>';
                    layout += '</ul>';
                layout += '</div>';
            layout += '</div>';
            layout += '<div class="product-info">';
                layout += '<h1 class="product-name"></h1>';
            layout += '</div>';
        layout += '</form>';

    $('#productModal .modal-product').html(layout);
}
