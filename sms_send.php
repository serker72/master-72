<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(__FILE__) . '/app.php');
require_once(dirname(__FILE__) . '/lib/iqsms_function.php');

function date_diff_f($date1, $date2){
    $diff = strtotime($date2) - strtotime($date1);
    return $diff;
}

$now = date("Y-m-d");

send_sms_msg();
return;

$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE time_date != '' AND time_time != '' AND master_name != 0 AND street != ''", false);

foreach ($orders as $one) {
	$date_f = date_diff_f($now, $one['time_date']);
	if($date_f <= 86400 && $date_f > 82500){
		send_sms_master($one['id']);
		send_sms_client($one['id'], $one['phone'], $one['customer-name']);
		if($one['phone2'] != '' && $one['sms2'] == 'on'){
			send_sms_client($one['id'], $one['phone2'], $one['customer-name2']);
		}
		if($one['phone3'] != '' && $one['sms3'] == 'on'){
			send_sms_client($one['id'], $one['phone3'], $one['customer-name3']);
		}
	}
}


?>