<?$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$index->order_edit_jurisdiction($order_id);
$order_info = $index->get_order($order_id);
if($index->check){
	$index->do_error();
}else{
    ?>
<form action="?act=add_work_order" method="post" >
	<table class="form">
		<caption>添加工单</caption>
        <tr>
            <th>订单号</th>
            <td><input type="text" id="order_id" name="order_id" class="text" value="<?=isset($_GET['order_id']) ? $_GET['order_id'] : ''?>" /></td>
        </tr>
        <tr>
			<th>负责人</th>
            <td>
			<select name="member_id" >
				<option value="0">业务员</option>
				<?$member_select = $index->get_member_select(0);
				foreach($member_select as $v){?>
				<option value="<?=$v['member_id']?>"<?=isset($order_info) && $order_info['member_id'] == $v['member_id'] ? ' selected' : ''?>><?=$v['username']?></option>
				<?}?>
			</select>
			</td>
		</tr>
        <tr>
            <th>类别</th>
            <td>
                <select name="wo_type" id="wo_type">
                    <?$wo_types = $index->work_order_types();
                    foreach($wo_types as $v){?>
                        <option value="<?=$v?>"><?=$v?></option>
                    <?}?>
                </select>

            </td>
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
			<th>备注</th>
			<td><textarea type="text" name="remarks" class="text"><?=isset($order_info) ? $order_info['remarks'] : ''?></textarea></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" value="添加" class="btn" />
			</td>
		</tr>
	</table>
</form>
<?}?>