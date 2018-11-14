<form class="box_form" id="ajax_order_status">
	<table class="form">
		<input type="hidden" name="order_id" value="<?=isset($_GET['order_id']) ? $_GET['order_id'] : 0?>" />
		<?if(in_array($this->member_info['group_id'], array(2, 9))){?>
		<tr>
			<th>上机状态</th>
			<td>
            <?if($_GET['part']==1){?>
                <input type="hidden" name="production_status" id="production_status" value="1" />
                部分上机
                <input style="width:65px;" type="text" name="sy_weight" class="text" id="sy_weight" value="<?=$order_info['sy_weight'] ? $order_info['sy_weight'] : ''?>" placeholder="剩余克重" />
            <?}else{?>
            <?if($_GET['all_pro']){?>
			<input type="hidden" name="production_status" value="2" />
			全部上机
			<?}else{?>
				<select name="production_status" onchange="view_kz(this.value);">
					<option value="0">待上机</option>
					<option value="1"<?=$order_info['production_status'] == 1 ? ' selected' : ''?>>部分上机</option>
					<option value="2"<?=$order_info['production_status'] == 2 ? ' selected' : ''?>>已上机</option>
				</select>
				<input style="width:65px;<?if($order_info['production_status'] != 1){?>display:none;<?}?>" type="text" name="sy_weight" class="text" id="sy_weight" value="<?=$order_info['sy_weight'] ? $order_info['sy_weight'] : ''?>" placeholder="剩余克重" />
				<?}?>
            <?}?>
			</td>
		</tr>
		<tr>
			<th>机器</th>
			<td><select name="machine_id">
				<option value="0">选择机器</option>
				<?$machine_select = $this->get_machine_select();
				if($machine_select){
					foreach($machine_select as $v){?>
				<option value="<?=$v['machine_id']?>"<?=$order_info['machine_id'] == $v['machine_id'] ? ' selected' : ''?>><?=$v['name']?></option>
					<?}
				}?>
			</select></td>
		</tr>
		<?}if(!$_GET['all_pro']){
		if(in_array($this->member_info['group_id'], array(2, 9))){?>
		<tr>
			<th>技术员</th>
			<td><select name="production_id">
			<option value="0">不指定技术员</option>
			<?$member_select = $this->get_member_select(2);
			if($member_select){
				foreach($member_select as $v){?>
			<option value="<?=$v['member_id']?>"<?=$order_info['member_id'] == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
				<?}
			}?>
			</select></td>
		</tr>
		<?}?>
        <?if($_GET['step']==4){?>
				<tr>
					<th>价格</th>
					<td>
						<input style="width:65px;" type="text" class="text" name="price" value="<?=$order_info['price'] ?>" />
					</td>
				</tr>
				<tr>
					<th>付款方式</th>
					<td>
						<select name="pay_type">
							<?$pay_types = $this->get_customer_pay_types($order_info['customer_id']);
							foreach($pay_types as $v){?>
								<option value="<?=$v?>"<?= $order_info['pay_type'] == $v ? ' selected' : ''?>><?=$v?></option>
							<?}?>
						</select>
					</td>
				</tr>
			<?}?>
		<tr>
			<th>发货时间</th>
			<td>
				<input type="text" class="text" readonly id="result_delivery_time" name="result_delivery_time" value="<?=$order_info['result_delivery_time'] ? date('Y-m-d', $order_info['result_delivery_time']) : ''?>" />
				<?=$order_info['result_delivery_time'] ? $this->get_datetime($order_info['result_delivery_time']) : $this->get_datetime($order_info['result_delivery_time'])?>
			</td>
		</tr>

		<?if(in_array($this->member_info['group_id'], array(1, 4, 9))){?>
		<tr>
			<th>发货状态</th>
			<td><select name="delivery_status">
				<option value="0" <?if($_GET['step']==4){?>selected<?}?>>未发货</option>
				<option value="1" <?if($_GET['step']!=4){?>selected<?}?>>已发货</option>
			</select></td>
		</tr>
		<?}
		}?>
		<tr>
			<th></th>
			<td>
				<input type="button" onclick="do_order_status('#ajax_order_status');" value="修改" class="btn" />
				<input type="button" onclick="ui.box.close();" value="关闭" class="btn" />
			</td>
		</tr>
	</table>
</form>
<script>start_ajax_edit_order_status();</script>