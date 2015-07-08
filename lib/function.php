<?php
header('Content-type:text/html; charset=utf-8');
require_once(dirname(dirname(__FILE__)) . '/app.php');

require_once('sms_module.php');
require_once('ksk_functions.php');

/* СМС мастеру */
function send_sms_master($order_id){
    $sms_api_options = get_sms_api_options();
    
    if (count($sms_api_options) > 0) {
	//$from_sms_send = '79224717444';
	$from_sms_send = $sms_api_options['sms_api_phone'];

	$options_master = DB::GetQueryResult("SELECT name, var FROM `settings` WHERE `name` = 'sms_master' LIMIT 1", true);

	$sms_sended = DB::GetQueryResult("SELECT * FROM `sms_sended` WHERE order_id = ".$order_id." LIMIT 1", true);
	if(!$sms_sended){
		$order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$order_id." LIMIT 1", true);
		//Города
		$city_id = Utility::GetColumn($order, 'city_id');
		$city = Table::Fetch('city', $city_id);
		//Тип работ
		$work_id = Utility::GetColumn($order, 'work_type');
		$works = Table::Fetch('work_type', $work_id);

		if($order){
			$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
			//$send = new ComtubeSMS("Grad","qwerty-13579");
			//СМС - МАСТЕРУ
			$master = DB::GetQueryResult("SELECT * FROM `user` WHERE id = ".$order['master_name']." LIMIT 1", true);
			if($master && ($master['sms'] == 1) && ($master['phone'] != '')){

				$offer_num = DB::GetQueryResult("SELECT * FROM `order_offers` WHERE order_id = ".$order['id'], true);
				$offer_num = str_pad($offer_num['offer_id'], 7, '0', STR_PAD_LEFT) . PHP_EOL;
				$order['offer_number'] = $offer_num;				

				$sms_body = str_replace("%date%", $order['time_date'], $options_master['var']);
				$sms_body = str_replace("%time%", $order['time_time'], $sms_body);
				$sms_body = str_replace("%work_type%", $works[$order['work_type']]['name'], $sms_body);
				$sms_body = str_replace("%act%", $order['offer_number'], $sms_body);

				//Поселок
				if($order['city_id2'] != 0){
					$city2 = DB::GetQueryResult("SELECT * FROM `city` WHERE id = {$order['city_id2']}", true);
					$city_name2 = ','.$city2['name'];
				}else $city_name2 = '';

				if($order['flat'] != '' && $order['flat'] != NULL && $order['flat'] != 'NULL' ){
					$flat = ',кв.'.$order['flat'];
				}else $flat = '';

				if($order['corpus'] != '' && $order['corpus'] != NULL && $order['corpus'] != 'NULL' ){
					$corpus = ',кор.'.$order['corpus'];
				}else $corpus = '';


				$address = $city[$order['city_id']]['name'].$city_name2.',ул.'.$order['street'].', д.'.$order['house'].$flat.$corpus;
				$sms_body = str_replace("%address%", $address, $sms_body);
				$sms_body = str_replace("%phone%", ((substr($order['phone'], 0, 1) === '+') ? $order['phone'] : '+'.$order['phone']), $sms_body);
				$sms_body = str_replace("%note%", $order['note'], $sms_body);

				$text_master_send = iconv('utf-8', 'utf-8', $sms_body);
				//Отправляем смс если она не была отправлена ранее
				//$result = $send->send_sms($text_master_send,$master['phone'],$from_sms_send,'post');
				$result = $send->send_sms($text_master_send, $master['phone'], $from_sms_send);

				$res = DB::GetQueryResult("INSERT INTO `sms_sended` (`id`, `date`, `number`, `text`, `order_id`, `type`) VALUES (NULL, CURRENT_TIMESTAMP, '{$master['phone']}', '{$sms_body}', '{$order_id}', '1');", true);

			}
		}
	}
    }
}


/* СМС КЛИЕНТУ */
function send_sms_client($order_id, $number, $client_name){
    $sms_api_options = get_sms_api_options();
    
    if (count($sms_api_options) > 0) {
	//$from_sms_send = '79224717444';
	$from_sms_send = $sms_api_options['sms_api_phone'];
        
        // Проверим и добавим + к номеру
        //if (substr($number, 0, 1) !== '+') $number_to = '+'.$number;
        //else $number_to = $number;
        
	$options_client = DB::GetQueryResult("SELECT name, var FROM `settings` WHERE `name` = 'sms_client' LIMIT 1", true);
	$sms_sended = DB::GetQueryResult("SELECT * FROM `sms_sended` WHERE order_id = ".$order_id." AND `number` LIKE '{$number}' LIMIT 1", true);

	if(!$sms_sended){
		$order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$order_id." LIMIT 1", true);
		//Города
		$city_id = Utility::GetColumn($order, 'city_id');
		$city = Table::Fetch('city', $city_id);
		//Тип работ
		$work_id = Utility::GetColumn($order, 'work_type');
		$works = Table::Fetch('work_type', $work_id);

		if($order){
			$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
			//СМС - КЛИЕНТУ
			$sms_body_client = str_replace("%date%", $order['time_date'], $options_client['var']);
			$sms_body_client = str_replace("%time%", $order['time_time'], $sms_body_client);
			$sms_body_client = str_replace("%name%", $client_name, $sms_body_client);
			$sms_body_client = str_replace("%work_type%", $works[$order['work_type']]['name'], $sms_body_client);

			$text_client_send = iconv('utf-8', 'utf-8', $sms_body_client);

			$sms_sended_client = DB::GetQueryResult("SELECT * FROM `sms_sended` WHERE `number` LIKE '{$number}' ORDER BY `id` DESC LIMIT 1", true);
			$time_now = time();
			$time_order = strtotime($sms_sended_client['date']);
			$razn = $time_now - $time_order;

			if($razn >= 60){
				if($number_to != ''){
					$result = $send->send_sms($text_client_send, $number, $from_sms_send);
					$res1 = DB::GetQueryResult("INSERT INTO `sms_sended` (`id`, `date`, `number`, `text`, `order_id`, `type`) VALUES (NULL, CURRENT_TIMESTAMP, '{$number}', '{$sms_body_client}', '{$order_id}', '2');", true);
				}
			}
		}
	}
    }
}

/* СМС администрации */
function send_sms_admin($order_id){
    $sms_api_options = get_sms_api_options();
    
    if (count($sms_api_options) > 0) {
	//$from_sms_send = '79224717444';
	$from_sms_send = $sms_api_options['sms_api_phone'];
        
	$user_for_send = DB::GetQueryResult("SELECT * FROM `user` WHERE `rang` = 'admin' OR `rang` = 'operator' AND sms = 1", false);
	$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
	foreach ($user_for_send as $one) {
		if($one['phone'] != '' && strlen($one['phone']) > 5){
			$sms_body = 'В системе заказов новый необработанный заказ или сообщение';
			$text_master_send = iconv('utf-8', 'utf-8', $sms_body);
			//Отправляем смс если она не была отправлена ранее
			$result = $send->send_sms($text_master_send,$one['phone'],$from_sms_send);
		}
	}
    }
}

?>