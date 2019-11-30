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
$mod = isset($_GET['mod']) && $_GET['mod'] ? $_GET['mod'] : '';
$act = isset($_GET['act']) && $_GET['act'] ? $_GET['act'] : '';
if($act){
	if(!$index->check)	$index->$act();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>生产管理系统</title>
<link href="css/css.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.ui.css" rel="stylesheet" type="text/css" />
<script>var no_refresh = 0;</script>
</head>

<body>
	<div class="header">
		<h1>生产管理系统</h1>
		<ul class="menu">
			<?if($index->member_info['group_id'] == 9){?>
			<a href="?mod=member"<?=in_array($mod, array('member', 'edit_member')) ? ' class="cur"': ''?>>员工管理</a>
			<a href="?mod=machine"<?=in_array($mod, array('machine', 'edit_machine')) ? ' class="cur"': ''?>>机器管理</a>
			<a href="?mod=material"<?=in_array($mod, array('material', 'edit_material')) ? ' class="cur"': ''?>>材料管理</a>
			<a href="?mod=customer"<?=in_array($mod, array('customer', 'edit_customer')) ? ' class="cur"': ''?>>客户管理</a>
			<a href="?mod=order"<?=in_array($mod, array('order', 'edit_order', 'edit_order_status')) ? ' class="cur"': ''?>>订单管理</a>
            <a href="?mod=work_order"<?=in_array($mod, array('add_work_order','work_order')) ? ' class="cur"': ''?>>工单列表</a>
			<?}elseif(in_array($index->member_info['group_id'], array(4))){?>
			<a href="?mod=order_1"<?=in_array($mod, array('order_1', 'edit_order')) ? ' class="cur"': ''?>>下单管理</a>
			<a href="?mod=order_2"<?=in_array($mod, array('order_2', 'edit_order_status')) ? ' class="cur"': ''?>>发货管理</a>
			<a href="?mod=order_3"<?=in_array($mod, array('order_3')) ? ' class="cur"': ''?>>订单管理</a>
			<?}elseif(in_array($index->member_info['group_id'], array(1))){?>
			<a href="?mod=customer"<?=in_array($mod, array('customer', 'edit_customer')) ? ' class="cur"': ''?>>客户管理</a>
			<a href="?mod=order_1"<?=in_array($mod, array('order_1', 'edit_order')) ? ' class="cur"': ''?>>下单管理</a>
			<a href="?mod=order_2"<?=in_array($mod, array('order_2', 'edit_order_status')) ? ' class="cur"': ''?>>发货管理</a>
			<a href="?mod=order_3"<?=in_array($mod, array('order_3')) ? ' class="cur"': ''?>>订单管理</a>
			<a href="?mod=finance_1"<?=in_array($mod, array('finance_1')) ? ' class="cur"': ''?>>待回款管理</a>
			<?}elseif(in_array($index->member_info['group_id'], array(2))){?>
			<a href="?mod=order_1"<?=in_array($mod, array('order_1', 'edit_order_status')) ? ' class="cur"': ''?>>待生产管理</a>
			<a href="?mod=order_2"<?=in_array($mod, array('order_2')) ? ' class="cur"': ''?>>已生产管理</a>
			<a href="?mod=order_3"<?=in_array($mod, array('order_3')) ? ' class="cur"': ''?>>生产管理</a>
			<?}elseif(in_array($index->member_info['group_id'], array(5))){?>
				<a href="?mod=finance_1"<?=in_array($mod, array('finance_1')) ? ' class="cur"': ''?>>待回款管理</a>
				<a href="?mod=finance_2"<?=in_array($mod, array('finance_2')) ? ' class="cur"': ''?>>已回款</a>
				<a href="?mod=finance_3"<?=in_array($mod, array('finance_3')) ? ' class="cur"': ''?>>订单明细</a>
			<?}?>
		</ul>
		<?if($index->mid){?>
		<dl class="member_info">
			<dd>系统用户：<?=$index->member_info["username"]?>(<?=$index->group_name[$index->member_info["group_id"]]?>)
			|
			<a href="?mod=edit_pass">修改密码</a>
			|
			<a href="?mod=login&act=logout">退出</a>
			</dd>
			<dd class="time"><?=$index->today();?></dd>
		</dl>
		<?}?>
	</div>
	<div class="main">
	<?if($index->check || $index->ok){
		$index->do_error();
	}elseif($mod && 'mod/' . $mod . '.php'){
		include('mod/' . $mod . '.php');
	}else{?>
		<p class="welcome">欢迎使用生产管理系统！</p>
	<?}?>
	</div>
	<div class="footer">
		生产管理系统1.0，技术支持：Prince，QQ：178642152
	</div>
</body>
</html>
<script type="text/javascript" src="js/jquery.js"></script>
<?$PHP_SELF=$_SERVER['PHP_SELF'];
$baseurl='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strrpos($PHP_SELF,'/'));
?>
<script> var THEME_URL = '<?=$baseurl;?>';</script>
<script src="js/core.js?11"></script>
<script type="text/javascript" src="js/jquery.ui.js?3"></script>
<script type="text/javascript" src="js/jquery.ui.datepicker-zh-CN.js"></script>
<script src="js/jquery.fileupload.js"></script>
<script type="text/javascript" src="js/js.js?2019040124"></script>