<form class="box_form" id="ajax_order_finance_status">
	<table class="form">
		<input type="hidden" name="finance_id" value="<?=isset($_GET['finance_id']) ? $_GET['finance_id'] : 0?>" />
		<tr>
			<th>还款状态</th>
			<td>
				<?php if ($_GET['step']==2) {?>
					<input type="hidden" name="finance_status" value="2" />
					全部回款
				<?php }elseif($_GET['step']==3){?>
					<input type="hidden" name="finance_status" value="1" />
					<input style="width:65px;" type="text" name="sy_price" class="text" id="sy_price" value="" placeholder="剩余金额" />
					部分回款
				<?php }else{?>
					<select name="finance_status" onchange="view_sy_price(this.value);">
						<option value="2">全收款</option>
						<option value="1"<?=$should_gain['total_sy_price'] >0 ? ' selected' : ''?>>部分收款</option>
					</select>
					<input style="width:65px;<?if($should_gain['total_sy_price'] == 0){?>display:none;<?}?>" type="text" name="sy_price" class="text" id="sy_price" value="" placeholder="剩余金额" />
				<?php }?>
			</td>
		</tr>
		<tr>
			<th>回款编号</th>
			<td><input style="width:65px;" type="text" id="finance_no" class="text" name="finance_no" value="<?=$should_gain['finance_no'] ?>" /></td>
		</tr>
		<tr>
			<th>回款时间</th>
			<td>
				<input type="text" style="width:90px;" class="text" id="settle_time" name="settle_time" placeholder="默认为当前时间" value="<?=$order_info['settle_time'] ? date('Y-m-d', $order_info['settle_time']) : ''?>" />

			</td>
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
<script>start_ajax_edit_order_finance_status();</script>