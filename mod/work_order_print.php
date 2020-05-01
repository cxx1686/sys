<?$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$work_order = $index->get_work_order($id);

if($index->check){

	$index->do_error();
}else{
    $index->change_work_order_status($id);
    ?>
    <div id="print_content">
	<table class="list">
		<caption>工单</caption>
        <tr>
            <td >工单号</td>
            <td><input type="text" id="search_material" disabled class="text" value="<?=$work_order['id']?>" /></td>
            <td id="search_material">订单号</td>
            <td><input type="text" id="search_material" disabled class="text" value="<?=$work_order['order_id']?>" /></td>

        </tr>
        <tr>
			<td>负责人</td>
            <td>
                <input type="text" id="search_material" disabled class="text" value="<?=$index->get_member_name($work_order['member_id'])?>" />
            </td>
            <td>类别</td>
            <td >
                <input type="text" id="search_material" disabled class="text" value="<?=$work_order['wo_type']?>" />
            </td>
		</tr>
        <tr>
            <td>备注</td>
            <td colspan="3"><?=$work_order['remarks']?>
            </td>
        </tr>

		<tr>
            <td colspan="4" align="center">
            <img src="<?=$work_order['img'] ? "server/php/files/{$work_order['img']}":'' ?>" align="middle"  width="500px" height="500px">
            </td>
		</tr>
	</table>
    <!--
    <table class="list">
        <tr>
            <th>客户</th>
            <th>业务员</th>
            <th>价格</th>
            <th>付款方式</th>
            <th>材料</th>
            <th>上机状态</th>
            <th>发货时间</th>
            <th>下单时间</th>
        </tr>
        <tr>

            <td><?=$index->get_customer_name($work_order['customer_id'])?></td>
            <td><?=$index->get_member_name($work_order['order_member_id'])?></td>
            <td><?=$work_order['price']?></td>
            <td><?=$work_order['pay_type']?></td>
            <td><?=$index->get_material_name($work_order['material_id'])?></td>
            <td><?=$work_order['machine_id']==0 ? '待上机' : $index->get_machine_name($work_order['machine_id'])?></td>

            <td><?=$work_order['result_delivery_time'] ? (date('Y-m-d', $work_order['result_delivery_time']) . ' ' . (date('H', $work_order['result_delivery_time']) >= 18 ? '晚' : '<font color=orange>早</font>')) : ($work_order['delivery_time'] > time() ? (date('Y-m-d', $work_order['delivery_time']) . ' ' . (date('H', $work_order['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) : '<font color=red>' . (date('Y-m-d', $work_order['delivery_time']) . ' ' . (date('H', $work_order['delivery_time']) >= 18 ? '晚' : '<font color=green>早</font>')) . '</font>')?></td>
            <td><?=date('Y-m-d H:i:s', $work_order['order_time'])?></td>
        </tr>
    </table>
    -->
    </div>
<?}?>
<script type="text/javascript" src="js/jquery.js"></script>
<script >
    $(document).ready(function() {
        prnhtml = $("#print_content").html();
        window.document.body.innerHTML=prnhtml;
        window.print()
    });
</script>
