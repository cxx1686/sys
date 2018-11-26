<?if(isset($_GET['member_id'])){
	$member_info = $index->get_member($_GET['member_id']);
	$member_info['material_id'] = $member_info['material_id'] ? explode(',', $member_info['material_id']) : array();
}if($index->check){
	$index->do_error();
}else{
	$material = $index->material_list(false);
?>
<form action="?act=edit_member" method="post">
	<table class="form">
		<caption><?=isset($_GET['member_id']) ? '修改客户资料' : '添加新客户';?></caption>
		<input type="hidden" name="member_id" value="<?=isset($member_info) ? $member_info['member_id'] : 0?>" />
		<tr>
			<th>用户名</th>
			<td><input type="text" name="username" class="text" value="<?=isset($member_info) ? $member_info['username'] : ''?>" /></td>
		</tr>
		<tr>
			<th>密码</th>
			<td><input type="text" name="password" class="text" value="<?=isset($member_info) ? $member_info['password'] : ''?>" /></td>
		</tr>
		<tr>
			<th>用户组</th>
			<td><select name="group_id" id="group_id" onchange="member_ch_mater()">
				<option value="1"<?=isset($member_info) && $member_info['group_id'] == 1 ? ' selected' : ''?>>业务员</option>
				<option value="2"<?=isset($member_info) && $member_info['group_id'] == 2 ? ' selected' : ''?>>技术员</option>
				<option value="4"<?=isset($member_info) && $member_info['group_id'] == 4 ? ' selected' : ''?>>跟单员</option>
				<option value="5"<?=isset($member_info) && $member_info['group_id'] == 5 ? ' selected' : ''?>>财务</option>
			</select></td>
		</tr>
		<tr id="material"<?if(isset($member_info) && in_array($member_info['group_id'], array(2, 4))){}else{echo ' style="display:none;"';}?>>
			<th>材料</th>
			<td>
				<?foreach($material as $v){?>
				<input type="checkbox" id="material_<?=$v['material_id']?>" name="material_id[]"<?=isset($member_info) && in_array($v['material_id'], $member_info['material_id']) ? ' checked' : '';?> value="<?=$v['material_id']?>" />
				<label for="material_<?=$v['material_id']?>"><?=$v['name']?></label>
				<?}?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="<?=isset($_GET['member_id']) ? '修改' : '添加';?>" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>