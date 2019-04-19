<form class="box_form" id="ajax_do_budong" >
	<table class="form">
		<input type="hidden" name="order_id" value="<?=isset($_GET['order_id']) ? $_GET['order_id'] : 0?>" />
		
		<tr>
			<th>图片</th>
			<td>
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<span>Select files...</span>
					<!-- The file input field used as target for the file upload widget -->
					<input id="fileupload" type="file" name="files[]" multiple>
				</span>
				<div id="budong_imgs">
					<!-- <input type="hidden" id="img" name="img[]" value="<?=isset($order_info) ? $order_info['bu_dong_img'] : ''?>" />
					 -->
					 <?$bu_dong_img_arr = explode(',',$order_info['bu_dong_img']);  if(!empty($bu_dong_img_arr[0])){foreach($bu_dong_img_arr as $i){ $temp_arr = explode('.',$i);?>
						<input type="hidden" id="<?=$temp_arr[0]?>" name="img[]" value="<?=$i?>" />
					<?}}?>
				</div>
				<br />
				<br />
				<!-- The global progress bar -->
				<div id="progress" class="progress">
					<div class="progress-bar progress-bar-success"></div>
				</div>
				<!-- The container for the uploaded files -->
				<div id="files" class="files">
					<?$bu_dong_img_arr = explode(',',$order_info['bu_dong_img']); if(!empty($bu_dong_img_arr[0])){foreach($bu_dong_img_arr as $i){ $temp_arr = explode('.',$i);?>
						<p><?=$i?>&nbsp&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)'  data_id="<?=$temp_arr[0]?>" onclick="budong_del(this)">删除</a></p>
					<?}}?>
				</div>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="button" onclick="ajax_do_budong('#ajax_do_budong');" value="上传" class="btn" />
				<input type="button" onclick="ui.box.close();" value="关闭" class="btn" />
			</td>
		</tr>
	</table>
</form>
<script src="js/jquery.fileupload.js"></script>
<script >
$(document).ready(function() {
	var url = window.location.hostname === 'blueimp.github.io' ?
					'//jquery-file-upload.appspot.com/' : 'server/php/';
		var file_id =1;
		$('#fileupload').fileupload({
			url: url,
			dataType: 'json',
			autoUpload: true,
			success: function (e) {
				file_id++;
				var flag_file = 'file_'+file_id;
				$.each(e.files, function (index, file) {
					str = "<p>"+file.name+"&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)'  data_id="+flag_file+" onclick=budong_del(this)>删除</a></p>";
					str2= "<input type='hidden' name='img[]' id="+flag_file+" value="+file.name+">";
					$('#files').append(str);
					$("#budong_imgs").append(str2);
					
				});
				$('#progress .progress-bar').css(
						'width',
						'0%'
					);
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
				);

			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
		});
	function budong_del(obj){
		debugger;
		if(confirm("确定要删除吗？")){
			$(obj).parent().remove();
			var temp_id = $(obj).attr('data_id');
			$("#"+temp_id).remove();
			$(obj).remove();
		}else{
			return false;
		}
	}
	</script>