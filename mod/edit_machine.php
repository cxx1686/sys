<?if(isset($_GET['machine_id'])){
	$machine_info = $index->get_machine($_GET['machine_id']);
}if($index->check){
	$index->do_error();
}else{?>
<form action="?act=edit_machine" method="post">
	<table class="form">
		<caption><?=isset($_GET['machine_id']) ? '修改机器' : '添加新机器';?></caption>
		<input type="hidden" name="machine_id" value="<?=isset($machine_info) ? $machine_info['machine_id'] : 0?>" />
		<tr>
			<th>机器名称</th>
			<td><input type="text" name="name" class="text" value="<?=isset($machine_info) ? $machine_info['name'] : ''?>" /></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="<?=isset($_GET['machine_id']) ? '修改' : '添加';?>" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>