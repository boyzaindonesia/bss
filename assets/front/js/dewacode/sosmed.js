var $ = jQuery.noConflict();

$(window).load(function($) {
    
});

$(document).ready(function($) {
    
    /** ADD LIKE OR WISHLIST **/
    $(document).on('click touchstart', '.btn-add-like:not(.load)', function(e){
        e.preventDefault();
        var $this  = $(this);
        var id    = $this.attr('data-like-id');
        var type  = $this.attr('data-like-type');
        var remove  = $this.attr('data-like-remove');
        var loading = $this.find('.small-ajax-loader');
        var icon = $this.find('.fa');
        icon.hide();
        loading.removeClass('hide').show();
        $this.addClass('load');

        if(remove==''||remove==undefined||remove=='undefined'){ remove=''; }

        $.ajax({
            type: 'POST',
            url: MOD_URL+'ajax-like',
            data: {'thisId':id,'thisType':type,'thisAction':'save'},
            async: false,
            cache: false,
            dataType: 'json',
            success: function(data){
                if(data.err==true){
                    window.location.href = data.action;
                } else {
                    if(remove!=''){ $this.parents(remove).remove(); }

                    if(data.action=='add'){
                        $this.addClass('active').attr('data-original-title',data.msg);
                        $('.btn-add-like[data-like-id='+id+'][data-like-type='+type+']').addClass('active').attr('data-original-title',data.msg);
                    } else {
                        $this.removeClass('active').attr('data-original-title',data.msg);
                        $('.btn-add-like[data-like-id='+id+'][data-like-type='+type+']').removeClass('active').attr('data-original-title',data.msg);
                    }
                    if(type=='like'){
                        if(data.action=='add'){
                            $('.result-count-like[data-like-id='+id+']').addClass('active');
                            $('.result-count-like[data-like-id='+id+'] span').html('You and '+data.count);
                        } else {
                            $('.result-count-like[data-like-id='+id+']').removeClass('active');
                            $('.result-count-like[data-like-id='+id+'] span').html(data.count);
                        }
                    }
                    setTimeout(function(){
                        loading.hide();
                        icon.show();
                        $this.removeClass('load');
                    }, 500);
                }
            },
            error: function(jqXHR){
                var response = jqXHR.responseText;
                console.log(response);
                setTimeout(function(){
                    loading.hide();
                    icon.show();
                    $this.removeClass('load');
                }, 500);
            }
        });
        // $wishlist = get_check_like($r->product_id,'member_wishlist');
        // <span class="label-wish btn-add-like <?php echo ($wishlist?'active':'') ?>" data-toggle="tooltip" data-original-title="<?php echo ($wishlist?'Sudah dalam Wishlist':'Tambah ke Wishlist') ?>" data-like-id="<?php echo $r->product_id ?>" data-like-type="wishlist" data-like-remove="tr"><i class="small-ajax-loader hide"></i><i class="fa fa-heart"></i></span>
    });
 
});
