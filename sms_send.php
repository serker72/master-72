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

// Проверим статус ранее отправленных SMS
check_sms_msg_status();
//send_sms_msg();
//return;

$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE time_date != '' AND time_time != '' AND master_name != 0 AND street != ''", false);

foreach ($orders as $one) {
	$date_f = date_diff_f($now, $one['time_date']);
	if($date_f <= 86400 && $date_f > 82500){
		prepare_sms_master($one['id']);
		prepare_sms_client($one['id'], $one['phone'], $one['customer-name'], 3);
		if($one['phone2'] != '' && $one['sms2'] == 'on'){
			prepare_sms_client($one['id'], $one['phone2'], $one['customer-name2'], 4);
		}
		if($one['phone3'] != '' && $one['sms3'] == 'on'){
			prepare_sms_client($one['id'], $one['phone3'], $one['customer-name3'], 5);
		}
	}
}

// Отправим SMS
send_sms_msg();

// Проверим статус ранее отправленных SMS
check_sms_msg_status();

?>