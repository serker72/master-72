<?php
header('Content-type:text/html; charset=utf-8');
require_once(dirname(dirname(__FILE__)) . '/app.php');

require_once('iqsms_JsonGate.php');
require_once('ksk_functions.php');

/********************** 
 * iqsms_msg.type
 * 1 - admin
 * 2 - master
 * 3 - client phone 1
 * 4 - client phone 2
 * 5 - client phone 3
 * 6 - admin balans add funds
 */

/* Создание СМС мастеру */
function prepare_sms_master($order_id){
    $settings = get_settings();
    
    $options_master = $settings['sms_master'];

    $sms_sended = DB::GetQueryResult("SELECT * FROM `iqsms_msg` WHERE order_id = ".$order_id." AND `type` = 2", true);
    
    if(!$sms_sended){
        $order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$order_id." LIMIT 1", true);
        //Города
        $city_id = Utility::GetColumn($order, 'city_id');
        $city = Table::Fetch('city', $city_id);
        //Тип работ
        $work_id = Utility::GetColumn($order, 'work_type');
        $works = Table::Fetch('work_type', $work_id);

        if($order){
            //$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
            //$send = new ComtubeSMS("Grad","qwerty-13579");
            //СМС - МАСТЕРУ
            $master = DB::GetQueryResult("SELECT * FROM `user` WHERE id = ".$order['master_name']." LIMIT 1", true);
            if($master && ($master['sms'] == 1) && ($master['phone'] != '')){
                $offer_num = DB::GetQueryResult("SELECT * FROM `order_offers` WHERE order_id = ".$order['id'], true);
                $offer_num = str_pad($offer_num['offer_id'], 7, '0', STR_PAD_LEFT) . PHP_EOL;
                $order['offer_number'] = $offer_num;				

                $sms_body = str_replace("%date%", $order['time_date'], $options_master);
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
                //$result = $send->send_sms($text_master_send, $master['phone'], $from_sms_send);

                $res = DB::GetQueryResult("INSERT INTO `iqsms_msg` (`id`, `order_id`, `dt_create`, `phone`, `text`, `type`) VALUES (NULL, '{$order_id}', CURRENT_TIMESTAMP, '{$master['phone']}', '{$sms_body}', '2');", true);
            }
        }
    }
}

/* Создание СМС КЛИЕНТУ */
function prepare_sms_client($order_id, $number, $client_name, $sms_type=3){
    $settings = get_settings();
	
    $options_client = $settings['sms_client'];
    
    //$from_sms_send = '79224717444';
    //$from_sms_send = $sms_api_options['sms_api_phone'];

    // Проверим и добавим + к номеру
    //if (substr($number, 0, 1) !== '+') $number_to = '+'.$number;
    //else $number_to = $number;
    //
    // Проверим и elfkbv + из номера
    if (substr($number, 0, 1) == '+') $number_to = str_replace('+', '', $number);
    else $number_to = $number;

    $sms_sended = DB::GetQueryResult("SELECT * FROM `iqsms_msg` WHERE order_id = ".$order_id." AND phone = '".$number_to."' AND `type` = ".$sms_type, true);

    if(!$sms_sended){
        $order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$order_id." LIMIT 1", true);
        //Города
        $city_id = Utility::GetColumn($order, 'city_id');
        $city = Table::Fetch('city', $city_id);
        //Тип работ
        $work_id = Utility::GetColumn($order, 'work_type');
        $works = Table::Fetch('work_type', $work_id);

        if($order){
            //$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
            //СМС - КЛИЕНТУ
            $sms_body_client = str_replace("%date%", $order['time_date'], $options_client);
            $sms_body_client = str_replace("%time%", $order['time_time'], $sms_body_client);
            $sms_body_client = str_replace("%name%", $client_name, $sms_body_client);
            $sms_body_client = str_replace("%work_type%", $works[$order['work_type']]['name'], $sms_body_client);

            $text_client_send = iconv('utf-8', 'utf-8', $sms_body_client);

            //$sms_sended_client = DB::GetQueryResult("SELECT * FROM `sms_sended` WHERE `number` LIKE '{$number_to}' ORDER BY `id` DESC LIMIT 1", true);
            //$time_now = time();
            //$time_order = strtotime($sms_sended_client['date']);
            //$razn = $time_now - $time_order;

            //if($razn >= 60){
                if($number_to != ''){
                    //$result = $send->send_sms($text_client_send, $number_to, $from_sms_send);
                    //$res = DB::GetQueryResult("INSERT INTO `sms_sended` (`id`, `date`, `number`, `text`, `order_id`, `type`) VALUES (NULL, CURRENT_TIMESTAMP, '{$number_to}', '{$sms_body_client}', '{$order_id}', '2');", true);
                    $res = DB::GetQueryResult("INSERT INTO `iqsms_msg` (`id`, `order_id`, `dt_create`, `phone`, `text`, `type`) VALUES (NULL, '{$order_id}', CURRENT_TIMESTAMP, '{$number_to}', '{$sms_body_client}', '{$sms_type}');", true);
                }
            //}
        }
    }
}

/* Создание СМС администрации */
function prepare_sms_admin($order_id, $msg_text='', $sms_type=1){
    //$settings = get_settings();
    
    //$from_sms_send = '79224717444';
    //$from_sms_send = $sms_api_options['sms_api_phone'];
    
    if (($msg_text == '') && ($order_id == 0)) {
        return false;
    } else if ($order_id == 0) {
        $sms_sended = false;
    } else if ($sms_type == 6) {
        $sms_sended = DB::GetQueryResult("SELECT * FROM `iqsms_msg` WHERE date(`dt_create`) = date(now()) AND `type` = 6", true);
    } else {
        $sms_sended = DB::GetQueryResult("SELECT * FROM `iqsms_msg` WHERE order_id = ".$order_id." AND `type` = 1", true);
    }
    
    if(!$sms_sended){
        $user_for_send = DB::GetQueryResult("SELECT * FROM `user` WHERE `rang` = 'admin' OR `rang` = 'operator' AND sms = 1", false);
        //$send = new Sms($sms_api_options['sms_api_username'], $sms_api_options['sms_api_password']);
        foreach ($user_for_send as $one) {
            if($one['phone'] != '' && strlen($one['phone']) > 5){
                if ($msg_text == '') {
                    $sms_body = 'TEST..В системе заказов новый необработанный заказ '.($order_id == 0 ? 'или сообщение' : '№'.$order_id);
                } else {
                    $sms_body = $msg_text;
                }
                $text_admin_send = iconv('utf-8', 'utf-8', $sms_body);
                //Отправляем смс если она не была отправлена ранее
                //$result = $send->send_sms($text_admin_send, $one['phone'], $from_sms_send);
                $res = DB::GetQueryResult("INSERT INTO `iqsms_msg` (`id`, `order_id`, `dt_create`, `phone`, `text`, `type`) VALUES (NULL, '{$order_id}', CURRENT_TIMESTAMP, '{$one['phone']}', '{$text_admin_send}', '1');", true);
            }
        }
    }
}

//
function get_sms_statuses() {
    $sms_statuses = array();
    
    $sms_statuses_q = DB::GetQueryResult("SELECT * FROM `iqsms_status`", false);
    
    if($sms_statuses_q){
        foreach ($sms_statuses_q as $one) {
            $sms_statuses[$one['id']] = $one['name'];
        }
    }
    
    return $sms_statuses;
}

// Отправка сообщений
function send_sms_msg($order_id=0){
    $packet_msg_count = 200;
    $settings = get_settings();
    
    if (($settings['sms_api_username'] == '') || ($settings['sms_api_password'] == '')) {
        echo '<p>Не указаны параметры для отправки СМС !</p>';
        return false;
    }
    
    $query = "SELECT * FROM `iqsms_msg` WHERE id_status IN (0,2,3,4,5,6,7,8)";
    
    if ($order_id > 0) {
        $query .= " AND order_id = ".$order_id;
    }
    
    // Получим список неотправленных или отправленных с ошибкой СМС
    $sms_not_sended = DB::GetQueryResult($query, false);
    if(!$sms_not_sended){
        return false;
    }
    
    $gate = new iqsms_JsonGate($settings['sms_api_username'], $settings['sms_api_password']);
    
    // узнаем текущий баланс
    $gate_credits = $gate->credits();
    
    // Проверим текущий баланс
    if ($gate_credits['credits'] == 0) {
        //echo '<p>Для отправки СМС необходимо пополнить баланс !</p>';
        return false;
    } else if ($gate_credits['credits'] <= (int)$settings['sms_api_min_balance']) {
        prepare_sms_admin(0, 'Баланс в сервисе IQSMS достиг минимума, пополните баланс.', 6);
    }
    
    // получаем список доступных подписей
    $gate_senders = $gate->senders();
    
    // Проверим доступность подписи из настроек
    if ($settings['sms_api_phone'] != '') {
        if (!in_array($settings['sms_api_phone'], $gate_senders['senders'])) {
            echo '<p>Неверная (незарегистрированная) подпись отправителя !</p>';
            return false;
        }
    }
    
    //test
    //return false;
    
    // Отправим неотправленные сообщения
    $messages = array();
    $message_count = 0;
    foreach ($sms_not_sended as $one) {
        $messages[] = array(
           "clientId" => $one['id'],
           "phone"=> $one['phone'],
           "text"=> $one['text'],
           //"sender"=> "TEST" 
        );
        
        if ($settings['sms_api_phone'] != '') {
           $messages[$message_count]['sender'] = $settings['sms_api_phone'];
        }
        
        $message_count++;
        
        if ($message_count == $packet_msg_count) {
            $ret_flag = send_sms_packet($gate, 'testQueue', $messages);
            if (!$ret_flag) {
                return false;
            }
            
            $messages = array();
            $message_count = 0;
        }
    }
    
    if ($message_count > 0) {
        $ret_flag = send_sms_packet($gate, 'testQueue', $messages);
        if (!$ret_flag) return false;
        else return true;
    }
    else return true;
}

// Отправка пакета сообщений
function send_sms_packet($gate, $queuename, $messages) {
    if (($gate == NULL) || ($queuename == '') || (count($messages) < 1)) {
        return false;
    }
    
    //var_dump($messages);
    // отправляем пакет sms
    $ret_messages = $gate->send($messages, $queuename);
    //var_dump($ret_messages);
    
    set_sms_msg_status($ret_messages);
    
    return true;
}

// Запись статусов отправленных сообщений
function set_sms_msg_status($messages) {
    $now = date("Y-m-d H:i:s");
    
    $sms_statuses = get_sms_statuses();
    
    foreach ($messages['messages'] as $key => $value) {
        $msg = DB::GetQueryResult("SELECT * FROM `iqsms_msg` WHERE `id` = " . $value['clientId'], true);
        if (!$msg) continue;
        
        $val_array = array();
        
        if (isset($value['smscId']) && ($msg['smscid'] !== $value['smscId'])) {
            $val_array['smscid'] = $value['smscId'];
        }
        
        $st = array_search($value['status'], $sms_statuses);
        
        if ($msg['id_status'] != $st) {
            $val_array['id_status'] = $st;
            $val_array['dt_status'] = $now;
            
            // Проверка наличия записей
            $rec_exist = DB::Exist('iqsms_msg_status', array(
                'id_msg' => $value['clientId'],
                'id_status' => $st,
            ));
            
            if (!$rec_exist) {            
                $st_id = DB::Insert('iqsms_msg_status', array(
                    'id_msg' => $value['clientId'],
                    'id_status' => $st,
                    'dt_status' => $now,
                ));
            }
        }
        
        if (count($val_array) > 0) {
            //Table::UpdateCache('iqsms_msg', $value['clientId'], $val_array);
            Table::Update('iqsms_msg', $value['clientId'], $val_array);
        }
    }

}


// Отправка сообщений
function check_sms_msg_status($order_id=0){
    $packet_msg_count = 200;
    $settings = get_settings();
    
    if (($settings['sms_api_username'] == '') || ($settings['sms_api_password'] == '')) {
        echo '<p>Не указаны параметры для отправки СМС !</p>';
        return false;
    }
    
    $query = "SELECT * FROM `iqsms_msg` WHERE id_status IN (1,9,12)";
    
    if ($order_id > 0) {
        $query .= " AND order_id = ".$order_id;
    }
    
    // Получим список отправленных СМС
    $sms_sended = DB::GetQueryResult($query, false);
    if(!$sms_sended){
        return false;
    }
    
    $gate = new iqsms_JsonGate($settings['sms_api_username'], $settings['sms_api_password']);
    
    // узнаем текущий баланс
    $gate_credits = $gate->credits();
    
    // Проверим текущий баланс
    if ($gate_credits['credits'] == 0) {
        //echo '<p>Для отправки СМС необходимо пополнить баланс !</p>';
        return false;
    } else if ($gate_credits['credits'] <= (int)$settings['sms_api_min_balance']) {
        //prepare_sms_admin(0, 'Баланс в сервисе IQSMS достиг минимума, пополните баланс.');
    }
    
    // получаем список доступных подписей
    /*$gate_senders = $gate->senders();
    
    // Проверим доступность подписи из настроек
    if ($settings['sms_api_phone'] != '') {
        if (!in_array($settings['sms_api_phone'], $gate_senders['senders'])) {
            echo '<p>Неверная (незарегистрированная) подпись отправителя !</p>';
            return false;
        }
    }*/
    
    // Проверим статус отправленных сообщений
    $messages = array();
    $message_count = 0;
    foreach ($sms_sended as $one) {
        $messages[] = array(
           "clientId" => $one['id'],
           "smscId"=> $one['smscid'],
        );
        
        $message_count++;
        
        if ($message_count == $packet_msg_count) {
            $ret_messages = $gate->status($messages);
            //var_dump($ret_messages);
            
            set_sms_msg_status($ret_messages);
            
            $messages = array();
            $message_count = 0;
        }
    }
    
    $ret_messages = $gate->status($messages);
    //var_dump($ret_messages);
    set_sms_msg_status($ret_messages);
}
