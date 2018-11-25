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
		<th>全选<input type="checkbox" id="chkall" onclick="all_select(this,'.ids')" /></th>
        <th>日期</th>
        <th>客户</th>
        <th>总克重</th>
        <th>应收款</th>
        <th>付款方式</th>
        <th>状态</th>
        <th>回款编号</th>
		<th>操作</th>
	</tr>
	<?$list = $index->should_gain_total();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td ><input type="checkbox" onclick="checkboxOnclick(this)"  class="ids" name="ids[]" value="<?=$v["customer_id"]?>" /><?=$v["customer_id"]?></td>
        <td><?=$v['order_create_month']?></td>
        <td><?=$index->get_customer_name($v['customer_id'])?></td>
        <td><?=$v['total_weight']?></td>
		<td><?=$v['should_gain']?></td>
        <td><?=$v['pay_type']?></td>
        <td><? if($v['total_sy_price']>0){echo '部分回款';}else{ echo '待回款';}?></td>
        <td></td>
		<td>
            <a class="btn" onclick="edit_finance('<?=$v['customer_id'].'_'.$v['order_create_month']?>')">回款</a>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="14">
			<div style="float:left;color:red;line-height:40px;"><?if($index->member_info['group_id']==2){?>
                    <input type="button" name="submit" value="部分收款" class="btn3" onclick="edit_order_status(5, 'all')" />
                    <input type="button" name="submit" class="btn3" value="全部收款" onclick="edit_order_status(1, 'all')" />
              <?}?>
			</div>
			<div style="float:right;line-height:40px;"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></div>
		</td>
	</tr>
	<?}else{?>
	<tr><td colspan="14" class="no_info">没有订单！</td></tr>
	<?}?>
</table>
</form>