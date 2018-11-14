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
	if($act=='del_attch')
	{	
		$path = $_GET['path'];
		$index->del_attch($path);
		$res = array('code' => 0, 'msg' => '删除成功');
		exit(json_encode($res));
	}
}