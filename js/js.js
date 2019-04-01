var start_refresh;
var wait_status = true;
$(document).ready(function() {
	$('#datepicker1').datepicker({dateFormat: "yy-mm-dd"});
	$('#datepicker2').datepicker({dateFormat: "yy-mm-dd"});
	$('#delivery_time').datepicker({dateFormat: "yy-mm-dd", minDate:0});
	$('#result_delivery_time').datepicker({dateFormat: "yy-mm-dd"});
	$('#order_start_time1').datepicker({dateFormat: "yy-mm-dd"});
	$('#order_start_time2').datepicker({dateFormat: "yy-mm-dd"});
	$('#search_member_id').change(function(){
		get_customer();
	});
	$('#search_customer').keyup(function(){
		get_customer();
	});
	$('#search_material').keyup(function(){
		get_material();
	});
	if($('#fileupload')[0]){
		var url = window.location.hostname === 'blueimp.github.io' ?
					'//jquery-file-upload.appspot.com/' : 'server/php/';

		$('#fileupload').fileupload({
			url: url,
			dataType: 'json',
			autoUpload: true,
			success: function (e) {
				$.each(e.files, function (index, file) {
					$('<p/>').text(file.name).appendTo('#files');
					$('#img').val(file.name);
				});
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
		//zip file upload
		$('#zipfileupload').fileupload({
			url: 'server/php/zip_upload.php',
			dataType: 'json',
			autoUpload: true,
			success: function (e) {
				if(e.code==1)
				{
					alert(e.msg);
					$('#zip_progress .progress-bar').css(
						'width',
						'0%'
					);
				}
				else
				{
					//$('<p/>').text(e.data.name).appendTo('#old_files');
					str = "<p>"+e.data.name+"&nbsp&nbsp&nbsp&nbsp<a href='javascript:void(0)'  onclick=del_attch('"+e.data.path+"')>删除</a></p>";
					$('#old_files').html(str);
					$('#zip_path').val(e.data.path);
				}
				
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#zip_progress .progress-bar').css(
					'width',
					progress + '%'
				);

			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	}
	if(no_refresh == 1){
		start_refresh = setTimeout(function(){location.reload();},10000);
		$(document).mousemove(function(e){
			con_refresh();
		});
		$('input[type="checkbox"]').click(function(){
			wait_status = true;
			$('input[type="checkbox"]').each(function(){
				if($(this).attr("checked")=='checked'){
					wait_status = false;
					return false;
				}
			});
			con_refresh();
		});
	}



});
function member_ch_mater(){
	var g_id = $('#group_id').val();
	if(g_id == 2 || g_id == 4){
		$('#material').show();
	}else{
		$('#material').hide();
	}
}
function con_refresh(){
	clearTimeout(start_refresh);
	if(wait_status){
		start_refresh = setTimeout(function(){location.reload();},10000);
	}
}

function del_attch(path)
{
	if(confirm("确定要删除吗？")){
		$.ajax({
			cache: true,
			type: "get",
			dataType: "json",
			url:'attch.php?act=del_attch&path='+path,
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(e) {
				if(e.code == 0){
					$('#zip_progress .progress-bar').css(
						'width',
						'0%'
					);
					$('#old_files').html('');
					$('#zip_path').val('');
				}
			}
		});
		
	}else{
		return false;
	}
}
function del(){
	if(confirm("确定要删除吗？")){
		return true;
	}else{
		return false;
	}
}

function all_select(aid,sid){
	// alert($(aid).attr("checked"));
	if($(aid).attr("checked")=='checked'){
		$(sid).attr("checked",true);
	}else{
		$(sid).attr("checked",false);
	}
}

function edit_order_status(step, order_id){
	var all_pro = 0;
    var part = 0;
    order_ids =[];
	if(order_id == 'all'){

        $('.ids:checked').each(function(){
			order_ids.push($(this).val()); 
		});
		all_pro = 1;
		order_id = order_ids.join();
	}
	var step_txt = '修改生产状态';
	if(step == 2) step_txt = '修改发货状态';
	if(step == 3) step_txt = '修改订单状态';
    if(step == 4) step_txt = '修改金额';
    if(step == 5 ) {
    	if (order_ids.length !=1)
		{
			alert('只能选择一个订单！');
			return false;
		}
        all_pro = 0;
        step_txt = '部分上机';
        part = 1;
	}
	no_refresh = true;
	ui.box.load('ajax.php?act=edit_order_status&order_id=' + order_id + '&all_pro=' + all_pro+'&part='+part+'&step='+step, step_txt, null);
	return false;
}
function edit_finance(finance_id){

    var step_txt = '修改还款信息';
	fin_ids =[];
	step = 1;
	if(finance_id == 'all' || finance_id == 'one'){
		step = 2;
		$('.ids:checked').each(function(){
			fin_ids.push($(this).val());
		});
		if(finance_id == 'one' ) {
			if (fin_ids.length !=1)
			{
				alert('只能选择一个订单！');
				return false;
			}
			step = 3;
		}
		finance_id = fin_ids.join();
	}

    no_refresh = true;
    ui.box.load('ajax.php?act=edit_finance&finance_id=' + finance_id +'&step='+step, step_txt, null);
    return false;
}

function edit_finance_by_order(order_id){

	var step_txt = '修改还款信息';
	step = 1;
	if (order_id==''){
		alert('请选择订单');
		return false;
	}


	no_refresh = true;
	ui.box.load('ajax.php?act=edit_finance&order_id=' + order_id +'&step='+step, step_txt, null);
	return false;
}

function start_ajax_edit_order_status(){
	$('#result_delivery_time').datepicker({dateFormat: "yy-mm-dd"});
}
function start_ajax_edit_order_finance_status(){
	$('#settle_time').datepicker({dateFormat: "yy-mm-dd"});
}

function get_material(){
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=get_material_select',
		data:{ck:$('#search_material').val()},// 你的formid
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {
			if(e.status == 1){
				$('#material_id option').remove();
				$.each(e.con, function(n, v){
					$('#material_id').append('<option value="">' + v.name + '</option>');
				});
			}
		}
	});
}

function get_customer(){
	var member_id = 0;
	if($('#search_member_id')[0]){
		member_id = $('#search_member_id').val();
	}
	var is_chang_order = $('#is_chang_order').val();
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=get_customer_select',
		data:{ck:$('#search_customer').val(), member_id:member_id,is_chang_order:is_chang_order},// 你的formid
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {
			if(e.status == 1){
				$('#customer_id option').remove();
                //$('#customer_id').append('<option value="0">所有客户</option>');
				$.each(e.con, function(n, v){
					$('#customer_id').append('<option value="' + v.customer_id + '">' + v.attr_name + '</option>');
				});
				if (typeof(is_chang_order) != "undefined")
				{
					var obj_val = Object.values(e.con);
					get_customer_pay_types(obj_val[0]['customer_id'])

                }
			}
		}
	});
}

function confirm_production_member(member_id)
{
    order_arr =[];
	$('.ids:checked').each(function(){
        order_arr.push($(this).val());
	});
	order_ids = order_arr.join();

    if (order_arr.length <1)
    {
        alert('请选择订单！');
        return false;
    }
    $.ajax({
        cache: true,
        type: "POST",
        dataType: "json",
        url:'ajax.php?act=confirm_production_member',
        data:{order_ids:order_ids, member_id: member_id},
        async: false,
        error: function(request) {
            alert("Connection error");
        },
        success: function(e) {
            if(e.status == 1){
                alert(e.msg);
                location.reload();

            }else{
                alert(e.msg);
            }
        }
    });
    return false;
}

function confirm_production_member(member_id)
{
	order_arr =[];
	$('.ids:checked').each(function(){
		order_arr.push($(this).val());
	});
	order_ids = order_arr.join();

	if (order_arr.length <1)
	{
		alert('请选择订单！');
		return false;
	}
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=confirm_production_member',
		data:{order_ids:order_ids, member_id: member_id},
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {
			if(e.status == 1){
				location.reload();

			}else{
				alert(e.msg);
			}
		}
	});
	return false;
}

function confirm_budong()
{
	order_arr = [];
	$('.ids:checked').each(function () {
		order_arr.push($(this).val());
	});
	order_ids = order_arr.join();

	if (order_arr.length < 1) {
		alert('请选择订单！');
		return false;
	}
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url: 'ajax.php?act=confirm_budong',
		data: {order_ids: order_ids},
		async: false,
		error: function (request) {
			alert("Connection error");
		},
		success: function (e) {
			if (e.status == 1) {
				location.reload();

			} else {
				alert(e.msg);
			}
		}
	});
	return false;
}
function do_order_status(obj){
    var production_status = $("#production_status").val();
    var sy_weight = $("#sy_weight").val();
	if (production_status==1 && sy_weight=='')
	{
		alert('请填写剩余重量');
		return false;
	}
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=do_order_status',
		data:$(obj).serialize(),
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {

			if(e.status == 1){
				ui.box.close();
				ui.showMessage(e.msg);

				location.reload();

			}else{
				ui.error(e.msg);
			}
		}
	});
	return false;
}
function do_order_finance_status(obj){
    var finance_status = $("#finance_status").val();
    var sk_price = $("#sk_price").val();
	var finance_no = $("#finance_no").val();
	if(finance_no=='')
	{
		alert('请填写回款编号');
		return false;
	}
    else if (finance_status==1 && sk_price=='')
    {
        alert('请填写回款金额');
        return false;
    }
	debugger;
    $.ajax({
        cache: true,
        type: "POST",
        dataType: "json",
        url:'ajax.php?act=do_order_finance_status',
        data:$(obj).serialize(),
        async: false,
        error: function(request) {
            alert("Connection error");
        },
        success: function(e) {

            if(e.status == 1){
                ui.box.close();
                ui.showMessage(e.msg);

                location.reload();

            }else{
				alert(e.msg);
            }
        }
    });
    return false;
}

$('#customer_id').change(function(){
    var is_chang_order = $('#is_chang_order').val();
    var customer_id = $("#customer_id").val();
    if (typeof(is_chang_order) != "undefined")
    {
    	get_customer_pay_types(customer_id)
    }
})

function reset_finance(finance_no)
{
	if(confirm("确定要撤回该笔回款吗？")) {
		$.ajax({
			cache: true,
			type: "POST",
			dataType: "json",
			url: 'ajax.php?act=reset_finance',
			data: {finance_no: finance_no},
			async: false,
			error: function (request) {
				alert("Connection error");
			},
			success: function (e) {

				if (e.status == 1) {
					ui.box.close();
					ui.showMessage(e.msg);

					location.reload();

				} else {
					alert(e.msg);
				}
			}
		});
	}
}

function reset_finance_by_order_id(order_id)
{
	if(confirm("确定要撤回该笔回款吗？")) {
		$.ajax({
			cache: true,
			type: "POST",
			dataType: "json",
			url:'ajax.php?act=reset_finance_by_order_id',
			data:{order_id:order_id},
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(e) {

				if(e.status == 1){
					ui.box.close();
					ui.showMessage(e.msg);

					location.reload();

				}else{
					alert(e.msg);
				}
			}
		});
	}
}
function dc(){
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=order_excel',
		data:$('#order_search').serialize(),// 你的formid
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {
			if(e.status == 1){
				location.href = e.fileurl;
			}else{
				alert(e.msg);
			}
		}
	});
}

function view_kz(v){
	if(v == 1){
		$('#sy_weight').show();
	}else{
		$('#sy_weight').hide();
	}
}

function view_sk_price(v){
    if(v == 1){
        $('#sk_price').show();
    }else{
        $('#sk_price').hide();
    }
}
//获取客户付款方式
function get_customer_pay_types(customer_id)
{
    $.ajax({
        cache: true,
        type: "POST",
        dataType: "json",
        url:'ajax.php?act=get_customer_pay_types',
        data:{customer_id:customer_id},
        async: false,
        error: function(request) {
            alert("Connection error");
        },
        success: function(e) {
            if(e.status == 1){
                $('#pay_type option').remove();
                $.each(e.con, function(n, v){
                    $('#pay_type').append('<option value="' + v + '">' + v + '</option>');
                });
            }else{
                alert(e.msg);
            }
        }
    });
}

function checkboxOnclick(checkbox){
    var parent_tr = checkbox.parentNode.parentNode;
    var parent_tr_color = '';
    if ( checkbox.checked == true){
        parent_tr_color="#999999";
    }
    parent_tr.style.backgroundColor=parent_tr_color;

}
function ajax_budong(order_id)
{
    if (order_id=='') {
        alert('请选择订单！');
        return false;
    }
	//wait_status = false;
	//con_refresh();
    ui.box.load('ajax.php?act=do_budong&order_id=' + order_id);
    return false;
}

function ajax_do_budong(obj){
	
	$.ajax({
		cache: true,
		type: "POST",
		dataType: "json",
		url:'ajax.php?act=upload_do_budong',
		data:$(obj).serialize(),
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(e) {

			if(e.status == 1){
				no_refresh = false;
				ui.box.close();
				ui.showMessage(e.msg);

				location.reload();

			}else{
				ui.error(e.msg);
			}
		}
	});
	return false;
} 
function show_bu_dong_img(obj){
	$(obj).hide();
	$(obj).next().show()
	return false;
}

window.onload=function (){
    var customer_id = $("#customer_id").val();
    var order_id = $("#order_id").val();

    if (typeof(customer_id) != "undefined" && order_id=='0') {
        get_customer_pay_types(customer_id);
    }
}
