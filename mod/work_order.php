<div class="search_bar">
	<form action="" method="get" id="order_search">
		<input type="hidden" name="mod" value="work_order" />
		<ul>
			<li>
				<select name="member_id" >
					<option value="0">负责人</option>
					<?$member_select = $index->get_member_select(1);
					foreach($member_select as $v){?>
					<option value="<?=$v['member_id']?>"<?=isset($_GET['member_id']) && intval($_GET['member_id']) == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
					<?}?>
				</select>
                <!--
                <input type="text" name="order_start_time" id="datepicker1" value="<?=isset($_GET['order_start_time']) ? $_GET['order_start_time'] : '';?>" readonly="readonly" placeholder="添加开始日期" />
                -
                <input type="text" name="order_end_time" id="datepicker2" value="<?=isset($_GET['order_end_time']) ? $_GET['order_end_time'] : '';?>" readonly="readonly" placeholder="添加截止日期" />
                -->
				<select name="wo_type">
					<option value="0">类别</option>
					<?$wo_types = $index->work_order_types();
					if($wo_types){
						foreach($wo_types as $v){?>
					<option value="<?=$v?>"<?=isset($_GET['wo_type']) && $_GET['wo_type'] == $v? ' selected' : ''?>><?=$v?></option>
						<?}
					}?>
				</select>

                <select name="status">
                    <option value="-1">状态</option>
                    <?$wo_status = $index->get_work_order_status();
                    if($wo_status){
                        foreach($wo_status as $k=>$v){?>
                            <option value="<?=$k?>"
                                <?
                                $_GET['status'] = empty($_GET['status']) ? 1 : $_GET['status'];
                                if($_GET['status'] == $k) {
                                    echo ' selected';
                                } else {
                                    echo ' ';
                                }
                                ?>
                                >
                                <?=$v?>
                            </option>
                        <?}
                    }?>
                </select>

			</li>
		</ul>
		<input type="submit" value="查询" />
	</form>
	<ul class="btns">

		<a href="?mod=add_work_order" class="btn2">添加新工单</a>
	</ul>
	<ul class="clear"></ul>
</div>
<table class="list" cellpadding="0" cellspacing="0" border="0">
	<caption>订单列表</caption>
	<tr>
        <th>工单号</th>
        <th>订单号</th>
        <th>负责人</th>
        <th>类别</th>
        <th>图片</th>
        <th>备注</th>
        <th>工单创建时间</th>
		<th>客户</th>
		<th>业务员</th>
		<th>价格</th>
		<th>付款方式</th>
		<th>材料</th>
		<th>上机状态</th>
		<th>发货时间</th>
		<th>下单时间</th>
        <th>工单状态</th>
		<th>操作</th>
	</tr>
	<?$list = $index->work_order_list();
	if($list){
		foreach($list as $n=>$v){?>
	<tr>
        <td><?=$v['id']?></td>
        <td><?=$v['order_id']?></td>
        <td><?=$index->get_member_name($v['member_id'])?></td>
        <td><?=$v['wo_type']?></td>
        <td><?=$v['img'] ? '<a href="server/php/files/' . $v['img'] . '" target="_blank">查看</a>' : ''?></td>
        <td><?=$v['remarks']?></td>
        <td><?=date('Y-m-d H:i:s', $v['add_time'])?></td>
		<td><?=$index->get_customer_name($v['customer_id'])?></td>
		<td><?=$index->get_member_name($v['order_member_id'])?></td>
		<td><?=$v['price']?></td>
		<td><?=$v['pay_type']?></td>
        <td><?=$index->get_material_name($v['material_id'])?></td>
		<td><?=$v['machine_id']==0 ? '待上机' : $index->get_machine_name($v['machine_id'])?></td>

		<td><?=$v['result_delivery_time'] ? (date('Y-m-d', $v['result_delivery_time']) . ' ' . (date('H', $v['result_delivery_time']) >= 18 ? '晚' : '<font color=orange>早</font>')) : ($v['delivery_time'] > time() ? (date('Y-m-d', $v['delivery_time']) . ' ' . (date('H', $v['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) : '<font color=red>' . (date('Y-m-d', $v['delivery_time']) . ' ' . (date('H', $v['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) . '</font>')?></td>
		<td><?=date('Y-m-d H:i:s', $v['order_time'])?></td>
        <td><?=$index->get_work_order_status_cn($v['status'])?></td>

		<td>
            <a class="btn" href="?mod=work_order_print&id=<?=$v['id']?>" target="_blank">打印</a>
		</td>
	</tr>
	<?}?>
	<tr>
		<td colspan="16">
			<div style="float:right;"><? $subPages=new SubPages($index->pagesize,$index->recordcount,10,1);?></div>
		</td>
	</tr>
	<?}else{?>
	<tr><td colspan="16" class="no_info">没有订单！</td></tr>
	<?}?>
</table>