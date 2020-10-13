<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(APPPATH."libraries/FrontController.php");
class tester extends FrontController {
    var $cur_menu = '';

    function __construct()
    {
        parent::__construct();


        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }

    }

    function index(){
        $error  = false;
        $msg    = 'Sukses cek koneksi.';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");
        if (isset($_POST)) {
            $request = json_decode($_POST["params"]);
            $user_id                = mysql_real_escape_string($request->user_id);
            $store_id               = mysql_real_escape_string($request->store_id);
            $token                  = mysql_real_escape_string($request->token);
            $thisAction             = mysql_real_escape_string($request->thisAction);

        }

        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

    function test_get(){
        $error  = false;
        $msg    = 'Sukses cek koneksi.';
        $total  = 0;
        $rows   = array();
        $result = array();
        $data   = array();
        // $postdata = file_get_contents("php://input");

        $rows['numrows']  = $total;
        $rows['result']   = $result;

        $rows['error']  = $error;
        $rows['msg']    = $msg;
        die(json_encode($rows));
        exit();
    }

}
