<form class="box_form" id="ajax_order_finance_status">
	<table class="form">
		<input type="hidden" name="finance_id" value="<?=isset($_GET['finance_id']) ? $_GET['finance_id'] : 0?>" />
		<tr>
			<th>还款状态</th>
			<td>
				<select name="finance_status" onchange="view_sy_price(this.value);">
					<option value="0">待收款</option>
					<option value="1"<?=$should_gain['total_sy_price'] >0 ? ' selected' : ''?>>部分收款</option>
					<option value="2">全收款</option>
				</select>
				<input style="width:65px;<?if($should_gain['total_sy_price'] == 0){?>display:none;<?}?>" type="text" name="sy_price" class="text" id="sy_price" value="<?=$should_gain['total_sy_weight'] ? $should_gain['total_sy_weight'] : ''?>" placeholder="剩余金额" />
				
			</td>
		</tr>
		<tr>
			<th>回款编号</th>
			<td><input style="width:65px;" type="text" class="text" name="finance_no" value="<?=$should_gain['finance_no'] ?>" /></td>
		</tr>
				
		<tr>
			<th></th>
			<td>
				<input type="button" onclick="do_order_finance_status('#ajax_order_finance_status');" value="修改" class="btn" />
				<input type="button" onclick="ui.box.close();" value="关闭" class="btn" />
			</td>
		</tr>
	</table>
</form>
<script>start_ajax_edit_order_status();</script>