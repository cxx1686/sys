<div class="search_bar">
	<form action="" method="get" id="order_search">
		<input type="hidden" name="mod" value="finance_3" />
		<ul>
			<li>
				<select name="customer_id" id="customer_id">
					<option value="0">所有客户</option>
					<?$customer_select = $index->get_customer_select($_GET['member_id']);
					foreach($customer_select as $v){?>
						<option value="<?=$v['customer_id']?>"<?=isset($_GET['customer_id']) && $_GET['customer_id'] == $v['customer_id'] ? ' selected' : ''?>><?=$v['attr_name']?></option>
					<?}?>
				</select><input type="text" name="ck" value="<?=$_GET['ck']?>" class="text" id="search_customer" placeholder="搜索客户" />

				<input type="text" class="text" name='finance_no'id="finance_no" value="<?=empty($_GET['finance_no'])?'':$_GET['finance_no']?>"placeholder="回款编号" />

				<input type="text" name="start_settle_date" id="datepicker1" value="<?=isset($_GET['start_settle_date']) ? $_GET['start_settle_date'] : '';?>" readonly="readonly" placeholder="回款开始日期" />
				-
				<input type="text" name="end_settle_date" id="datepicker2" value="<?=isset($_GET['end_settle_date']) ? $_GET['end_settle_date'] : '';?>" readonly="readonly" placeholder="回款截止日期" />
				<input type="text" name="order_start_time" style="width:80px;" id="order_start_time1" value="<?=isset($_GET['order_start_time']) ? $_GET['order_start_time'] : '';?>" readonly="readonly" placeholder="下单开始日期" />
				-
				<input type="text" name="order_end_time" style="width:80px;" id="order_start_time2" value="<?=isset($_GET['order_end_time']) ? $_GET['order_end_time'] : '';?>" readonly="readonly" placeholder="下单截止日期" />

				<select name="production_status">
					<option value="">生产状态</option>
					<option value="0"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==0 ? ' selected' : '';?>>待上机</option>
					<option value="1"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==1 ? ' selected' : '';?>>部分上机</option>
					<option value="2"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==2 ? ' selected' : '';?>>已上机</option>
				</select>
				<select name="finance_status">
					<option value="">回款状态</option>
					<option value="0"<?=isset($_GET['finance_status']) && is_numeric($_GET['finance_status']) && $_GET['finance_status']==0 ? ' selected' : '';?>>未回款</option>
					<option value="1"<?=isset($_GET['finance_status']) && is_numeric($_GET['finance_status']) && $_GET['finance_status']==1 ? ' selected' : '';?>>部分回款</option>
					<option value="2"<?=isset($_GET['finance_status']) && is_numeric($_GET['finance_status']) && $_GET['finance_status']==2 ? ' selected' : '';?>>已回款</option>
				</select>
                <select name="pay_type">
                    <option value="0">付款方式</option>
                  <?$pay_type_list = $index->pay_type_list;
                  foreach($pay_type_list as $v){?>
                      <option value="<?=$v?>"<?=isset($_GET['pay_type']) && $_GET['pay_type'] == $v ? ' selected' : ''?>><?=$v?></option>
                  <?}
                  ?>
                </select>
			</li>
		</ul>
		<input type="submit" value="查询" />
		<? if(!empty($_GET['finance_no'])){?>
			<input type="button" name="submit" class="btn3" value="撤回该笔回款" onclick="reset_finance('<?=$_GET['finance_no']?>')" />
		<?}?>
	</form>
    <ul class="clear"></ul>
</div>
<form action="?act=all_status" method="post">
<input type="hidden" name="purl" value="<?='?' . $_SERVER['QUERY_STRING']?>" />
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>订单列表</caption>
	<tr>
		<th>订单号</th>
		<th>客户</th>
    <th>业务员</th>
		<th>克重</th>
		<th>价格</th>
		<th>付款方式</th>
		<th>材料</th>
		<th>下单时间</th>
		<th>回款状态</th>
        <th>部分回款金额</th>
		<th>回款编号</th>
		<th>回款时间</th>
		<th>操作</th>
	</tr>
	<?$list = $index->order_list(3);
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td><?=date('Ymdhis', $v['order_time'])?></td>
		<td><?=$index->get_customer_name($v['customer_id'])?></td>
		<td><?=$index->get_member_name($v['member_id'])?></td>
		<td><?=$v['weight']?></td>
		<td><?=$v['price']?></td>
		<td><?=$v['pay_type']?></td>
		<td><?=$index->get_material_name($v['material_id'])?></td>
        <td><?=date('Y-m-d H:i:s', $v['order_time'])?></td>
        <td><?=$index->get_finance_status_cn($v['finance_status'])?></td>
        <td><?=$v['sy_price'] >0 ? $v['price']-$v['sy_price'] : 0?></td>
		<td><?=$v['finance_no']?></td>
		<td><?= $v['settle_time']==0?'':date('Y-m-d H:i:s', $v['settle_time'])?></td>
		<td>
			<? if ($v['finance_status']!=2){?><a class="btn" onclick="edit_finance_by_order('<?=$v['order_id']?>')">回款</a>
			<?}else{?>
			<a class="btn" onclick="reset_finance_by_order_id('<?=$v['order_id']?>')">撤回</a>
			<?}?>
		</td>

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