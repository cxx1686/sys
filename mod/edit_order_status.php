<?$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order_info = $index->get_order($order_id);
$index->order_status_jurisdiction($order_info);
if($index->check){
	$index->do_error();
}else{?>
<form action="?act=do_order_status" method="post">
	<table class="form">
		<caption>修改订单状态</caption>
		<input type="hidden" name="order_id" value="<?=isset($_GET['order_id']) ? $_GET['order_id'] : 0?>" />
		<tr>
			<th>客户</th>
			<td><?=$index->get_customer_name($order_info['customer_id']);?></td>
		</tr>
		<tr>
			<th>业务员</th>
			<td><?=$index->get_member_name($order_info['member_id']);?></td>
		</tr>
		<tr>
			<th>克重</th>
			<td><?=$order_info['weight']?></td>
		</tr>
		<tr>
			<th>价格</th>
			<td><?=$order_info['price']?></td>
		</tr>
		<tr>
			<th>材料</th>
			<td><?=$index->get_material_name($order_info['material_id'])?></td>
		</tr>
		<tr>
			<th>预计发货时间</th>
			<td><?=date('Y-m-d', $order_info['delivery_time']) . ' ' .  (date('H', $v['delivery_time']) >= 18 ? '晚' : '早')?></td>
		</tr>
		<tr>
			<th>备注</th>
			<td><?=str_replace("\n", '<br />', $order_info['remarks'])?></td>
		</tr>
		<?if(in_array($index->member_info['group_id'], array(2, 9))){?>
		<tr>
			<th>上机状态</th>
			<td><select name="production_status">
				<option value="0">待上机</option>
				<option value="1"<?=$order_info['production_status'] == 1 ? ' selected' : ''?>>部分上机</option>
				<option value="2"<?=$order_info['production_status'] == 2 ? ' selected' : ''?>>已上机</option>
			</select></td>
		</tr>
		<tr>
			<th>机器</th>
			<td><select name="machine_id">
				<option value="0">选择机器</option>
				<?$machine_select = $index->get_machine_select();
				if($machine_select){
					foreach($machine_select as $v){?>
				<option value="<?=$v['machine_id']?>"<?=$order_info['machine_id'] == $v['machine_id'] ? ' selected' : ''?>><?=$v['name']?></option>
					<?}
				}?>
			</select></td>
		</tr>
		<?}if($index->member_info['group_id'] == 9){?>
		<tr>
			<th>技术员</th>
			<td><select name="production_id">
			<option value="0">不指定技术员</option>
			<?$member_select = $index->get_member_select(2);
			if($member_select){
				foreach($member_select as $v){?>
			<option value="<?=$v['member_id']?>"<?=$order_info['member_id'] == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
				<?}
			}?>
			</select></td>
		</tr>
		<?}?>
		<tr>
			<th>发货时间</th>
			<td>
				<input type="text" class="text" readonly id="result_delivery_time" name="result_delivery_time" value="<?=$order_info['result_delivery_time'] ? date('Y-m-d', $order_info['result_delivery_time']) : ''?>" />
				<?=$order_info['result_delivery_time'] ? $index->get_datetime($order_info['result_delivery_time']) : $index->get_datetime($order_info['result_delivery_time'])?>
			</td>
		</tr>
		<?if(in_array($index->member_info['group_id'], array(1, 9))){?>
		<tr>
			<th>发货状态</th>
			<td><select name="delivery_status">
				<option value="0">未发货</option>
				<option value="1"<?=$order_info['delivery_status'] == 1 ? ' selected' : ''?>>已发货</option>
			</select></td>
		</tr>
		<?}?>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="修改" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>