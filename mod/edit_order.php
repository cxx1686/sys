<?$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$index->order_edit_jurisdiction($order_id);
$order_info = $index->get_order($order_id);
if($index->check){
	$index->do_error();
}else{
    ?>
<form action="?act=edit_order" method="post" >
	<table class="form">
		<caption><?=isset($_GET['order_id']) ? '修改订单' : '添加新订单';?></caption>
		<input type="hidden" id="order_id" name="order_id" value="<?=isset($_GET['order_id']) ? $_GET['order_id'] : 0?>" />
        <input type="hidden" id='is_chang_order' value="1" />
        <tr>
			<th>客户</th>
			<td><select name="customer_id" id="customer_id">
			<?@$customer_select = $index->get_customer_select($order_info['member_id']);
			foreach($customer_select as $v){?>
			<option value="<?=$v['customer_id']?>"<?=isset($order_info['customer_id']) && $order_info['customer_id'] == $v['customer_id'] ? ' selected' : ''?>><?=$v['attr_name']?></option>
			<?}?>
			</select><?if(in_array($index->member_info['group_id'], array(4, 9))){?>
			<select name="member_id" id="search_member_id">
				<option value="0">业务员</option>
				<?$member_select = $index->get_member_select(0);
				foreach($member_select as $v){?>
				<option value="<?=$v['member_id']?>"<?=isset($order_info) && $order_info['member_id'] == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
				<?}?>
			</select><?}?>
			<input type="text" class="text" id="search_customer" placeholder="搜索客户" />
			</td>
		</tr>
		<tr>
			<th>克重</th>
			<td><input type="text" name="weight" class="text" value="<?=isset($order_info) ? $order_info['weight'] : ''?>" /></td>
		</tr>
		<tr>
			<th>价格</th>
			<td><input type="text" name="price" class="text" value="<?=isset($order_info) ? $order_info['price'] : ''?>" /></td>
		</tr>
		<tr>
			<th>付款方式</th>
			<td>
               <select name="pay_type" id="pay_type">
              <?$pay_types = $index->get_customer_pay_types($order_info['customer_id']);
              foreach($pay_types as $v){?>
                  <option value="<?=$v?>"<?=isset($order_info) && $order_info['pay_type'] == $v ? ' selected' : ''?>><?=$v?></option>
              <?}?>
               </select>

			</td>
		</tr>
		<tr>
			<th>材料</th>
			<td><select name="material_id" id="material_id">
				<option value="0">选择材料</option>
				<?$material_select = $index->get_material_select();
				if($material_select){
					foreach($material_select as $v){?>
				<option value="<?=$v['material_id']?>"<?=$order_info['material_id'] == $v['material_id'] ? ' selected' : ''?>><?=$v['name']?></option>
					<?}
				}?>
			</select><input type="text" class="text" id="search_material" placeholder="搜索材料" /></td>
		</tr>
		<tr>
			<th>发货时间</th>
			<td><input type="text" name="delivery_time" id="delivery_time" class="text" value="<?=isset($order_info) ? date('Y-m-d', $order_info['delivery_time']) : ''?>" /><?=$index->get_datetime($order_info['delivery_time'])?></td>
		</tr>
		<tr>
			<th>图片</th>
			<td>
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span>Select files...</span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="fileupload" type="file" name="files[]" multiple>
				</span>
				<input type="hidden" id="img" name="img" value="<?=isset($order_info) ? $order_info['img'] : ''?>" />
				<br />
				<br />
				<!-- The global progress bar -->
				<div id="progress" class="progress">
					<div class="progress-bar progress-bar-success"></div>
				</div>
				<!-- The container for the uploaded files -->
				<div id="files" class="files"><?=isset($order_info) && $order_info['img'] ? '<a href="server/php/files/' . $order_info['img'] . '" target="_blank">' . $order_info['img'] . '</a>' : ''?></div>
			</td>
		</tr>
		<tr>
			<th>压缩包附件</th>
			<!-- <td><input type="file" name="ufile" id="ufile"/></br><?=$order_info['zip_path'] ? '<a href=' . $order_info['zip_path'] . ' target="_blank">附件</a>' : ''?></td> -->
			<td>
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span>添加附件...</span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="zipfileupload" type="file" name="zipfile" multiple>
				</span>
				<input type="hidden" id="zip_path" name="zip_path" value="<?=isset($order_info['zip_path']) ? $order_info['zip_path'] : ''?>" />
				<br />
				<br />
				<!-- The global progress bar -->
				<div id="zip_progress" class="progress">
					<div class="progress-bar progress-bar-success"></div>
				</div>
				<!-- The container for the uploaded files -->
				<div id="old_files" class="old_files"><?=isset($order_info) && $order_info['zip_path'] ? '<a href="' . $order_info['zip_path'] . '" target="_blank">'.array_pop(explode("/",$order_info['zip_path'])).'</a>' : ''?></div>
			</td>
			
		</tr>
		<tr>
			<th>备注</th>
			<td><textarea type="text" name="remarks" class="text"><?=isset($order_info) ? $order_info['remarks'] : ''?></textarea></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="<?=isset($_GET['order_id']) ? '修改' : '添加';?>" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>