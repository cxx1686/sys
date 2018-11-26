<div class="search_bar">
	<form action="" method="get" id="order_search">
		<input type="hidden" name="mod" value="finance_1" />
		<ul>
			<li>
				<select name="customer_id" id="customer_id">
					<option value="0">所有客户</option>
					<?$customer_select = $index->get_customer_select($_GET['member_id']);
					foreach($customer_select as $v){?>
					<option value="<?=$v['customer_id']?>"<?=isset($_GET['customer_id']) && intval($_GET['customer_id']) == $v['customer_id'] ? ' selected' : ''?>><?=$v['attr_name']?></option>
					<?}?>
				</select><input type="text" class="text" id="search_customer" placeholder="搜索客户" />
				
				
				<input type="text" name="order_start_time" id="datepicker1" value="<?=isset($_GET['order_start_time']) ? $_GET['order_start_time'] : '';?>" readonly="readonly" placeholder="下单开始日期" />
				-
				<input type="text" name="order_end_time" id="datepicker2" value="<?=isset($_GET['order_end_time']) ? $_GET['order_end_time'] : '';?>" readonly="readonly" placeholder="下单截止日期" />
				
				
			</li>
		</ul>
		<input type="submit" value="查询" />
	</form>
    <ul class="clear"></ul>
</div>
<form action="?act=all_status" method="post">
<input type="hidden" name="purl" value="<?='?' . $_SERVER['QUERY_STRING']?>" />
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>订单列表</caption>
	<tr>
		<th>日期</th>
		<th>客户</th>
		<th>总克重</th>
		<th>已回款金额</th>
		<th>付款方式</th>
	</tr>
	<?$list = $index->settle_total();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		 <td><?=$v['order_create_month']?></td>
     <td><?=$index->get_customer_name($v['customer_id'])?></td>
		 <td><?=$v['total_weight']?></td>
     <td><?=$v['total_settle_price']?></td>
     <td><?=$v['pay_type']?></td>
	</tr>

	<?}?>
		<tr>
			<td colspan="14">
				<div style="float:right;line-height:40px;"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></div>
			</td>
		</tr>
	<?}else{?>
	<tr><td colspan="14" class="no_info">没有订单！</td></tr>
	<?}?>
</table>
</form>