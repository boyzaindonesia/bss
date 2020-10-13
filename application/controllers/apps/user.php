<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class User extends FrontController {
    var $cur_menu = '';

    function __construct()
    {
        parent::__construct();

        // if (isset($_SERVER['HTTP_ORIGIN'])) {
        //     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        //     header('Access-Control-Allow-Credentials: true');
        //     header('Access-Control-Max-Age: 86400');    // cache for 1 day
        // }

        // // Access-Control headers are received during OPTIONS requests
        // if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        //         header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        //     if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        //         header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        //     exit(0);
        // }

    }

    function index(){

    }

    function login(){
        // debugCode('login');

        $user_id        = '';
        $user_name      = '';
        $user_email     = '';
        $user_fullname  = '';
        $user_photo     = '';
        $user_group     = '';
        $store_id       = '';
        $store_name     = '';
        $store_phone    = '';
        $token_apps     = '';
        $error          = true;
        $msg            = '';
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request    = json_decode($_POST["params"]);
            $username   = mysql_real_escape_string($request->username);
            $password   = mysql_real_escape_string($request->password);
            $thisAction = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'login'){
                if($username != '' && $password != ''){
                    $post_username  = $username;
                    $post_password  = md5($password);

                    $where = '(user_name="'.$post_username.'" OR user_email = "'.$post_username.'")';
                    $d = $this->db->get_where("mt_app_user",$where)->row();
                    if(count($d) > 0){
                        if($post_password == $d->user_password){

                            $error          = false;
                            $user_id        = $d->user_id;
                            $user_name      = $d->user_name;
                            $user_email     = $d->user_email;
                            $user_fullname  = $d->user_fullname;
                            $user_photo     = $d->user_photo;
                            $user_group     = $d->user_group;
                            $token_apps     = $d->token_apps;

                            $store_id       = $d->store_id;
                            $detail_store   = get_detail_store($store_id);
                            $store_name     = $detail_store->store_name;
                            $store_phone    = $detail_store->store_phone;

                            $this->db->update("mt_app_user",array("user_logindate"=>timestamp()),array("user_id"=>$user_id));

                            if($token_apps == ''){
                                $token_apps = generateUniqueToken(20,'mt_app_user','token_apps');
                                $this->db->update("mt_app_user",array("token_apps"=>$token_apps),array("user_id"=>$user_id));
                            }
                        } else {
                            $msg = 'Password Anda salah.';
                        }
                    } else {
                        $msg = 'Email/Username Anda tidak terdaftar.';
                    }

                    $return = array('user_id'       => $user_id,
                                    'user_name'     => $user_name,
                                    'user_email'    => $user_email,
                                    'user_fullname' => $user_fullname,
                                    'user_photo'    => $user_photo,
                                    'user_group'    => $user_group,
                                    'store_id'      => $store_id,
                                    'store_name'    => $store_name,
                                    'store_phone'   => $store_phone,
                                    'token_apps'    => $token_apps,
                                    'error'         => $error,
                                    'msg'           => $msg
                                );
                    die(json_encode($return));
                    exit();
                }
            }
        }
    }

    function register(){
        // debugCode('register');

        $user_id        = '';
        $error          = true;
        $msg            = '';
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request    = json_decode($_POST["params"]);
            $username   = mysql_real_escape_string($request->username);
            $password   = mysql_real_escape_string($request->password);
            $thisAction = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'register'){

            }
        }
    }

    function get_app_user(){
        $user_id        = '';
        $user_name      = '';
        $user_email     = '';
        $user_fullname  = '';
        $user_photo     = '';
        $user_group     = '';
        $token_apps     = '';
        $error          = true;
        $msg            = '';
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request     = json_decode($_POST["params"]);
            $user_id     = mysql_real_escape_string($request->user_id);
            $token       = mysql_real_escape_string($request->token);
            $thisAction  = mysql_real_escape_string($request->thisAction);

            if($thisAction == 'getdata'){
                $d = $this->db->get_where("mt_app_user",array(
                    "user_id"       => $user_id,
                    "token_apps"    => $token
                ),1,0)->row();
                if(count($d) > 0){
                    $user_id        = $d->user_id;
                    $user_name      = $d->user_name;
                    $user_email     = $d->user_email;
                    $user_fullname  = $d->user_fullname;
                    $user_photo     = $d->user_photo;
                    $user_group     = $d->user_group;
                    $store_id       = $d->store_id;
                    $token_apps     = $d->token_apps;

                    $error  = false;
                    $msg    = "User ditemukan..";
                } else {
                    $error = true;
                    $msg   = "User tidak ditemukan.";
                }

                $return = array('user_id'       => $user_id,
                                'user_name'     => $user_name,
                                'user_email'    => $user_email,
                                'user_fullname' => $user_fullname,
                                'user_photo'    => $user_photo,
                                'user_group'    => $user_group,
                                'store_id'      => $store_id,
                                'token_apps'    => $token_apps,
                                'error'         => $error,
                                'msg'           => $msg
                            );
                die(json_encode($return));
                exit();
            }
        }
    }

}
