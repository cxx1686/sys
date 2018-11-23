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
	
</div>
<form action="?act=all_status" method="post">
<input type="hidden" name="purl" value="<?='?' . $_SERVER['QUERY_STRING']?>" />
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>订单列表</caption>
	<tr>
		<th>全选<input type="checkbox" id="chkall" onclick="all_select(this,'.ids')" /></th>
		<th>客户</th>
        <th>应收款</th>
		<th>操作</th>
	</tr>
	<?$list = $index->should_gain_total();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td ><input type="checkbox" onclick="checkboxOnclick(this)"  class="ids" name="ids[]" value="<?=$v["customer_id"]?>" /><?=$v["customer_id"]?></td>
		<td><?=$index->get_customer_name($v['customer_id'])?></td>
		<td><?=$v['should_gain']?></td>
		
		<td>
			<?if(in_array($index->member_info['group_id'], array(1, 4))){?>
			<a class="btn" href="?mod=edit_order&order_id=<?=$v['order_id']?>">修改</a>
			<a class="btn" href="?act=del_order&order_id=<?=$v['order_id']?>" onclick="return del();">删除</a>
			<?}?>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="14">
			<div style="float:left;color:red;line-height:40px;"><?if($index->member_info['group_id']==2){?>
                    <input type="button" name="submit" value="部分上机" class="btn3" onclick="edit_order_status(5, 'all')" />
                    <input type="button" name="submit" class="btn3" value="全部上机" onclick="edit_order_status(1, 'all')" />
                    <input type="button" name="submit" class="btn3" value="选择订单" onclick="confirm_production_member(<?=$index->mid?>)" />
              <?}?>
                    
			<?if(in_array($index->member_info['group_id'],array(1,2))){?>
			&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<?$sum_list = $index->sum_list;
			if($sum_list)$num = count($sum_list);$i=1;{foreach($sum_list as $n=>$v){?>
				<b><?=$index->get_material_name($v['material_id'])?>待上机：</b><?=$v['cw'];?>
				<?if($i<$num){?>，<?} $i++;?>
			<?}}?>
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
<script>no_refresh = 1;</script>