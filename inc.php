<?
/*
 * Description: 内容操作类，包括...
 * Author: Prince
 * @since 2.0
*/

class index{

	// 数据操作对象
	var $db = object;
	

	// 分页记录总数
	var $recordcount = 0;
	var $count_weight = 0;
	var $count_price = 0;
	var $pagesize = 20;
	var $currentpage = 1;
	var $sum_list = 1;
	
	// 错误标识
	var $mid = 0;
	var $check = 0;
	
	// 正确标识
	var $ok = 0;
	
	// 错误数组
	var $error = array();
	var $header = 'index.php';
	
	// 会员信息
	var $member_info = array();
	var $group_name = array(1=>'业务员', 2=>'技术员', 4=>'跟单员', 5=>'财务', 9=>'管理员');
    var $pay_type_list = array('代收'=>'代收', '月结'=>'月结', '转账'=>'转账');
	var $un_mod = array(1=>array('member', 'edit_member', 'machine', 'edit_machine', 'order', 'material', 'edit_material'), 2=>array('member', 'edit_member', 'machine', 'edit_machine', 'customer', 'edit_customer', 'edit_order', 'order', 'material', 'edit_material'), 9=>array('member', 'edit_member', 'machine', 'edit_machine', 'order_1', 'order_2', 'order_3'), 9=>array('order_1', 'order_2', 'order_3'));
	var $un_act = array(1=>array('del_member', 'edit_member', 'del_machine', 'edit_machine', 'del_material', 'edit_material', 'res_del_order', 're_order', 'res_del_customer', 're_customer', 'order_excel', 'all_status'), 2=>array('del_member', 'edit_member', 'del_machine', 'edit_machine', 'del_material', 'edit_material', 'del_customer', 're_customer', 'res_del_customer', 'edit_customer', 'del_order', 'res_del_order', 're_order', 'edit_order', 'order_excel'), 4=>array('del_member', 'edit_member', 'del_machine', 'edit_machine', 'del_material', 'edit_material'), 9=>array());
    var $work_order_types = array('test1'=>'test1', 'test2'=>'test2', 'test3'=>'test3');

	// 初始化
	function __construct(){
		global $db;
		$this->db = $db;
		$this->currentpage = isset($_GET['currentpage']) && is_numeric($_GET['currentpage']) && $_GET['currentpage'] > 0 ? $_GET['currentpage'] : 1;
		if((isset($_GET['mod']) && $_GET['mod'] != 'login' && !$_SESSION['mid']) || (!isset($_GET['mod']) && !$_SESSION['mid'])){
			echo '<script>alert("请先登录！");location.href="?mod=login";</script>';
			exit;
		}else{
			$this->mid = $_SESSION['mid'];
			$this->member_info = $this->get_member($this->mid);
			if(isset($_GET['mod']) && $_GET['mod'] && in_array($_GET['mod'], $this->un_mod[$this->member_info['group_id']])){
				$this->check++;
				$this->error[] = '没有操作权限！';
			}
			if(isset($_GET['act']) && $_GET['act'] && in_array($_GET['act'], $this->un_act[$this->member_info['group_id']])){
				$this->check++;
				$this->error[] = '没有操作权限！';
			}
		}
	}
	
	function index(){
		$this->__construct($id);
	}
	
	function __destruct(){
		return true;
	}
	
	function do_error(){
		if($this->check){
			echo $this->msgbox($this->error, 'error');
		}elseif($this->ok){
			echo $this->msgbox($this->error, 'ok', $this->header);
		}
	}
	
	// 权限控制
	function jurisdiction($value = ''){
		if($value){
			$value = explode(',', $value);
			if(!in_array($this->member_info['group_id'], $value)){
				$this->check++;
				$this->error[] = '权限不足，禁止操作！';
			}
		}
	}
	
	function sqllimit($sql){
		$start = ($this->currentpage-1)*$this->pagesize;
		$sql .= " limit $start," . $this->pagesize;
		return $sql;
	}
	
	function safe($msg){
		$msg = trim($msg);
		$old = array("&amp;","&nbsp;","'",'"',"<",">","\t","\r");
		$new = array("&"," ","&#39;","&quot;","&lt;","&gt;","&nbsp; &nbsp; ","");
		$msg = str_replace($old,$new,$msg);
		$msg = str_replace("   ","&nbsp; &nbsp;",$msg);
		$old = array("/<script(.*)<\/script>/isU","/<frame(.*)>/isU","/<\/fram(.*)>/isU","/<iframe(.*)>/isU","/<\/ifram(.*)>/isU","/<style(.*)<\/style>/isU");
		$new = array("","","","","","");
		$msg = preg_replace($old,$new,$msg);
		return $msg;
	}
	
	function get_datetime($datetime){
		$h = $datetime ? date('H', $datetime) : 8;
		// $m = $datetime ? date('i') : 0;
		$str = '<select name="hour">';
		$str .= '<option value="8"' . ($h == 8 ? ' selected' : '') . '>早</option>';
		$str .= '<option value="18"' . ($h == 18 ? ' selected' : '') . '>晚</option>';
		// for($i=0;$i<24;$i++){
			// $str .= '<option value="' . $i . '"' . ($i==$h?' selected':'') . '>' . $i . '</option>';
		// }
		// $str .= '</select>';
		// $str .= '<select name="minute">';
		// for($i=0;$i<60;$i++){
			// $str .= '<option value="' . $i . '"' . ($i==$m?' selected':'') . '>' . $i . '</option>';
		// }
		// $str .= '</select>';
		return $str;
	}
	
	function logout(){
		unset($_SESSION['mid']);
		$this->ok++;
		$this->header = '?mod=login';
		$this->error[] = '系统帐号已退出！';
	}
	
	function edit_pass(){
		$oldpass = $_POST['oldpass'] ? $this->safe($_POST['oldpass']) : '';
		$newpass = $_POST['newpass'] ? $this->safe($_POST['newpass']) : '';
		$newpass2 = $_POST['newpass2'] ? $this->safe($_POST['newpass2']) : '';
		if(!$oldpass){
			$this->check++;
			$this->error[] = '原密码不能为空！';
		}
		if(!$newpass){
			$this->check++;
			$this->error[] = '新密码不能为空！';
		}
		if($newpass != $newpass2){
			$this->check++;
			$this->error[] = '两次密码不一致！';
		}
		if(!$this->check){
			$sql = "select * from esys_member where member_id = " . $this->mid . " and password = '$oldpass'";
			$check = $this->db->get_count($sql);
			if($check){
				$sql = "update esys_member set password='$newpass' where member_id = " . $this->mid;
				$this->db->query($sql);
				$this->ok++;
				$this->error[] = '密码修改成功，请重新登录！';
				$this->header = '?mod=login';
				unset($_SESSION['mid']);
			}else{
				$this->check++;
				$this->error[] = '原密码不正确！';
			}
		}
	}
	
	function login(){
		$username = $_POST['username'] ? $this->safe($_POST['username']) : '';
		$password = $_POST['password'] ? $this->safe($_POST['password']) : '';
		$sql = "select * from esys_member where group_id>0 and username='$username' and password='$password'";
		$checklogin = $this->db->get_row($sql);
		if($checklogin){
			$_SESSION['mid'] = $checklogin['member_id'];
			$this->ok++;
			$this->error[] = '登录成功！';
			$this->header = './';
		}else{
			$this->check++;
			$this->error[] = '帐号或密码不正确！';
		}
	}
	
	function get_member($mid){
		return $this->db->get_row('select * from esys_member where member_id = ' . $mid);
	}
	
	function get_member_name($mid){
		$username = '';
		if($mid){
			$mid_arr = explode(',',$mid);

			foreach ($mid_arr as $k=>$i){
				$v = $this->get_member($i);
				if($k>5) {
					$username .='...';
					break;
				}
				$username .= $v['username'].',';

			}
			if($username){
				return rtrim($username,',');
			}else{
				return '未找到';
			}
		}else{
			return '暂无';
		}

	}
	
	function get_machine($machine_id){
		return $this->db->get_row('select * from esys_machine where machine_id = ' . $machine_id);
	}
	
	function get_machine_name($machine_id){
		if($machine_id){
			$v = $this->get_machine($machine_id);
			if($v){
				return $v['name'];
			}else{
				return '未找到';
			}
		}else{
			return '暂无';
		}
	}
	
	function get_material($material_id){
		return $this->db->get_row('select * from esys_material where material_id = ' . $material_id);
	}
	
	function get_material_name($material_id){
		if($material_id){
			$v = $this->get_material($material_id);
			if($v){
				return $v['name'];
			}else{
				return '未找到';
			}
		}else{
			return '暂无';
		}
	}
	
	function get_customer($customer_id){
		return $this->db->get_row('select * from esys_customer where customer_id = ' . $customer_id);
	}
	
	function get_customer_name($customer_id){
		$attr_name = '';
		if($customer_id){
			$customer_arr = explode(',',$customer_id);

			foreach ($customer_arr as $k=>$i){
				$v = $this->get_customer($i);
				if($k>5) {
					$attr_name .='...';
					break;
				}
				$attr_name .= $v['attr_name'].',';
			}
			if($attr_name){
				return rtrim($attr_name,',');
			}else{
				return '未找到';
			}
		}else{
			return '暂无';
		}
	}
	
	function get_order($order_id){
		return $this->db->get_row('select * from esys_order where order_id = ' . $order_id);
	}
	
	function today(){
		$week = array('日','一','二','三','四','五','六');
		return '今天是' . date('Y年m月d日') . '，星期' . $week[date('w')];
	}
	
	function msgbox($erray=array(),$constyle="error",$header='') {
		if(!empty($erray))	{
			$str = "<div id=\"$constyle\"><ul>";
			foreach($erray as $error)
				$str .= "<li>$error</li>";
			$str .= "</ul><div class='clear'></div></div>";
			$header = $header ? 'location.href="' . $header . '"' : 'history.back()';
			$str .= '<script>setTimeout(function(){' . $header . ';},3000);</script>';
			return $str;
		}
	}
	
	// 修改员工
	function edit_member(){
		$member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
		$map['username'] = isset($_POST['username']) ? $this->safe($_POST['username']) : '';
		$map['password'] = isset($_POST['password']) ? $this->safe($_POST['password']) : '';
		$map['group_id'] = isset($_POST['group_id']) ? intval($_POST['group_id']) : '';
		$map['material_id'] = isset($_POST['material_id']) ? implode(',', $_POST['material_id']) : '';
		$this->jurisdiction('9');
		if(!$map['username']){
			$this->check++;
			$this->error[] = '用户名不能为空！';
		}
		if(!$map['password']){
			$this->check++;
			$this->error[] = '密码不能为空！';
		}
		if(!$map['group_id']){
			$this->check++;
			$this->error[] = '没有选择会员组';
		}
		$edit_txt = '添加';
		if(!$this->check){
			if($member_id){
				$this->db->update('esys_member', $map, 'where member_id = ' . $member_id, $member_id);
				$edit_txt = '修改';
			}else{
				$map['reg_time'] = time();
				$this->db->insert('esys_member', $map);
			}
			$this->ok++;
			$this->error[] = $edit_txt . '员工成功！';
			$this->header = '?mod=member';
		}
	}
	
	// 读取列表数据
	function member_list(){
		$where = ' where group_id != 9 ';
		$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : '';
		if($group_id) $where .= ' and group_id = ' . $group_id;
		$start_time = isset($_GET['start_time']) ? strtotime($_GET['start_time']) : 0;
		$end_time = isset($_GET['end_time']) ? strtotime($_GET['end_time']) : 0;
		if($start_time) $where .= ' and reg_time > ' . $start_time;
		if($end_time) $where .= ' and reg_time < ' . $end_time;
		$keyword = isset($_GET['keyword']) ? $this->safe($_GET['keyword']) : '';
		if($keyword) $where .= " and (username like '%$keyword%')";
		$sql = 'select * from esys_member ' . $where .' order by reg_time desc';
		$sql = $this->sqllimit($sql);
		// echo $sql;
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
	}
		
	function del_member(){
		$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : 0;
		$this->jurisdiction('9');
		if(!$this->check){
			if($member_id){
				$sql = 'delete from esys_member where member_id=' . $member_id;
				$this->db->query($sql);
				$this->ok++;
				$this->header = '?mod=member';
				$this->error[] = '删除员工成功！';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	// 修改机器
	function edit_machine(){
		$machine_id = isset($_POST['machine_id']) ? intval($_POST['machine_id']) : 0;
		$map['name'] = isset($_POST['name']) ? $this->safe($_POST['name']) : '';
		$this->jurisdiction('4,9');
		if(!$map['name']){
			$this->check++;
			$this->error[] = '机器名不能为空！';
		}
		$edit_txt = '添加';
		if(!$this->check){
			if($machine_id){
				$this->db->update('esys_machine', $map, 'where machine_id = ' . $machine_id, $machine_id);
				$edit_txt = '修改';
			}else{
				$this->db->insert('esys_machine', $map);
			}
			$this->ok++;
			$this->error[] = $edit_txt . '机器成功！';
			$this->header = '?mod=machine';
		}
	}
	
	// 读取列表数据
	function machine_list(){
		$sql = 'select * from esys_machine';
		$sql = $this->sqllimit($sql);
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
	}
		
	function del_machine(){
		$machine_id = isset($_GET['machine_id']) ? intval($_GET['machine_id']) : 0;
		$this->jurisdiction('4,9');
		if(!$this->check){
			if($machine_id){
				$sql = 'delete from esys_machine where machine_id=' . $machine_id;
				$this->db->query($sql);
				$this->ok++;
				$this->error[] = '删除机器成功！';
				$this->header = '?mod=machine';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	// 修改材料
	function edit_material(){
		$material_id = isset($_POST['material_id']) ? intval($_POST['material_id']) : 0;
		$map['name'] = isset($_POST['name']) ? $this->safe($_POST['name']) : '';
		$this->jurisdiction('4,9');
		if(!$map['name']){
			$this->check++;
			$this->error[] = '材料名称不能为空！';
		}
		$edit_txt = '添加';
		if(!$this->check){
			if($material_id){
				$this->db->update('esys_material', $map, 'where material_id = ' . $material_id, $material_id);
				$edit_txt = '修改';
			}else{
				$this->db->insert('esys_material', $map);
			}
			$this->ok++;
			$this->error[] = $edit_txt . '材料成功！';
			$this->header = '?mod=material';
		}
	}
	
	// 读取列表数据
	function material_list($is_limit = true){
		$sql = 'select * from esys_material';
		if($is_limit){
			$sql = $this->sqllimit($sql);
		}
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
	}
		
	function del_material(){
		$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : 0;
		$this->jurisdiction('4,9');
		if(!$this->check){
			if($material_id){
				$sql = 'delete from esys_material where material_id=' . $material_id;
				$this->db->query($sql);
				$this->ok++;
				$this->error[] = '删除材料成功！';
				$this->header = '?mod=material';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	// 修改客户
	function edit_customer(){
		$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
		$map['name'] = isset($_POST['name']) ? $this->safe($_POST['name']) : '';
		$map['attr_name'] = isset($_POST['attr_name']) ? $this->safe($_POST['attr_name']) : '';
		$map['contact'] = isset($_POST['contact']) ? $this->safe($_POST['contact']) : '';
		$map['address'] = isset($_POST['address']) ? $this->safe($_POST['address']) : '';
		$map['phone'] = isset($_POST['phone']) ? $this->safe($_POST['phone']) : '';
		$map['qq'] = isset($_POST['qq']) ? $this->safe($_POST['qq']) : '';
    if(in_array($this->member_info['group_id'], array( 9))) {
      $map['pay_types'] = isset($_POST['pay_types']) ? implode(',', $_POST['pay_types']) : '';
      $map['pay_types'] = trim($map['pay_types']);
    }
		$this->jurisdiction('1,9');
		if(!$map['name']){
			$this->check++;
			$this->error[] = '客户全称不能为空！';
		}
		if(!$map['attr_name']){
			$this->check++;
			$this->error[] = '客户简称不能为空！';
		}
		if(!$map['contact']){
			$this->check++;
			$this->error[] = '联系人不能为空！';
		}
		if(!$map['address']){
			$this->check++;
			$this->error[] = '客户地址不能为空！';
		}
		if(!$map['phone']){
			$this->check++;
			$this->error[] = '客户电话不能为空！';
		}
		$edit_txt = '添加';
		if(!$this->check){
			if(in_array($this->member_info['group_id'], array(4, 9))){
				$map['member_id'] = isset($_POST['member_id']) ? intval($_POST['member_id']) : $this->mid;
				$old_member_id = isset($_POST['old_member_id']) ? intval($_POST['old_member_id']) : 0;
				if($old_member_id && $old_member_id != $map['member_id']){ // 同步转移订单到新业务员下
					$sql = 'update esys_order set member_id = ' . $map['member_id'] . ' where member_id = ' . $old_member_id;
					$this->db->query($sql);
				}
			}else{
				$map['member_id'] = $this->mid;
			}
			if($customer_id){
				$where = '';
				if($this->member_info['group_id'] == 1){
					$where = ' and member_id=' . $this->mid;
				}
				$sql = "select * from esys_customer where customer_id = '$customer_id' $where and is_del=0";
				$check = $this->db->get_count($sql);
				if($check){
					$this->db->update('esys_customer', $map, 'where customer_id = ' . $customer_id, $customer_id);
					$edit_txt = '修改';
				}else{
					$this->check++;
					$this->error[] = '没有找到相关客户，可能已被删除！';
				}
			}else{
				$map['join_time'] = time();
				$this->db->insert('esys_customer', $map);
			}
			if(!$this->check){
				$this->ok++;
				$this->error[] = $edit_txt . '客户成功！';
				$this->header = '?mod=customer';
			}
		}
	}
	
	// 读取列表数据
	function customer_list(){
		$type = 0;
		if(in_array($this->member_info['group_id'], array(4, 9))) $type = isset($_GET['type']) ? $_GET['type'] : 0;
		$where = ' where is_del = ' . $type;
		if($this->member_info['group_id']==1) $where .= ' and member_id = ' . $this->mid;
		$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : '';
		if($member_id) $where .= ' and member_id = ' . $member_id;
		$start_time = isset($_GET['start_time']) ? strtotime($_GET['start_time']) : 0;
		$end_time = isset($_GET['end_time']) ? strtotime($_GET['end_time']) : 0;
		if($start_time) $where .= ' and join_time > ' . $start_time;
		if($end_time) $where .= ' and join_time < ' . $end_time;
		$keyword = isset($_GET['keyword']) ? $this->safe($_GET['keyword']) : '';
		if($keyword) $where .= " and (name like '%$keyword%' or attr_name like '%$keyword%' or address like '%$keyword%' or phone like '%$keyword%' or qq like '%$keyword%')";
		$sql = 'select * from esys_customer ' . $where .' order by join_time desc';
		$sql = $this->sqllimit($sql);
		// echo $sql;
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
	}

	function get_customer_pay_types($customer_id=0){

    $customer_id = isset($_POST['customer_id']) && $_POST['customer_id'] ? intval($_POST['customer_id']) : $customer_id;
    $res = array();
    if ($this->member_info['group_id'] == 9)
    {
      $res = $this->pay_type_list;
    }
    else
    {
      $sql = "select pay_types from esys_customer where customer_id={$customer_id} ";
      $list = $this->db->get_row($sql);
      $res = explode(',',$list['pay_types']);
    }
    return $res;

    }
	// 读取客户下拉
	function get_customer_select($member_id = 0){
		$member_id = isset($_POST['member_id']) && $_POST['member_id'] ? intval($_POST['member_id']) : 0;
		if($this->member_info['group_id'] != 2){
			if(!in_array($this->member_info['group_id'], array(4,5, 9))){
				$where = ' and member_id = ' . $this->mid;
			}elseif(in_array($this->member_info['group_id'], array(4, 9)) && $member_id > 0){
				$where = ' and member_id = ' . $member_id;
			}
		}
		
		
		$ck = isset($_REQUEST['ck']) && $_REQUEST['ck'] ? $this->safe($_REQUEST['ck']) : '';
		
		if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id']==0){
			$ck='';
		}
		if($ck) $where .= " and attr_name like '%$ck%'";
		$sql = 'select customer_id, attr_name from esys_customer where is_del=0 ' . $where . ' order by attr_name asc';
		
        $list = $this->db->get_results($sql);
        $res = array();
		$ck_customer_id = '';
		$flag = 1;
        foreach($list as $v)
        {
			if(!empty($ck)&& $_POST['is_chang_order']!=1)
			{
				$res[$ck]['attr_name'] = $ck;
				$ck_customer_id .= $v['customer_id'].',';
			}
			/*
			if($ck==$v['attr_name']){
				$flag = 0;
				unset($res[$ck]);
			}
			*/
            if($ck!=$v['attr_name'] || $_POST['is_chang_order']==1){
                if(empty($res[$v['attr_name']]))
                {
                    $res[$v['attr_name']] = $v;
                }
                else
                {
                    $res[$v['attr_name']]['customer_id'] .= ','.$v['customer_id'];
                }
            }
        }
		if(!empty($ck_customer_id) && $flag==1)
		{
			$res[$ck]['customer_id'] .= trim($ck_customer_id,',');
		}
		
    return $res;
		//return $this->db->get_results($sql);
	}
		
	// 读取员工下拉
	function get_member_select($group_id = 0){
		if(in_array($this->member_info['group_id'], array(4, 9, 5))){
			$where = '';
			if($group_id){
				$where = ' where group_id = 9 or group_id = ' . $group_id;
			}
			$sql = 'select member_id, username from esys_member ' . $where . ' order by group_id desc,username asc';
			return $this->db->get_results($sql);
		}
	}
	
	// 读取机器下拉
	function get_machine_select(){
		$sql = 'select machine_id, name from esys_machine';
		return $this->db->get_results($sql);
	}
	
	// 读取材料下拉
	function get_material_select(){
		$where = '';
		$ck = isset($_POST['ck']) && $_POST['ck'] ? $this->safe($_POST['ck']) : '';
		if($ck) $where = " where name like '%$ck%'";
		if(in_array($this->member_info['group_id'], array(4))){
			$material = $this->member_info['material_id'] ? $this->member_info['material_id'] : '';
			if($material){
				$where = $where ? ($where . ' and') : ' where';
				$where .= ' material_id in (' . $material . ')';
			}
		}
		$sql = 'select material_id, name from esys_material' . $where;
		return $this->db->get_results($sql);
	}
	
	function del_customer(){
		$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;
		$this->jurisdiction('1,4,9');
		if(!$this->check){
			if($customer_id){
				$where = '';
				if($this->member_info['group_id'] == 1){
					$where = ' and member_id=' . $this->mid;
				}
				$sql = "select * from esys_customer where customer_id = '$customer_id' $where and is_del=0";
				$check = $this->db->get_count($sql);
				if($check){
					$sql = 'update esys_customer set is_del=1 where customer_id='.$customer_id;
					$this->db->query($sql);
					$this->ok++;
					$this->error[] = '删除客户成功！';
					$this->header = '?mod=customer';
				}else{
					$this->check++;
					$this->error[] = '操作错误！';
				}
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function re_customer(){
		$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;
		$this->jurisdiction('4,9');
		if(!$this->check){
			if($customer_id){
				$sql = 'update esys_customer set is_del=0 where customer_id='.$customer_id;
				$this->db->query($sql);
				$this->ok++;
				$this->error[] = '还原客户成功！';
				$this->header = '?mod=customer';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function res_del_customer(){
		$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;
		$this->jurisdiction('9');
		if(!$this->check){
			if($customer_id){
				$sql = 'delete from esys_customer where customer_id=' . $customer_id;
				$this->db->query($sql);
				$this->ok++;
				$this->header = '?mod=customer&type=1';
				$this->error[] = '彻底删除客户成功！';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function order_edit_jurisdiction($order_id){
		if($order_id && !in_array($this->member_info['group_id'], array(4, 9))){
			$order_info = $this->get_order($order_id);
			if(!$order_info){
				$this->check++;
				$this->error[] = '未找到相关订单信息！';
			}elseif($order_info['member_id'] != $this->mid){
				$this->check++;
				$this->error[] = '禁止操作他人订单！';
			}elseif($order_info['is_del']){
				$this->check++;
				$this->error[] = '订单已删除，禁止修改！';
			}elseif($order_info['production_id']){
				$this->check++;
				$this->error[] = '订单已进入生产流程，禁止修改！';
			}elseif($order_info['delivery_status']){
				$this->check++;
				$this->error[] = '订单已发货，禁止修改！';
			}
		}
	}
	
	function order_status_jurisdiction($order_info){
		if(!in_array($this->member_info['group_id'], array(4, 9))){
			if(in_array($this->member_info, array(1))){
				if(!$order_info){
					$this->check++;
					$this->error[] = '未找到订单！';
				}elseif($order_info['member_id'] != $this->mid){
					$this->check++;
					$this->error[] = '禁止操作他人订单！';
				}elseif($order_info['production_status'] == 0){
					$this->check++;
					$this->error[] = '不能操作未上机订单';
				}elseif($order_info['delivery_status']){
					$this->check++;
					$this->error[] = '不能操作已发货订单';
				}elseif($order_info['is_del']){
					$this->check++;
					$this->error[] = '该订单已被删除，无法操作！';
				}else{
					$delivery_status = isset($_POST['delivery_status']) ? intval($_POST['delivery_status']) : 0;
					$result_delivery_time = isset($_POST['result_delivery_time']) ? intval($_POST['result_delivery_time']) : 0;
					if(!$delivery_status || !$result_delivery_time){
						$this->check++;
						$this->error[] = '请选择已发货及填写发货时间！';
					}
				}
			}elseif(in_array($this->member_info, array(2))){
				if(!$order_info){
					$this->check++;
					$this->error[] = '未找到订单！';
				}elseif($order_info['delivery_status']){
					$index->check++;
					$index->error[] = '不能操作已发货订单';
				}elseif($order_info['is_del']){
					$this->check++;
					$this->error[] = '该订单已被删除，无法操作！';
				}elseif($order_info['production_id'] && $order_info['production_id'] != $this->mid){
					$this->check++;
					$this->error[] = '已有其它技术员操作此订单，无法操作！';
				}else{
					$production_status = isset($_POST['production_status']) ? intval($_POST['production_status']) : 0;
					$machine_id = isset($_POST['machine_id']) ? intval($_POST['machine_id']) : 0;
					if(!$production_status || !$machine_id){
						$this->check++;
						$this->error[] = '请选择上机状态及机器！';
					}
				}
			}
		}
	}

	// 修改订单
	function edit_order(){
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$this->order_edit_jurisdiction($order_id);
		$map['customer_id'] = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
		$map['weight'] = isset($_POST['weight']) ? intval($_POST['weight']) : 0;
		$map['price'] = isset($_POST['price']) ? $this->safe($_POST['price']) : 0;
		$map['pay_type'] = isset($_POST['pay_type']) ? $this->safe($_POST['pay_type']) : 0;
		$map['material_id'] = isset($_POST['material_id']) ?  $this->safe($_POST['material_id']) : '';
		$map['remarks'] = isset($_POST['remarks']) ? $this->safe($_POST['remarks']) : '';
		$map['img'] = isset($_POST['img']) ? $_POST['img'] : '';
		$map['zip_path'] = isset($_POST['zip_path']) ? $_POST['zip_path'] : '';
		$member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
		$hour = isset($_POST['hour']) ? intval($_POST['hour']) : 8;
		$minute = isset($_POST['minute']) ? intval($_POST['minute']) : 0;
		// $map['delivery_time'] = isset($_POST['delivery_time']) && $_POST['delivery_time'] ? strtotime($_POST['delivery_time'] . ' ' . $hour . ':' . $minute) : 0;
		$map['delivery_time'] = isset($_POST['delivery_time']) && $_POST['delivery_time'] ? strtotime($_POST['delivery_time'] . ' ' . $hour . ':00:00') : 0;
		$map['result_delivery_time'] = $map['delivery_time'];
		// echo strtotime($_POST['delivery_time'] . ' ' . $hour . ':00:00');
		// exit;
		// $this->jurisdiction('1,9');
		if(!$map['customer_id']){
			$this->check++;
			$this->error[] = '没有选择客户！';
		}
		if(!$map['weight']){
			$this->check++;
			$this->error[] = '克重不能为空！';
		}
		if(!$map['price']){
			$this->check++;
			$this->error[] = '价格不能为空！';
		}
		if(!$map['material_id']){
			$this->check++;
			$this->error[] = '材料不能为空！';
		}
		if(!$map['delivery_time']){
			$this->check++;
			$this->error[] = '发货时间不能为空！';
		}
		$edit_txt = '添加';
		/*
		$zip_file = $_FILES["ufile"];
		if(!empty($zip_file))
		{	
			$zip_file_path = $this->upload_zip($zip_file);
			if($zip_file_path['code']==0)
			{
				$map['zip_path'] = $zip_file_path['data']['path'];
			}
			else
			{
				$this->check++;
				$this->error[] = $zip_file_path['msg'];
			}
		}
		*/
		if(!$this->check){
			if(in_array($this->member_info['group_id'], array(4, 9)) && $member_id > 0){
				$member_id = $member_id;
			}else{
				$member_id = $this->mid;
			}
			if($order_id){
				// if(in_array($this->member_info['group_id'], array(1))) $where = ' and member_id=' . $this->mid;
				// $sql = "select * from esys_order where order_id = '$order_id' $where and is_del=0";
				// $check = $this->db->get_count($sql);
				// if($check){
					$map['member_id'] = $member_id;
					$this->db->update('esys_order', $map, 'where order_id = ' . $order_id, $order_id);
					$edit_txt = '修改';
				// }else{
					// $this->check++;
					// $this->error[] = '没有找到相关订单，可能已被删除！';
				// }
			}else{
				$map['member_id'] = $member_id;
				$map['order_time'] = time();
				$map['order_create_month'] = date('Ym',$map['order_time']);
				$this->db->insert('esys_order', $map);
			}
			if(!$this->check){
				$this->ok++;
				$this->error[] = $edit_txt . '订单成功！';
				if(in_array($this->member_info['group_id'], array(1))){
					$this->header = '?mod=order_1';
				}else{
					$this->header = '?mod=order';
				}
			}
		}
	}
	function confirm_production_member(){
        $order_ids = isset($_POST['order_ids']) ? $_POST['order_ids'] : 0;
        $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : 0;
        if (!empty($order_ids) && !empty($member_id)) {
            $sql = "update esys_order set production_member_id = IF(production_member_id>1, 0, {$member_id})  where order_id in (" . $order_ids . ')';
            //echo $sql;
            //exit;
            $this->db->query($sql);
            $this->ok++;
            $this->error[] = '选择成功！';
        } else {
            $this->check++;
            $this->error[] = '请选择订单';
        }
        return true;
    }

	function confirm_budong(){
		$order_ids = isset($_POST['order_ids']) ? $_POST['order_ids'] : 0;
		if (!empty($order_ids) ) {
			$sql = 'update esys_order set is_bu_dong = IF(is_bu_dong=1, 0, 1) where order_id in (' . $order_ids . ')';
			//echo $sql;
			//exit;
			$this->db->query($sql);
			$this->ok++;
			$this->error[] = '操作成功！';
		} else {
			$this->check++;
			$this->error[] = '请选择订单';
		}
		return true;
	}

	function do_order_status(){
		$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
		$ids = explode(',', $order_id);
		foreach($ids as $order_id){
			$order_info = $this->get_order($order_id);
			$this->order_status_jurisdiction($order_info);
			if(!$this->check){
				$hour = isset($_POST['hour']) ? intval($_POST['hour']) : 0;
				$map['result_delivery_time'] = isset($_POST['result_delivery_time']) && $_POST['result_delivery_time'] ? strtotime($_POST['result_delivery_time'] . ' ' . $hour . ':00:00') : $map['delivery_time'];
				$map['delivery_time'] = $map['result_delivery_time'];
				if(empty($map['delivery_time']))
				{
					unset($map['delivery_time']);
				}
				if(empty($map['result_delivery_time']))
				{
					unset($map['result_delivery_time']);
				}
				if(in_array($this->member_info['group_id'], array(2, 9))){
					$map['production_status'] = isset($_POST['production_status']) ? intval($_POST['production_status']) : 0;
					if($map['production_status'] == 1) $map['sy_weight'] = $_POST['sy_weight'] ? intval($_POST['sy_weight']) : 0;
					$map['machine_id'] = isset($_POST['machine_id']) ? intval($_POST['machine_id']) : 0;
					if(in_array($this->member_info['group_id'], array(4, 9))){
						$map['production_id'] = isset($_POST['production_id']) ? intval($_POST['production_id']) : 0;
						if($map['production_id']) $map['production_time'] = time();
					}else{
						if($map['production_status'] && !$order_info['production_id']){
							// $map['production_id'] = $this->mid;
							$map['production_time'] = time();
						}
					}
				}
				if(in_array($this->member_info['group_id'], array(1, 4, 9))){
					$map['delivery_status'] = isset($_POST['delivery_status']) ? intval($_POST['delivery_status']) : 0;
					// $minute = isset($_POST['minute']) ? intval($_POST['minute']) : 0;
					// $map['result_delivery_time'] = isset($_POST['result_delivery_time']) && $_POST['result_delivery_time'] ? strtotime($_POST['result_delivery_time'] . ' ' . $hour . ':' . $minute) : 0;
				}
        if(in_array($this->member_info['group_id'], array(1))){
          $map['price'] = trim($_POST['price']);
          $map['pay_type'] = trim($_POST['pay_type']);
        }
				if(!$this->check){
					$this->db->update('esys_order', $map, 'where order_id=' . $order_id, $order_id);
					$this->ok++;
					$this->error[] = '修改订单状态成功！';
					if(in_array($this->member_info['group_id'], array(1, 4))){
						$this->header = '?mod=order_2';
					}elseif(in_array($this->member_info['group_id'], array(2))){
						if($map['production_status'] == 2) $this->header = '?mod=order_2';
						elseif($map['production_status'] == 1) $this->header = '?mod=order_1';
					}else{
						$this->header = '?mod=order';
					}
				}
			}
		}
		// return $str;
	}

    function do_order_finance_status(){
        $finance_ids = isset($_POST['finance_id']) ? $_POST['finance_id'] : 0;
		$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
		if($order_id==0) {
            $ids = explode(',', $finance_ids);
		} else {
			$ids = explode(',', $order_id);
		}
		$finance_no = trim($_POST['finance_no']);
		/*
		$finance_no_info = $this->get_finance($finance_no);
		if(!empty($finance_no_info)){
			$this->check++;
			$this->error[] = '回款编号已存在';
			return false;
		}
		*/
		$total_finance_price=0;
		$finance_orders = array();
		$fin_month=0;
        foreach($ids as $finance_id) {
            $finance_price=0;
            $customer_id_arr = $sale_member_id_arr = array();
            $map['finance_no'] = $finance_no;
            $map['finance_status'] = 2;
			$map['sy_price'] = 0;
			$map['settle_time'] = empty($_POST['settle_time']) ? time() : strtotime($_POST['settle_time']);

			if($order_id==0) {
				$param_info = explode("_",$finance_id);
				$customer_id = $param_info['0'];
				$order_month = $param_info['1'];

				$where  = "where customer_id='{$customer_id}' and order_create_month='{$order_month}' AND is_del = 0 ";
			} else {
				$where  = "where order_id='{$order_id}'";
			}

			$sql = 'select sum(IF(`sy_price`>0,`sy_price`,price)) as total_sy_price from esys_order '. $where. ' and finance_status!=2 group by customer_id' ;
			$should_gain =  $this->db->get_row($sql);
			$total_sy_price = $should_gain['total_sy_price'];

			//待回款订单
			$sql = 'select price, sy_price, order_id,finance_no,finance_status,customer_id,member_id,order_create_month,pay_type from esys_order '. $where. 'and finance_status!=2 order by order_id asc';
			$list  = $this->db->get_results($sql);

			$total_price = 0;
			$order_ids = '';
			$last_order_id = '';
			//全部回款
			if( intval($_POST['finance_status'])==2) {
				$gain_price = $total_sy_price;
			}//部分回款
			elseif (intval($_POST['finance_status'])==1 && $_POST['sk_price'] >0) {
				$gain_price = $_POST['sk_price'];
			}
			$total_finance_price += $gain_price;
            $finance_price += $gain_price;
			foreach ($list as $v) {
				$price = $v['sy_price'] > 0 ? $v['sy_price'] : $v['price'];
				$total_price += $price;
				$customer_id_arr[$v['customer_id']] = $v['customer_id'];
				$sale_member_id_arr[$v['member_id']] = $v['member_id'];
				$fin_month = $v['order_create_month'];
				if ($gain_price>=$total_price) {
					$order_ids .= "'{$v['order_id']}',";
					$finance_orders[]=array(
						'order_id' => $v['order_id'],
						'finance_no' =>$finance_no,
						'price' => $price
					);
				} else {
					$last_order_id = $v['order_id'];
					break;
				}
			}

			if (!empty($order_ids)) {
				$this->db->update('esys_order', $map, ' where order_id in('.rtrim($order_ids, ',').')',1);
			}

			if (!empty($last_order_id)) {
				$last_sy_price = $total_price - $gain_price ;

				if ($last_sy_price > 0 ) {
					$map['finance_status'] = 1;
					$map['sy_price'] = $last_sy_price ;

					$finance_orders[]=array(
						'order_id' => $v['order_id'],
						'finance_no' =>$finance_no,
						'price' => $price-$last_sy_price
					);
					$this->db->update('esys_order', $map, " where order_id = '{$last_order_id}'",1);
				}
			}

			/*
			//未还款的订单剩余金额改为订单金额
			$sql = "update esys_order set sy_price=price {$where} AND finance_status=0";
			$this->db->query($sql);
			*/

            //记录回款编号和回款金额
            $id = $this->add_finance(implode(",",$customer_id_arr),$finance_no,$finance_price,$map['settle_time'],implode(",",$sale_member_id_arr),$fin_month,$v['pay_type']);
            //记录回款明细
            $this->add_finance_order($finance_orders, $id);
            $this->ok++;
        }

		$this->error[] = '收款成功！';
    // return $str;
    }

	function all_status(){
		$ids = isset($_POST['ids']) ? implode(',', $_POST['ids']) : '';
		$purl = isset($_POST['purl']) ? $_POST['purl'] : '';
		$submit = isset($_POST['submit']) ? $_POST['submit'] : '部分上机';
		$status = $submit == '全部上机' ? 2 : 1;
		if(!$ids){
			$this->check++;
			$this->error[] = '没有选择';
		}
		if(!$this->check){
			$sql = 'update esys_order set production_status = ' . $status . ' where production_status < 2 and order_id in (' . $ids . ')';
		// echo $sql;
		// exit;
			$this->db->query($sql);
			$this->ok++;
			$this->error[] = '修改生产状态成功！';
			$this->header = $purl;
		}
	}

	function order_serach(){
		$type = 0;
		$is_del = 0;
		if(in_array($this->member_info['group_id'], array(4, 9))) $is_del = isset($_GET['is_del']) ? $_GET['is_del'] : 0;
		$where = ' where is_del = ' . $is_del;
		if($this->member_info['group_id']==1){
			$where .= ' and member_id = ' . $this->mid;
		}elseif($this->member_info['group_id']==2){
			$where .= ' and (production_id = ' . $this->mid . ' or production_id = 0)';
		}elseif(in_array($this->member_info['group_id'], array(4, 9))){
			// 业务ID
			$member_id = isset($_GET['member_id']) ? intval($_GET['member_id']) : '';
			if($member_id) $where .= ' and member_id = ' . $member_id;
			// 技术ID
			$production_id = isset($_GET['production_id']) ? intval($_GET['production_id']) : 0;
			if($production_id) $where .= ' and production_id = ' . $production_id;
		}
		// 客户ID
		$customer_id = isset($_GET['customer_id']) ? trim($_GET['customer_id']) : 0;
		if($customer_id) $where .= ' and customer_id in (' . $customer_id . ')';
		// 发货时间查询
		$start_time_ext = isset($_GET['start_time_ext']) ? intval($_GET['start_time_ext']) : 8;
		$end_time_ext = isset($_GET['end_time_ext']) ? intval($_GET['end_time_ext']) : 8;
		$start_time = isset($_GET['start_time']) && $_GET['start_time'] ? strtotime($_GET['start_time'] . ' ' . $start_time_ext . ':00') : 0;
		$end_time = isset($_GET['end_time'])&& $_GET['end_time'] ? strtotime($_GET['end_time'] . ' ' . $end_time_ext . ':00') : 0;
		if($start_time){
			$where .= ' and ((result_delivery_time >= ' . $start_time . ') or (result_delivery_time = 0 and delivery_time >= ' . $start_time . '))';
		}
		if($end_time){
			$where .= ' and ((result_delivery_time != 0 and result_delivery_time <= ' . $end_time . ') or (result_delivery_time = 0 and delivery_time <= ' . $end_time . '))';
		}
		// 发货状态
		$delivery_status = isset($_GET['delivery_status']) ? $_GET['delivery_status'] : '';
		if(is_numeric($delivery_status)) $where .= ' and delivery_status = ' . $delivery_status;
		// 生产状态
		$production_status = isset($_GET['production_status']) ? $_GET['production_status'] : '';
		if(is_numeric($production_status)) $where .= ' and production_status = ' . $production_status;
		// 下单时间
		$order_start_time = isset($_GET['order_start_time']) ? strtotime($_GET['order_start_time']) : 0;
		$order_end_time = isset($_GET['order_end_time']) && $_GET['order_end_time'] ? strtotime($_GET['order_end_time'])+86400 : 0;
		if($order_start_time) $where .= ' and order_time >= ' . $order_start_time;
		if($order_end_time) $where .= ' and order_time <= ' . $order_end_time;
		// 结算时间
		$start_settle_time = isset($_GET['start_settle_date']) ? strtotime($_GET['start_settle_date']) : 0;
		$end_settle_time = isset($_GET['end_settle_date']) && $_GET['end_settle_date'] ? strtotime($_GET['end_settle_date'])+86400 : 0;
		if(!empty($start_settle_time)) $where .= " and settle_time>={$start_settle_time}";
		if(!empty($end_settle_time)) $where .= " and settle_time<{$end_settle_time}";

		$machine_id = isset($_GET['machine_id']) ? intval($_GET['machine_id']) : '';
		if($machine_id) $where .= ' and machine_id = ' . $machine_id;
		$material_id = isset($_GET['material_id']) ? intval($_GET['material_id']) : '';
		if($material_id) $where .= ' and material_id = ' . $material_id;
		if(!empty($_GET['finance_no'])) $where .= " and finance_no = '" . trim($_GET['finance_no'])."'";
		if(is_numeric($_GET['finance_status'])) $where .= " and finance_status = '" . trim($_GET['finance_status'])."'";
    if(!empty($_GET['pay_type'])) $where .= " and pay_type = '" . trim($_GET['pay_type'])."'";
		/*$production_start_time = isset($_GET['production_start_time']) ? strtotime($_GET['production_start_time']) : 0;
		$production_end_time = isset($_GET['production_end_time']) ? strtotime($_GET['production_end_time']) : 0;
		$delivery_start_time = isset($_GET['delivery_start_time']) ? strtotime($_GET['delivery_start_time']) : 0;
		$delivery_end_time = isset($_GET['delivery_end_time']) ? strtotime($_GET['delivery_end_time']) : 0;
		$result_start_time = isset($_GET['result_start_time']) ? strtotime($_GET['result_start_time']) : 0;
		$result_end_time = isset($_GET['result_end_time']) ? strtotime($_GET['result_end_time']) : 0;
		if($production_start_time) $where .= ' and production_time > ' . $production_start_time;
		if($production_end_time) $where .= ' and production_time < ' . $production_end_time;
		if($delivery_start_time) $where .= ' and delivery_time > ' . $delivery_start_time;
		if($delivery_end_time) $where .= ' and delivery_time < ' . $delivery_end_time;
		if($result_start_time) $where .= ' and result_delivery_time > ' . $result_start_time;
		if($result_end_time) $where .= ' and result_delivery_time < ' . $result_end_time;*/
		// $keyword = isset($_GET['keyword']) ? $this->safe($_GET['keyword']) : '';
		// if($keyword) $where .= " and (name like '%$keyword%' or address like '%$keyword%' or phone like '%$keyword%' or qq like '%$keyword%')";
		// echo $where;
		$_SESSION['where'] = $where;
		// echo $where;
		return $where;
	}
	
	function order_ordertype(){
		$ordertype = '';
		$order = isset($_GET['order']) && in_array($_GET['order'], array('weight', 'price', 'delivery_time', 'order_time', 'machine_id', 'production_time', 'production_status')) ? $_GET['order'] : '';
		$order = $order == 'delivery_time' ? 'result_delivery_time' : $order;
		$type = isset($_GET['type']) && in_array($_GET['type'], array('asc', 'desc')) ? $_GET['type'] : '';
		if($order && $type) $ordertype = $order . ' ' . $type;
		// echo $order;
		// exit;
		return $ordertype;
	}
	
	function get_ordertype($colum, $type = 'asc'){
		$subPage_link = '?' . $_SERVER["QUERY_STRING"];
		if(strpos($subPage_link, '&currentpage')){
			$subPage_link = preg_replace('/&currentpage=\d+/', '', $subPage_link);
		}
		if(!strpos($subPage_link, '&order')){
			$subPage_link .= '&order=' . $colum;
		}else{
			$subPage_link = preg_replace('/&order=\w+/', '&order=' . $colum, $subPage_link);
		}
		if(!strpos($subPage_link, 'type')){
			$subPage_link .= '&type=' . $type;
		}else{
			$subPage_link = preg_replace('/type=\w+/', 'type=' . $type, $subPage_link);
		}
		return $subPage_link;
	}
	
	// 读取列表数据
	function order_list($step = ''){
		$where = $this->order_serach();

		if(in_array($this->member_info['group_id'], array(2, 4))){
			$material = $this->member_info['material_id'] ? $this->member_info['material_id'] : '';
			if($material){
				$where .= ' and material_id in (' . $material . ')';
			}
		}
		if(is_numeric($step)){
			switch($step){
				case 1:
					// $where .= ' and production_status = 0';
					if(in_array($this->member_info['group_id'], array(1, 4))) $where .= ' and production_status < 2';
					elseif(in_array($this->member_info['group_id'], array(2))) $where .= ' and production_status < 2';
				break;
				case 2:
					if(in_array($this->member_info['group_id'], array(1, 4))) $where .= ' and delivery_status = 0 and production_status > 1';
					elseif(in_array($this->member_info['group_id'], array(2))) $where .= ' and production_status = 2';
				break;
			}
		}
		$sql = 'select sum(weight) as cw, sum(price) as cp from esys_order ' . $where;
		$sc = $this->db->get_row($sql);
		if($sc){
			$this->count_weight = $sc['cw'];
			$this->count_price = $sc['cp'];
		}
		//统计
		$sql = 'select sum(IF(`sy_weight`>0,`sy_weight`,weight)) as cw, material_id from esys_order where is_del = 0  and production_status != 2 group by material_id';
		$sum_list = $this->db->get_results($sql);
		if($sum_list){
			$this->sum_list = $sum_list;
		}

		$ordertype = $this->order_ordertype();
		$ordertype = $ordertype ? $ordertype : ' order_time desc';
		$sql = 'select * from esys_order ' . $where .' order by ' . $ordertype;

		$sql = $this->sqllimit($sql);
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
	}
		
	//导出excel	
	function order_excel(){
		// $where = $this->order_serach();
		$where = $_SESSION['where'];
		$sql = 'select * from esys_order ' . $where .' order by order_time desc';
		$list = $this->db->get_results($sql);
		if($list){
			require_once('PHPExcel.php');
			$nowtime = time();
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle('导出订单数据(' . date('Y-m-d H:i:s', $nowtime) . ')');
			$objPHPExcel->getActiveSheet()->setAutoFilter('B1:K1');
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A1', '订单号')
						->setCellValue('B1', '客户简称')
						->setCellValue('C1', '业务员')
						->setCellValue('D1', '克重')
						->setCellValue('E1', '价格')
						->setCellValue('F1', '付款方式')
						->setCellValue('G1', '材料')
						->setCellValue('H1', '技术员')
						->setCellValue('I1', '生产状态')
						->setCellValue('J1', '发货状态')
						->setCellValue('K1', '发货时间')
						->setCellValue('L1', '下单时间')
						->setCellValue('M1', '备注');
			foreach($list as $n=>$v){
				$i = $n+2;
				$production_status = $v['production_status']==1 ? '部分上机' : ($v['production_status']==1 ? $v['machine_id'] : '待上机');
				$delivery_time = $v['result_delivery_time'] ? PHPExcel_Shared_Date::PHPToExcel($v['result_delivery_time']+28800) : ($v['delivery_time'] > time() ? PHPExcel_Shared_Date::PHPToExcel($v['delivery_time']+28800) : '已超时');
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValueExplicit('A' . $i, date('Ymdhis', $v['order_time']), PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValue('B' . $i, $this->get_customer_name($v['customer_id']))
							->setCellValue('C' . $i, $this->get_member_name($v['member_id']))
							->setCellValue('D' . $i, $v['weight'])
							->setCellValue('E' . $i, $v['price'])
							->setCellValue('F' . $i, $v['pay_type'])
							->setCellValue('G' . $i, $this->get_material_name($v['material_id']))
							->setCellValue('H' . $i, $this->get_member_name($v['production_id']))
							->setCellValue('I' . $i, $production_status)
							->setCellValue('J' . $i, ($v['delivery_status'] ? '已发货' : '未发货'))
							->setCellValue('K' . $i, $delivery_time)
							->setCellValue('M' . $i, $v['remarks'])
							->setCellValue('L' . $i, PHPExcel_Shared_Date::PHPToExcel($v['order_time']+28800))->getStyle('L' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22);
				// $objPHPExcel->setActiveSheetIndex(0)->getStyle('H' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22);
				/*if($v['delivery_time']<time()){
					$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				}*/
				$objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX22);
			}
			$objPHPExcel->setActiveSheetIndex()->setCellValue('D' . ($i+1), '=SUM(D2:D' . $i . ')'); 
			$objPHPExcel->getActiveSheet()->setTitle('订单数据');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(35);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$filename = 'data/' . date('Y-m-d-H-i-s', $nowtime) . '.xlsx';
			$objWriter->save($filename);
			// $objWriter->save(dirname(__FILE__) . '/' . $filename);
			$response = array(
				'status' => 1,
				'fileurl' => $filename
			);
		}else{
			$response = array(
				'status' => 0,
				'msg' => '没有可导出的数据！'
			);
		}
		echo json_encode($response);
	}
		
	function del_order(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$this->jurisdiction('1,4,9');
		if(!$this->check){
			if($order_id){
				$where = '';
				if($this->member_info['group_id'] == 1){
					$where = ' and member_id=' . $this->mid;
				}
				$sql = "select * from esys_order where order_id = '$order_id' $where and is_del=0";
				$check = $this->db->get_count($sql);
				if($check){
					$sql = 'update esys_order set is_del=1 where order_id='.$order_id;
					$this->db->query($sql);
					$this->ok++;
					$this->error[] = '删除订单成功！';
					$this->header = '?mod=order';
				}else{
					$this->check++;
					$this->error[] = '操作错误！';
				}
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function re_order(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$this->jurisdiction('4,9');
		if(!$this->check){
			if($order_id){
				$sql = 'update esys_order set is_del=0 where order_id='.$order_id;
				$this->db->query($sql);
				$this->ok++;
				$this->error[] = '还原订单成功！';
				$this->header = '?mod=order&is_del=1';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function res_del_order(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$this->jurisdiction('9');
		if(!$this->check){
			if($order_id){
				$sql = 'delete from esys_order where order_id=' . $order_id;
				$this->db->query($sql);
				$this->ok++;
				$this->header = '?mod=order&is_del=1';
				$this->error[] = '彻底删除订单成功！';
			}else{
				$this->check++;
				$this->error[] = '参数错误！';
			}
		}
	}
	
	function edit_order_status(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order_info = $this->get_order($order_id);
		$this->order_status_jurisdiction($order_info);
		if(!$this->check){
			require_once('mod/ajax_order_status.php');
		}else{
			echo '<p class="box_err">' . implode('<br />', $this->error);
			// unset($this->error);
		}
		// exit($str);
	}

    function edit_finance(){
        $finance_id = isset($_GET['finance_id']) ? trim($_GET['finance_id']) : 0;
		$order_id = isset($_GET['order_id']) ? trim($_GET['order_id']) : 0;
		$params = explode(",",$finance_id);
		if (empty($params['1']) && $order_id==0) {
			$param_info = explode("_",$finance_id);
			$customer_id = $param_info['0'];
			$order_month = $param_info['1'];
			$where  = "where customer_id='{$customer_id}' and order_create_month='{$order_month}' AND finance_status!=2";

			$temp_sql = 'select sum(price) as should_gain,SUM(weight) AS total_weight,
				customer_id,order_create_month,pay_type,sum(sy_price) as total_sy_price,
				count(order_id) as num from esys_order ' . $where. ' group by customer_id,order_create_month';

			$should_gain =  $this->db->get_row($temp_sql);

			if(!empty($should_gain)){
				require_once('mod/ajax_order_finance.php');
			}else{
				echo '<p class="box_err">系统繁忙，请稍后重试。</p>';
			}
		} else {
			require_once('mod/ajax_order_finance.php');
		}

    }

	public function upload_zip($upload_file)
	{
		$path = str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/";
		$tname = $upload_file["tmp_name"];
		
		//$fname = iconv("UTF-8", "gbk", $upload_file["name"]); 
		$fname = $upload_file["name"];
		$res = array('code' => 0, 'msg' => '成功');
		$type_wj = pathinfo($fname, PATHINFO_EXTENSION); //获取文件类型

		$zip_path = $path.'file/zip/'.date('Y-m-d');
		is_dir($zip_path) OR mkdir($zip_path, 0777, true); 
		$zip_file_path = $path.'file/zip_file/'.date('Y-m-d').'/'.substr($fname,0,strrpos($fname,"."));
		is_dir($zip_file_path) OR mkdir($zip_file_path, 0777, true); 
			
		 //判断文件类型
		$zip_file = $zip_path.'/'.$fname;
		if(!file_exists($zip_file))
		{
			if(strtolower($type_wj) == "zip" || strtolower($type_wj) == "rar")
			{
				
				move_uploaded_file($tname,$zip_file); 
				
				if (strtolower($type_wj) == "zip")
				{
					$zip = new ZipArchive; 
					$res_status = $zip->open($zip_file); 
					 if ($res_status === TRUE) { 
						 //解压缩到test文件夹 
						$zip->extractTo($zip_file_path); 
						$zip->close();
						//$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.iconv("gbk", "UTF-8", $fname);
						$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.$fname;
						
					 } else { 
						 $res['code'] = 1;
						 $res['msg'] = '请稍后再试！';
					 } 
				}
				else
				{	
					//$obj=new com("wscript.shell");//使用PHP预定义的Com组件加载Shell,加载wscript.shell用来执行dos命令的组件
					//$obj->run("winrar x $zip_path\\".$fname." ".$zip_file_path,0,true);//所要执行的命
					$locale = 'zh_CN.UTF-8';
					setlocale(LC_ALL, $locale);
					putenv('LC_ALL='.$locale);
					exec("rar x {$zip_file} {$zip_file_path}/");
					$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.$fname;
				}
				exec("chmod -R 777 {$zip_file_path}");
					//exec("chmod -R 777 {$zip_file}");
				
			 }
			 else
			 {
				$res['code'] = 1;
				$res['msg'] = '上传压缩包格式不正确，只限zip或rar！';
				
			 }
		}
		else{
			$res['data']['path'] = '/file/zip/'.date('Y-m-d').'/'.$fname;
		}
		 return $res;
	}

	function del_attch($path)
	{
		$base_path = str_replace('\\','/',realpath(dirname(__FILE__)))."/";
		if(!empty($path))
		{
			unlink($base_path.$path);  
			$dir = $this->get_file_path($path);
			return $this->del_zip_file($dir);
		}
	}

	function del_zip_file($dir)
	{
		$dh = opendir($dir);  
		while ($file = readdir($dh)) {  
			if($file != "." && $file!="..") {  
				$fullpath = $dir."/".$file;  
				if(!is_dir($fullpath)) {  
					unlink($fullpath);  
				} else {  
					$this->del_zip_file($fullpath);  
				}  
			}  
		}  
		closedir($dh);  
		//删除当前文件夹：  
		if(rmdir($dir)) {  
			return true;  
		} else {  
			return false;  
		} 
	}

	function get_file_path($path)
	{
		$file1 = array_pop(explode("/zip/",$path));
		$pos = strrpos($file1, '.');
		$file2 = substr($file1,0,$pos);
		$path = str_replace('\\','/',realpath(dirname(__FILE__)))."/";
		$dir_path = $path.'file/zip_file/'.$file2;
		return $dir_path;
	}

	// 统计每个客户的待收款
	function should_gain_total(){

		$where = 'where 1=1 AND finance_status!=2 AND is_del = 0 ';
		if (!empty($_GET['customer_id'])) {
			$where .= ' and customer_id in (' . $_GET['customer_id'] . ')';
		}
		if(in_array($this->member_info['group_id'], array(1))){
			$where .= ' and member_id ='.$this->mid;
		}

		// 下单时间
		$order_start_time = isset($_GET['order_start_time']) ? strtotime($_GET['order_start_time']) : 0;
		$order_end_time = isset($_GET['order_end_time']) && $_GET['order_end_time'] ? strtotime($_GET['order_end_time'])+86400 : 0;
		if($order_start_time) $where .= ' and order_time >= ' . $order_start_time;
		if($order_end_time) $where .= ' and order_time <= ' . $order_end_time;
        if(!empty($_GET['pay_type'])) $where .= " and pay_type = '" . trim($_GET['pay_type'])."'";

		$temp_sql = 'select sum(IF(`sy_price`>0,`sy_price`,price))  as should_gain,sum(price)  as total_price,SUM(weight) AS total_weight,
        customer_id,member_id,order_create_month,pay_type,sum(sy_price) as total_sy_price,
        count(order_id) as num from esys_order ' . $where. ' group by customer_id,order_create_month,member_id,pay_type order by order_create_month ASC';

		$sql = $this->sqllimit($temp_sql);
		//echo $sql;
		$list = $this->db->get_results($sql);
		if($list){
			$num_sql = "SELECT COUNT(*) AS num FROM ($temp_sql) AS temp";
			$total_num = $this->db->get_results($num_sql);
			$this->recordcount = $total_num[0]['num'];
			return $list;
		}
		//加一个订单月份和收款金额、收款状态
		return $list;
	}


	// 统计每个客户的待收款
	function settle_total(){

		$where = 'where 1=1 AND price>0  ';
		if (!empty($_GET['customer_id'])) {
			$where .= ' and customer_id in (' . $_GET['customer_id'] . ')';
		}

		if (!empty($_GET['finance_no'])) {
			$where .= "and finance_no={$_GET['finance_no']}";
		}

        if (!empty($_GET['pay_type'])) {
          $where .= "and pay_type={$_GET['pay_type']}";
        }

		if (!empty($_GET['member_id'])) {
			$where .= "and find_in_set({$_GET['member_id']},sale_member_id)";
		}
		// 回款时间
		if (!empty($_GET['start_settle_date']) ) {
			$start_settle_time = !empty($_GET['start_settle_date']) ? strtotime($_GET['start_settle_date']) : 0;

			$where .= " AND settle_time>={$start_settle_time}";
		}
		if(!empty( $_GET['end_settle_date'])){
			$end_settle_time = !empty( $_GET['end_settle_date']) ? strtotime($_GET['end_settle_date'])+86400 : 0;
			$where .= " AND settle_time<{$end_settle_time} ";
		}

		$temp_sql = 'select * from esys_finance ' . $where. 'order by id desc';

		$sql = $this->sqllimit($temp_sql);
		$list = $this->db->get_results($sql);
		if($list){
			$this->recordcount = $this->db->get_count($sql);
			return $list;
		}
		//加一个订单月份和收款金额、收款状态
		return $list;
	}


	function get_finance($finance_id){
		$sql = "select * from esys_finance where id='{$finance_id}' limit 1";

		$finance_no_info = $this->db->get_row($sql);
		return $finance_no_info;
	}
	function get_finance_orders($finance_no){
		$sql = "select * from esys_finance_order where finance_no='{$finance_no}'and is_del=0";

		$list = $this->db->get_results($sql);
		return $list;
	}
	function get_finance_orders_by_order_id($order_id) {
		$sql = "select * from esys_finance_order where order_id='{$order_id}'and is_del=0";

		$list = $this->db->get_results($sql);
		return $list;
	}

	function add_finance_order($map,$finance_id){
		if(is_array($map[0])) {
			foreach($map as $v)
			{
        $v['finance_id'] = $finance_id;
				$v['add_time'] = time();
				$this->db->insert('esys_finance_order', $v);
			}
		}else {
      $map['finance_id'] = $finance_id;
			$map['add_time'] = time();
			$this->db->insert('esys_finance_order', $map);
		}
	}
	function add_finance($customer_id,$finance_no,$price,$settle_time,$sale_member_id,$fin_month,$pay_type){
		$map['customer_id'] = $customer_id;
		$map['finance_no'] = $finance_no;
		$map['price'] = $price;
		$map['member_id'] = $this->mid;
		$map['settle_time'] = $settle_time;
		$map['sale_member_id'] = $sale_member_id;
		$map['fin_month'] = $fin_month;
        $map['pay_type'] = $pay_type;
		$map['add_time'] = time();
		return $this->db->insert('esys_finance', $map);
	}

	function reset_finance()
	{
		$finance_no = trim($_POST['finance_no']);
		$list = $this->get_finance_orders($finance_no);
		foreach($list as $v){
			$order_id = $v['order_id'];
			$order_info = $this->get_order($order_id);
			$update = array(
				'sy_price' => $v['price']+$order_info['sy_price'],
				'finance_status' => 1,
				'finance_no' =>  ''
			);
			$this->db->update('esys_order',$update, "where order_id ='{$order_id}'", $order_id);
		}

		$this->db->update('esys_finance_order', array('is_del'=>1), "where finance_no='{$finance_no}'",1);
		$this->db->update('esys_finance', array('price'=>0), "where finance_no='{$finance_no}'",1);
		$this->ok++;
		$this->error[] = '撤回成功！';

	}

	function reset_finance_by_order_id()
	{
		$order_id = trim($_POST['order_id']);
		$order_info = $this->get_order($order_id);
		$update = array(
			// 'sy_price' => $order_info['price'],
            'sy_price' => 0,
			'finance_status' => 0,
			'finance_no' =>  ''
		);
		$this->db->update('esys_order',$update, "where order_id ='{$order_id}'", $order_id);

		$temp_arr = array();
		$list = $this->get_finance_orders_by_order_id($order_id);
		foreach($list as $v){
			$temp_arr[$v['finance_id']] += $v['price'];
		}
		$this->db->update('esys_finance_order', array('is_del'=>1), "where order_id='{$order_id}'",1);
		foreach ($temp_arr as $k=>$v) {
			$info = $this->get_finance($k);
			$update = array('price'=> $info['price']-$v);
			$this->db->update('esys_finance', $update, "where finance_no='{$k}'",1);
		}
		$this->ok++;
		$this->error[] = '撤回成功！';

	}

	function get_finance_status_cn($finance_status){
		$arr = array(
			'0' => '未回款',
			'1' => '部分回款',
			'2' => '已回款',
		);
		if (!empty($arr[$finance_status])) {
			return $arr[$finance_status];
		} else {
			return '';
		}
	}

	function do_budong(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order_info = $this->get_order($order_id);
		$this->order_status_jurisdiction($order_info);
		if(!$this->check){
			require_once('mod/ajax_do_budong.php');
		}else{
			echo '<p class="box_err">' . implode('<br />', $this->error);
			// unset($this->error);
		}
		// exit($str);
	}

	function ajax_budong(){
		$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
		$bu_dong_img = isset($_POST['img']) ? $_POST['img'] : '';
		$map['bu_dong_img'] = implode(",",$bu_dong_img);
		
		$this->db->update('esys_order', $map, 'where order_id=' . $order_id, $order_id);
		$this->ok++;
		$this->error[] = '上传补洞图片成功！';
					
		
	}

    function work_order_types(){
       $res = $this->work_order_types;
        return $res;
    }

    // 修改订单
    function add_work_order(){
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order_info = $this->get_order($order_id);
        if(!$order_info) {
            $this->check++;
            $this->error[] = '未找到相关订单信息！';
        }
        $map['order_id'] = $order_id;
        $map['member_id'] = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
        $map['wo_type'] = isset($_POST['wo_type']) ? $this->safe($_POST['wo_type']) : '';

        $map['remarks'] = isset($_POST['remarks']) ? $this->safe($_POST['remarks']) : '';
        $map['img'] = isset($_POST['img']) ? $_POST['img'] : '';

        if(!$map['member_id']){
            $this->check++;
            $this->error[] = '没有选择负责人！';
        }
        if(!$map['wo_type']){
            $this->check++;
            $this->error[] = '没有选择类别！';
        }
        if(!$map['remarks']){
            $this->check++;
            $this->error[] = '备注不能为空！';
        }
//        if(!$map['img']){
//            $this->check++;
//            $this->error[] = '附件不能为空！';
//        }
        $map['add_time'] = time();
        $map['create_member_id'] = $this->mid;

        $edit_txt = '添加';
        if(!$this->check){
            $map['add_time'] = time();
            $this->db->insert('work_order', $map);
            $this->ok++;
            $this->error[] = $edit_txt . '工单创建成功！';
            $this->header = '?mod=work_order';
        }
    }

  // 读取列表数据
  function work_order_list(){
    $where = '1=1';
    // 负责人
    $member_id = isset($_GET['member_id']) ? $_GET['member_id'] : '';
    if(is_numeric($member_id)) $where .= ' and member_id = ' . $member_id;
    // 订单号
    $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
    if(is_numeric($order_id)) $where .= ' and order_id = ' . $order_id;

    $ordertype = ' id desc';
    $sql = 'select * from work_order ' . $where .' order by ' . $ordertype;

    $sql = $this->sqllimit($sql);
    $list = $this->db->get_results($sql);
    if($list){
      $this->recordcount = $this->db->get_count($sql);
      return $list;
    }
  }

}
?>