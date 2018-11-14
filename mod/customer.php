<div class="search_bar">
	<form action="" method="get">
		<input type="hidden" name="mod" value="customer" />
		<ul>
			<li>
				<?if(in_array($index->member_info['group_id'], array(4,9))){?>
				<select name="member_id">
					<option value="0">所有业务员</option>
					<?$member_select = $index->get_member_select(1);
					foreach($member_select as $v){?>
					<option value="<?=$v['member_id']?>"<?=isset($_GET['member_id']) && intval($_GET['member_id']) == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
					<?}?>
				</select>
				<?}?>
				<input type="text" name="start_time" id="datepicker1" value="<?=isset($_GET['start_time']) ? $_GET['start_time'] : '';?>" readonly="readonly" placeholder="查询开始时间" />
				-
				<input type="text" name="end_time" id="datepicker2" value="<?=isset($_GET['end_time']) ? $_GET['end_time'] : '';?>" readonly="readonly" placeholder="查询截止时间" />
				<input type="text" name="keyword" value="<?=isset($_GET['keyword']) ? $_GET['keyword'] : '';?>" placeholder="输入客户信息查询" />
			</li>
		</ul>
		<input type="submit" value="查询" />
	</form>
	<ul class="btns">
		<?if(in_array($index->member_info['group_id'], array(4,9))){?>
		<a href="?mod=customer" class="btn2">正常客户</a>
		<a href="?mod=customer&type=1" class="btn2">已删除客户</a>
		<?}?>
		<a href="?mod=edit_customer" class="btn2">添加新客户</a>
	</ul>
	<ul class="clear"></ul>
</div>
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>客户列表</caption>
	<tr>
		<th>简称</th>
		<th>全称</th>
		<th>地址</th>
		<th>联系人</th>
		<th>电话</th>
		<th>QQ</th>
		<th>业务员</th>
		<th>添加时间</th>
		<th>操作</th>
	</tr>
	<?$list = $index->customer_list();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
		<td><?=$v['attr_name']?></td>
		<td><?=$v['name']?></td>
		<td><?=$v['address']?></td>
		<td><?=$v['contact']?></td>
		<td><?=$v['phone']?></td>
		<td><?=$v['qq']?></td>
		<td><?=$index->get_member_name($v['member_id'])?></td>
		<td><?=date('Y-m-d H:i:s', $v['join_time'])?></td>
		<td>
			<?if(isset($_GET['type']) && $_GET['type'] == 1 && in_array($index->member_info['group_id'], array(4,9))){?>
			<a class="btn" href="?act=re_customer&customer_id=<?=$v['customer_id']?>">还原</a>
			<?if($index->member_info['group_id']==9){?>
			<a class="btn" href="?act=res_del_customer&customer_id=<?=$v['customer_id']?>" onclick="return del();">彻底删除</a>
			<?}
			}else{?>
			<a class="btn" href="?mod=edit_customer&customer_id=<?=$v['customer_id']?>">修改</a>
			<a class="btn" href="?act=del_customer&customer_id=<?=$v['customer_id']?>" onclick="return del();">删除</a>
			<?}?>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="8" align="right"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></td>
	</tr>
	<?}else{?>
	<tr><td colspan="8" class="no_info">没有客户，请先添加新客户！</td></tr>
	<?}?>
</table>