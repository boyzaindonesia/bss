<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/socialshare/SocialShareSimple.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $('.share').each(function(){
            $(this).ShareLink({
                title: $(this).data('share-title'),
                text: $(this).data('share-text'),
                image: $(this).data('share-image'),
                url: $(this).data('share-url'),
                hashtags: $(this).data('share-hashtags'),
                via: $(this).data('share-via')
            });
        });

        $('.counter').each(function(){
            $(this).ShareCounter({
                url: 'http://google.com/',
                increment: true
            });
        });

    });
</script>


<!-- CALLBACK -->
<script type="text/javascript">
// 	window.fbAsyncInit = function() {
//         FB.init({
//             appId      : '279160979103201',
//             xfbml      : true,
//             version    : 'v2.6'
//         });
//     };

//     (function(d, s, id){
//         var js, fjs = d.getElementsByTagName(s)[0];
//         if (d.getElementById(id)) {return;}
//         js = d.createElement(s); js.id = id;
//         js.src = "//connect.facebook.net/en_US/sdk.js";
//         fjs.parentNode.insertBefore(js, fjs);
//     }(document, 'script', 'facebook-jssdk'));

// 	$(document).on('click touchstart', '.fb-share-button.type-2', function(e){
//         e.preventDefault();

//         var $this = $(this),
//             url = $this.data('url'),
//             title = $this.data('title'),
//             image = $this.data('image'),
//             desc = $this.data('desc');

//         FB.ui({
//             method: 'share',
//             mobile_iframe: false,
//             display: 'popup',
//             name: title,
//             // type: 'article',
//             title: title,
//             description: desc,
//             picture: image,
//             caption: 'www.aeropolis.com',
//             hashtag: '#aeropolis',
//             href: url
//         }, function(response){
//             if (response && !response.error_message) {
//                 // alert('Terima kasih telah berbagi di sosial media Anda!');
//             } else {
//                 // alert('Canceled for share.');
//             }
//         });
//     });
</script>