<div class="search_bar">
	<form action="" method="get">
		<input type="hidden" name="mod" value="member" />
		<ul>
			<li>
				<select name="group_id">
					<option value="0">所有员工</option>
					<option value="1"<?=isset($_GET['group_id']) && intval($_GET['group_id']) == 1 ? ' selected' : ''?>>业务员</option>
					<option value="2"<?=isset($_GET['group_id']) && intval($_GET['group_id']) == 2 ? ' selected' : ''?>>技术员</option>
				</select>
				<input type="text" name="start_time" id="datepicker1" value="<?=isset($_GET['start_time']) ? $_GET['start_time'] : '';?>" readonly="readonly" placeholder="查询开始时间" />
				-
				<input type="text" name="end_time" id="datepicker2" value="<?=isset($_GET['end_time']) ? $_GET['end_time'] : '';?>" readonly="readonly" placeholder="查询截止时间" />
				<input type="text" name="keyword" value="<?=isset($_GET['keyword']) ? $_GET['keyword'] : '';?>" placeholder="输入用户名查询" />
			</li>
		</ul>
		<input type="submit" value="查询" />
	</form>
	<ul class="btns">
		<a href="?mod=edit_member" class="btn2">添加新员工</a>
	</ul>
	<ul class="clear"></ul>
</div>
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>员工列表</caption>
	<tr>
		<th>用户名</th>
		<th>密码</th>
		<th>员工组</th>
		<th>注册时间</th>
		<th>操作</th>
	</tr>
	<?$list = $index->member_list();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td><?=$v['username']?></td>
		<td><?=$v['password']?></td>
		<td><?=$index->group_name[$v['group_id']];?></td>
		<td><?=date('Y-m-d H:i:s', $v['reg_time'])?></td>
		<td>
			<a class="btn" href="?mod=edit_member&member_id=<?=$v['member_id']?>">修改</a>
			<a class="btn" href="?act=del_member&member_id=<?=$v['member_id']?>" onclick="return del();">删除</a>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="5" align="right"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></td>
	</tr>
	<?}else{?>
	<tr><td colspan="5" class="no_info">没有员工，请先添加新员工！</td></tr>
	<?}?>
</table>