<div class="search_bar">
	<form action="" method="get" id="order_search">
		<input type="hidden" name="mod" value="order_3" />
		<ul>
			<li>
				<select name="customer_id" id="customer_id">
					<option value="0">所有客户</option>
					<?$customer_select = $index->get_customer_select($_GET['member_id']);
					foreach($customer_select as $v){?>
					<option value="<?=$v['customer_id']?>"<?=isset($_GET['customer_id']) && intval($_GET['customer_id']) == $v['customer_id'] ? ' selected' : ''?>><?=$v['attr_name']?></option>
					<?}?>
				</select><input type="text" class="text" id="search_customer" placeholder="搜索客户" />
				<?if($index->member_info['group_id'] == 2){?>
				<input type="text" name="start_time" id="datepicker1" value="<?=isset($_GET['start_time']) ? $_GET['start_time'] : '';?>" readonly="readonly" placeholder="发货开始时间" />
				<select name="start_time_ext">
					<option value="8">早</option>
					<option value="18"<?=isset($_GET['start_time_ext']) && $_GET['start_time_ext']==18 ? ' selected' : '';?>>晚</option>
				</select>
				-
				<input type="text" name="end_time" id="datepicker2" value="<?=isset($_GET['end_time']) ? $_GET['end_time'] : '';?>" readonly="readonly" placeholder="发货截止时间" />
				<select name="end_time_ext">
					<option value="8">早</option>
					<option value="18"<?=isset($_GET['end_time_ext']) && $_GET['end_time_ext']==18 ? ' selected' : '';?>>晚</option>
				</select>
				<?}elseif($index->member_info['group_id'] == 1){?>
				<input type="text" name="order_start_time" id="datepicker1" value="<?=isset($_GET['order_start_time']) ? $_GET['order_start_time'] : '';?>" readonly="readonly" placeholder="下单开始日期" />
				-
				<input type="text" name="order_end_time" id="datepicker2" value="<?=isset($_GET['order_end_time']) ? $_GET['order_end_time'] : '';?>" readonly="readonly" placeholder="下单截止日期" />
				<?}?>
				<select name="material_id" id="material_id">
					<option value="">材料</option>
					<?$material_select = $index->get_material_select();
					if($material_select){
						foreach($material_select as $v){?>
					<option value="<?=$v['material_id']?>"<?=isset($_GET['material_id']) && $_GET['material_id'] == $v['material_id'] ? ' selected' : ''?>><?=$v['name']?></option>
						<?}
					}?>
				</select><input type="text" class="text" id="search_material" placeholder="搜索材料" />
				<select name="machine_id">
					<option value="0">上机状态</option>
					<?$machine_select = $index->get_machine_select();
					if($machine_select){
						foreach($machine_select as $v){?>
					<option value="<?=$v['machine_id']?>"<?=isset($_GET['machine_id']) && $_GET['machine_id'] == $v['machine_id'] ? ' selected' : ''?>><?=$v['name']?></option>
						<?}
					}?>
				</select>
				<select name="production_status">
					<option value="">生产状态</option>
					<option value="0"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==0 ? ' selected' : '';?>>待上机</option>
					<option value="1"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==1 ? ' selected' : '';?>>部分上机</option>
					<option value="2"<?=isset($_GET['production_status']) && is_numeric($_GET['production_status']) && $_GET['production_status']==2 ? ' selected' : '';?>>已上机</option>
				</select>
				<select name="delivery_status">
					<option value="">发货状态</option>
					<option value="0"<?=isset($_GET['delivery_status']) && is_numeric($_GET['delivery_status']) && $_GET['delivery_status']==0 ? ' selected' : '';?>>未发货</option>
					<option value="1"<?=isset($_GET['delivery_status']) && is_numeric($_GET['delivery_status']) && $_GET['delivery_status']==1 ? ' selected' : '';?>>已发货</option>
				</select>
			</li>
		</ul>
		<input type="submit" value="查询" />
	</form>
	<ul class="clear"></ul>
</div>
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>订单列表</caption>
	<tr>
		<th>订单号</th>
		<th>客户</th>
		<th>克重<div class="ordertype"><a href="<?=$index->get_ordertype('weight', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='weight' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('weight', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='weight' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<th>价格<div class="ordertype"><a href="<?=$index->get_ordertype('price', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='price' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('price', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='price' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<?if(in_array($index->member_info['group_id'], array(1, 4))){?><th>付款方式</th><?}?>
		<th>材料</th>
		<th>发货时间<div class="ordertype"><a href="<?=$index->get_ordertype('delivery_time', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='delivery_time' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('delivery_time', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='delivery_time' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<th>上机状态<div class="ordertype"><a href="<?=$index->get_ordertype('machine_id', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='machine_id' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('machine_id', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='machine_id' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<th>生产状态<div class="ordertype"><a href="<?=$index->get_ordertype('production_status', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='production_status' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('production_status', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='production_status' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<th>发货状态</th>
		<th>下单时间<div class="ordertype"><a href="<?=$index->get_ordertype('order_time', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='order_time' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('order_time', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='order_time' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
        <th>上机时间<div class="ordertype"><a href="<?=$index->get_ordertype('production_time', 'asc')?>" class="asc<?=isset($_GET['order']) && $_GET['order']=='production_time' && isset($_GET['type']) && $_GET['type']=='asc' ? ' cur' : ''?>"></a><a href="<?=$index->get_ordertype('production_time', 'desc')?>" class="desc<?=isset($_GET['order']) && $_GET['order']=='production_time' && isset($_GET['type']) && $_GET['type']=='desc' ? ' cur' : ''?>"></a></div></th>
		<th>技术员</th>
    <th>图片</th>
		<th>附件</th>
		<th>备注</th>
		<th>操作</th>
	</tr>
	<?$list = $index->order_list();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td><?=date('Ymdhis', $v['order_time'])?></td>
		<td><?=$index->get_customer_name($v['customer_id'])?></td>
		<td><?=$v['weight']?></td>
		<td><?=$v['price']?></td>
		<?if(in_array($index->member_info['group_id'], array(1, 4))){?><td><?=$v['pay_type']?></td><?}?>
		<td><?=$index->get_material_name($v['material_id'])?></td>
		<td><?=$v['result_delivery_time'] ? (date('Y-m-d', $v['result_delivery_time']) . ' ' . (date('H', $v['result_delivery_time']) >= 18 ? '晚' : '<font color=orange>早</font>')) : ($v['delivery_time'] > time() ? (date('Y-m-d', $v['delivery_time']) . ' ' . (date('H', $v['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) : '<font color=red>' . (date('Y-m-d', $v['delivery_time']) . ' ' . (date('H', $v['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) . '</font>')?></td>
		<td><?=$v['machine_id']==0 ? '待上机' : $index->get_machine_name($v['machine_id'])?></td>
		<td><?=$v['production_status']==1 ? '部分上机' : ($v['production_status']==2 ? '全部上机' : '待上机')?></td>
		<td><?=$v['delivery_status']==1 ? '已发货' : '未发货'?></td>
		<td><?=date('Y-m-d H:i:s', $v['order_time'])?></td>
        <td><?=$v['production_time'] ? date('Y-m-d H:i', $v['production_time']) : '未上机';?></td>
		<td><font color="blue"><?if(!empty($v['production_member_id'])){?><?=$index->get_member_name($v['production_member_id'])?><?}?></font></td>
        <td><?=$v['img'] ? '<a href="server/php/files/' . $v['img'] . '" target="_blank">查看</a>' : ''?></td>
		<td><?=$v['zip_path'] ? '<a href="' . $v['zip_path'] . '" target="_blank">下载</a>' : ''?></td>
		<td><?=str_replace("\n", '<br />', $v['remarks'])?></td>
		<td>
			<?if(in_array($index->member_info['group_id'], array(1, 4))){
			if($v['production_status'] == 0){?>
			<a class="btn" href="?mod=edit_order&order_id=<?=$v['order_id']?>">修改</a>
			<a class="btn" href="?act=del_order&order_id=<?=$v['order_id']?>" onclick="return del();">删除</a>
			<?}elseif($v['delivery_status'] == 0 && $v['production_status'] == 2){?>
			<!--<a class="btn" href="?mod=edit_order_status&order_id=<?=$v['order_id']?>">修改发货状态</a>-->
			<a class="btn"  onclick="edit_order_status(2, <?=$v['order_id']?>)">修改发货状态</a>
			<?}
			}elseif($index->member_info['group_id'] == 2 && $v['production_status'] < 2 && $v['delivery_status'] == 0){?>
				<!--<a class="btn" href="?mod=edit_order_status&order_id=<?=$v['order_id']?>">修改生产状态</a>-->
				<a class="btn" onclick="edit_order_status(1, <?=$v['order_id']?>)">修改生产状态</a>
			<?}?>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="<?=in_array($index->member_info['group_id'], array(1, 4))?14:13?>">
			<div style="float:left;color:red;"><b>总克重：</b><?=$index->count_weight;?><?if($index->member_info['group_id']==1){?>，<b>总金额：</b><? echo $index->count_price;}?></div>
			<div style="float:right;"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></div>
		</td>
	</tr>
	<?}else{?>
	<tr><td colspan="<?=$index->member_info['group_id']==1?14:13?>" class="no_info">没有订单！</td></tr>
	<?}?>
</table>