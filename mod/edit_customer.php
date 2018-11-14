<?if(isset($_GET['customer_id'])){
	$customer_info = $index->get_customer($_GET['customer_id']);
}if($index->check){
	$index->do_error();
}else{?>
<form action="?act=edit_customer" method="post">
	<table class="form">
		<caption><?=isset($_GET['customer_id']) ? '修改客户资料' : '添加新客户';?></caption>
		<input type="hidden" name="customer_id" value="<?=isset($customer_info) ? $customer_info['customer_id'] : 0?>" />
		<input type="hidden" name="old_member_id" value="<?=isset($customer_info) ? $customer_info['member_id'] : 0?>" />
		<tr>
			<th>客户全称</th>
			<td><input type="text" name="name" class="text" value="<?=isset($customer_info) ? $customer_info['name'] : ''?>" /></td>
		</tr>
		<tr>
			<th>客户简称</th>
			<td><input type="text" name="attr_name" class="text" value="<?=isset($customer_info) ? $customer_info['attr_name'] : ''?>" /></td>
		</tr>
		<tr>
			<th>地址</th>
			<td><input type="text" name="address" class="text" value="<?=isset($customer_info) ? $customer_info['address'] : ''?>" /></td>
		</tr>
		<tr>
			<th>联系人</th>
			<td><input type="text" name="contact" class="text" value="<?=isset($customer_info) ? $customer_info['contact'] : ''?>" /></td>
		</tr>
		<tr>
			<th>电话</th>
			<td><input type="text" name="phone" class="text" value="<?=isset($customer_info) ? $customer_info['phone'] : ''?>" /></td>
		</tr>
		<tr>
			<th>QQ</th>
			<td><input type="text" name="qq" class="text" value="<?=isset($customer_info) ? $customer_info['qq'] : ''?>" /></td>
		</tr>
		<?if(in_array($index->member_info['group_id'], array(4,9))){?>
		<tr>
			<th>业务员</th>
			<td><select name="member_id">
				<?$member_select = $index->get_member_select(1);
				foreach($member_select as $v){?>
				<option value="<?=$v['member_id']?>"<?=isset($customer_info) && $customer_info['member_id'] == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
				<?}?>
			</select></td>
		</tr>
		<?}?>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="<?=isset($_GET['customer_id']) ? '修改' : '添加';?>" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>