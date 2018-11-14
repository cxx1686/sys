<?php
$xxx ='resultStatus={9000};memo={};result={service=\"mobile.securitypay.pay\"&payment_type=\"1\"&_input_charset=\"utf-8\"&partner=\"2088701764380956\"&seller_id=\"t@zuzuche.com\"&out_trade_no=\"1506185386901\"&subject=\"商品名：美国旧金山订单（59173413）租车预定费用,付款流水号：1506185386901\"&body=\"商品名：美国旧金山订单（59173413）租车预定费用,付款流水号：1506185386901\"&total_fee=\"0.01\"&notify_url=\"http%3A%2F%2Fpay.zuzuche.com%2FalipayMobile_notify.php\"&success=\"true\"&sign_type=\"RSA\"&sign=\"\"}';

$a ='resultStatus={9000};memo={};result={service=\"mobile.securitypay.pay\"&payment_type=\"1\"&_input_charset=\"utf-8\"&partner=\"2088701764380956\"&seller_id=\"t@zuzuche.com\"&out_trade_no=\"18092519523312428643\"&subject=\"商品名：美国洛杉矶租车订单,付款流水号：18092519523312428643\"&body=\"商品名：美国洛杉矶租车订单,付款流水号：18092519523312428643\"&total_fee=\"1540\"&notify_url=\"http://pay.zuzuche.com/alipayMobile_notify.php\"&it_b_pay=\"60m\"&success=\"true\"&sign_type=\"RSA\"&sign=\"aRIqcO10K1ZB/NLRD5yZl/wExjaSVr6b8awb2132VG99EXbiQcOKHPKIZNrKFsEHqUXnNEL5tuVrilk3UGZlxF532K065XHMrMOfByxyXU1vWyYwc nhbtNRG4ZbJ3bEWOuyZ3Q5HdbfREFVPIk1Qoh9hQyWQbh93M3SO/i9EfQ=\"}';

$data =  stripslashes($a);


$data=explode(";", $data);
print_r($data);
foreach($data as $v){
	if(preg_match("#(.*?)={(.*?)}#", $v,$t))$data_arr[$t[1]]=$t[2];

}
$data_arr['result']=explode("&", $data_arr['result']);

$temp=array();
foreach($data_arr['result'] as $v){
	if(preg_match('#(.*?)="(.*?)"#', $v,$t))$temp[$t[1]]=$t[2];
}
$data_arr['result']=$temp;
print_r($data_arr);