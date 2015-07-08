<?php

/* 
 * Вспомогательные функции
 * Автор: Сергей Керимов
 * E-mail: serker72@gmail.com
 */

// Чтение из БД параметров API для отправки SMS
function get_sms_api_options() {
    $sms_api_username = DB::GetQueryResult("SELECT name, var FROM `settings` WHERE `name` = 'sms_api_username' LIMIT 1", true);
    $sms_api_password = DB::GetQueryResult("SELECT name, var FROM `settings` WHERE `name` = 'sms_api_password' LIMIT 1", true);
    $sms_api_phone    = DB::GetQueryResult("SELECT name, var FROM `settings` WHERE `name` = 'sms_api_phone'    LIMIT 1", true);
    
    if (($sms_api_username['var'] !== '') && ($sms_api_password['var'] !== '') && ($sms_api_phone['var'] !== '')) {
        $ret = array(
            'sms_api_username' => $sms_api_username['var'],
            'sms_api_password' => $sms_api_password['var'],
            'sms_api_phone'    => $sms_api_phone['var'],
        );
    } else {
        $ret = array();
    }
    
    return $ret;
}

// Запись в БД параметров API для отправки SMS
function set_sms_api_options($sms_api_username, $sms_api_password, $sms_api_phone) {
    if (($sms_api_username !== '') && ($sms_api_password !== '') && ($sms_api_phone !== '')) {
        $fl1 = DB::Update('settings', 'sms_api_username', array('sms_api_username' => $sms_api_username), 'name');
        $fl2 = DB::Update('settings', 'sms_api_password', array('sms_api_password' => $sms_api_password), 'name');
        $fl3 = DB::Update('settings', 'sms_api_phone', array('sms_api_phone' => $sms_api_phone), 'name');
        
        return ($fl1 && $fl2 && $fl3);
    } else {
        return false;
    }
    
}
