<div class="search_bar">
	<ul class="btns">
		<a href="?mod=edit_machine" class="btn2">添加新机器</a>
	</ul>
	<ul class="clear"></ul>
</div>
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>机器列表</caption>
	<tr>
		<th>机器名称</th>
		<th>操作</th>
	</tr>
	<?$list = $index->machine_list();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td><?=$v['name']?></td>
		<td>
			<a class="btn" href="?mod=edit_machine&machine_id=<?=$v['machine_id']?>">修改</a>
			<a class="btn" href="?act=del_machine&machine_id=<?=$v['machine_id']?>" onclick="return del();">删除</a>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="2" align="right"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></td>
	</tr>
	<?}else{?>
	<tr><td colspan="2" class="no_info">没有机器，请先添加新机器！</td></tr>
	<?}?>
</table>