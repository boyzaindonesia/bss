<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] 			= "front/site";
$route['admin'] 						= "auth";
$route['cms'] 							= "auth";
$route['cms/(:any)']  					= "auth/$1";
$route['cms/(:any)/(:num)'] 			= "auth/$1/$2";

// $route['404_override'] 					= "not_found/not_found";
$route['404_override'] 					= "others";

$route['ajax-function/(:any)'] 			= "ajax_function/$1";

// JIKA AMBIL DATANYA DARI MT_MENUS, MAKA DI HIDE
// $route['about'] 						= "front/site/about";
// $route['story'] 						= "front/site/story";
// $route['developer'] 					= "front/site/developer";
// $route['investor'] 						= "front/site/investor";
// $route['business'] 						= "front/site/business";
// $route['sustainability'] 				= "front/site/sustainability";
// $route['project'] 						= "front/site/project";
// $route['contact'] 						= "front/site/contact";
// $route['term-of-use'] 					= "front/site/pages/2";
// $route['privacy-policy'] 				= "front/site/pages/3";

$route['messages/(:any)']  				= 'front/site/messages/$1';
$route['faq']  							= 'front/site/faq';
$route['how-to-buy']                    = 'front/site/how_to_buy';

$route['home'] 							= "front/site/home";

$route['test-email'] 					= "front/site/test_email";
$route['send_message'] 					= "front/site/send_message";
$route['notify-me'] 					= "front/site/notify_me";

$route['news']  						= "front/site/news";
$route['news/(:any)']  					= "front/site/news/$1";
$route['news/(:num)/(:num)']  			= "front/site/news/$1/$2";
$route['load_more_news'] 				= "front/site/load_more_news";

$route['gallery']  						= "front/gallery";
$route['gallery/(:any)']  				= "front/gallery/$1";
$route['gallery/(:num)/(:num)']  		= "front/gallery/$1/$2";
$route['load_more_gallery'] 			= "front/gallery/load_more_gallery";

$route['gallery-images'] 				= "front/gallery/gallery_images";
$route['gallery-images/(:num)']  		= 'front/gallery/gallery_images/$1';
$route['gallery-images/(:any)']  		= 'front/gallery/gallery_images/$1';
$route['gallery-images/(:num)/(:any)']  = 'front/gallery/gallery_images/$1/$2';

$route['membership'] 					= "front/site/membership";

$route['confirmation'] 					= "front/site/confirmation";
$route['confirmation/(:any)']  			= 'front/site/confirmation/$1';
$route['confirmation/(:num)/(:num)']  	= 'front/site/confirmation/$1/$2';



// $route['search'] 						= "front/site/search";
// $route['search/(:any)']  				= 'front/site/search/$1';
// $route['search/(:any)/(:num)']  		= 'front/site/search/$1/$2';

// $route['product'] 						= "front/product";
// $route['product/(:any)']  				= 'front/product/product_detail/$1';
// $route['product/(:any)/(:num)']  		= 'front/product/product_detail/$1/$2';
// $route['p']  							= 'front/product/product_detail';
// $route['p/(:any)']  					= 'front/product/product_detail/$1';
// $route['p/(:any)/(:num)']  				= 'front/product/product_detail/$1/$2';

$route['ajax-product-category'] 		= "front/product/ajax_product_category";
$route['ajax-like'] 					= "front/product/ajax_like";
$route['ajax-product'] 					= "front/product/ajax_get_product";
$route['ajax-product-modal'] 			= "front/product/ajax_get_product_modal";


$route['cart'] 							= "front/shop/cart_store";
$route['checkout'] 						= "front/shop/checkout_store";
$route['confirm-payment']               = "front/shop/confirm_payment";

$route['ajax-cart-add'] 				= "front/shop/cart_add";
$route['ajax-cart-load'] 				= "front/shop/cart_load";
$route['ajax-cart-remove'] 				= "front/shop/cart_remove";
$route['ajax-cart-empty'] 				= "front/shop/cart_empty";


$route['fb-login'] 						= "front/member/fblogin";
$route['google-login'] 					= "front/member/googlelogin";
$route['twitter-login'] 				= "front/member/twitterlogin";
$route['authorize'] 					= "front/member/authorize";
$route['login'] 						= "front/member/account_login";
$route['logout'] 						= "front/member/account_logout";
$route['register'] 						= "front/member/account_register";
$route['register/(:any)'] 				= "front/member/account_register/$1";
$route['account-verification'] 			= "front/member/account_verification";
$route['account-send-verification'] 	= "front/member/account_send_verification";
$route['reset-password'] 				= "front/member/account_reset_password";
$route['create-password'] 				= "front/member/account_create_password";
$route['account'] 						= "front/member/account";
$route['profile'] 						= "front/member/account_form";
$route['change-email'] 					= "front/member/account_change_email";
$route['change-password'] 				= "front/member/account_change_password";
$route['change-photo-profile'] 			= "front/member/change_photo_profile";
$route['check-form-member'] 			= "front/member/check_form_member";
$route['address'] 						= "front/member/account_address";
$route['address/(:any)'] 				= "front/member/account_address/$1";
$route['address/(:any)/(:num)'] 		= "front/member/account_address/$1/$2";
$route['my-promo'] 						= "front/member/account_product";
$route['my-promo/(:any)']  				= 'front/member/account_product/$1';
$route['my-promo/(:any)/(:num)']  		= 'front/member/account_product/$1/$2';
$route['wishlist'] 						= "front/member/account_wishlist";
$route['wishlist/(:num)'] 				= "front/member/account_wishlist/$1";
$route['list-orders']                   = "front/member/account_list_orders";
$route['list-orders/(:num)']            = "front/member/account_list_orders/$1";
$route['check-member-notif'] 			= "front/member/check_member_notif";

$route['print-shipping-address']  		= 'report/print_shipping_address';
$route['print-shipping-address/(:any)'] = 'report/print_shipping_address/$1';
$route['print-address']                 = 'report/print_address';
$route['print-address-again']           = 'report/print_address/print_again';
$route['print-address-booking']         = 'report/print_address/print_booking';
$route['print-address-simple']          = 'report/print_address/print_simple';
$route['print-stock']  					= 'report/print_stock';
$route['cetak-stok']                    = 'report/print_stock';
$route['print-barcode']  				= 'report/print_barcode';


$route['apps']                          = "apps/site";
$route['apps/(:any)']                   = "apps/site/$1";
$route['apps/(:any)/(:any)']            = "apps/site/$1/$2";
$route['apps-user']                     = "apps/user";
$route['apps-user/(:any)']              = "apps/user/$1";
$route['apps-user/(:any)/(:any)']       = "apps/user/$1/$2";
$route['apps-member']                   = "apps/member";
$route['apps-member/(:any)']            = "apps/member/$1";
$route['apps-member/(:any)/(:any)']     = "apps/member/$1/$2";
$route['apps-product']                  = "apps/product";
$route['apps-product/(:any)']           = "apps/product/$1";
$route['apps-product/(:any)/(:any)']    = "apps/product/$1/$2";
$route['apps-orders']                   = "apps/orders";
$route['apps-orders/(:any)']            = "apps/orders/$1";
$route['apps-orders/(:any)/(:any)']     = "apps/orders/$1/$2";
$route['apps-test']                     = "apps/tester";
$route['apps-test/(:any)']              = "apps/tester/$1";
$route['apps-test/(:any)/(:any)']       = "apps/tester/$1/$2";

// $route['apps']  						= "apps/site";
// $route['apps/(:any)']  					= "apps/site/$1";
// $route['apps/(:any)/(:any)']  			= "apps/site/$1/$2";
// $route['apps-member']  					= "apps/member";
// $route['apps-member/(:any)']  			= "apps/member/$1";
// $route['apps-member/(:any)/(:any)']  	= "apps/member/$1/$2";
// $route['apps-product']  				= "apps/product";
// $route['apps-product/(:any)']  			= "apps/product/$1";
// $route['apps-product/(:any)/(:any)']  	= "apps/product/$1/$2";
// $route['apps-orders']  					= "apps/orders";
// $route['apps-orders/(:any)']  			= "apps/orders/$1";
// $route['apps-orders/(:any)/(:any)']  	= "apps/orders/$1/$2";
// $route['apps-purchase']  				= "apps/purchase";
// $route['apps-purchase/(:any)']  		= "apps/purchase/$1";
// $route['apps-purchase/(:any)/(:any)']  	= "apps/purchase/$1/$2";
// $route['apps-statistik']  				= "apps/statistik";
// $route['apps-statistik/(:any)']  		= "apps/statistik/$1";
// $route['apps-statistik/(:any)/(:any)']  = "apps/statistik/$1/$2";

/* Location: ./application/config/routes.php */