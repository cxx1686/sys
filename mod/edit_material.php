<?if(isset($_GET['material_id'])){
	$material_info = $index->get_material($_GET['material_id']);
}if($index->check){
	$index->do_error();
}else{?>
<form action="?act=edit_material" method="post">
	<table class="form">
		<caption><?=isset($_GET['material_id']) ? '修改机器' : '添加新机器';?></caption>
		<input type="hidden" name="material_id" value="<?=isset($material_info) ? $material_info['material_id'] : 0?>" />
		<tr>
			<th>材料名称</th>
			<td><input type="text" name="name" class="text" value="<?=isset($material_info) ? $material_info['name'] : ''?>" /></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="<?=isset($_GET['material_id']) ? '修改' : '添加';?>" class="btn" />
				<input type="button" onclick="history.back();" value="后退" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>