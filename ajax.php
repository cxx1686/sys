<?php
ob_start();
session_start();
error_reporting(0);
header('Content-Type:text/html;charset=utf-8');
date_default_timezone_set('PRC');
require_once("config.php");
require_once("mysql.php");
require_once("inc.php");
require_once("page.class.php");
$db = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
$index = new index();
$act = isset($_GET['act']) && $_GET['act'] ? $_GET['act'] : '';
if($act){
	if($act == 'order_excel'){
		$index->order_excel();
	}elseif($act == 'edit_order_status'){
		$index->edit_order_status();
	}elseif($act == 'get_customer_select'){
		$str['status'] = 1;
		$str['con'] = $index->get_customer_select('all');
		exit(json_encode($str));
	}elseif($act == 'get_customer_pay_types'){
    $str['status'] = 1;
    $str['con'] = $index->get_customer_pay_types();
    exit(json_encode($str));
  }elseif($act == 'confirm_production_member'){
    $index->confirm_production_member();
    if($index->ok){
      $str['status'] = 1;
      $str['msg'] = implode("/n", $index->error);
    }elseif($index->check){
      $str['status'] = 0;
      $str['msg'] = implode("/n", $index->error);
    }
    exit(json_encode($str));
  }
	elseif($act == 'do_order_status'){
		$index->do_order_status();
		if($index->ok){
			$str['status'] = 1;
			$str['msg'] = implode("/n", $index->error);
		}elseif($index->check){
			$str['status'] = 0;
			$str['msg'] = implode("/n", $index->error);
		}
		exit(json_encode($str));
	}elseif($act == 'do_order_finance_status'){
    $index->do_order_finance_status();
    if($index->ok){
      $str['status'] = 1;
      $str['msg'] = implode("/n", $index->error);
    }elseif($index->check){
      $str['status'] = 0;
      $str['msg'] = implode("/n", $index->error);
    }
    exit(json_encode($str));
  }elseif($act == 'edit_finance'){
		$index->edit_finance();
	}else{
		if(!$index->check){
			$str['status'] = 1;
			$str['con'] = $index->$act();
		}else{
			$str['status'] = 0;
			$str['msg'] = implode("/n", $index->error);
		}
		exit(json_encode($str));
	}
}
?>
