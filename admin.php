<?php
require_once(dirname(__FILE__) . '/app.php');

need_login(true);

if($login_user['rang'] != 'admin') Utility::Redirect(WEB_ROOT .'/');

$d_end = date('t');
$d_start = '01';
$y_now = date('Y');
$m_now = date('m');

$start_time = mysql_real_escape_string($d_start.'.'.$m_now.'.'.$y_now);
$end_time = mysql_real_escape_string($d_end.'.'.$m_now.'.'.$y_now);

$user_man = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'manager'", false);
$user_mas = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'master'", false);

$array_man = array();
$array_mas = array();
$zp = 0;

	foreach ($user_man as $one) {
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND user_id =".$one['id'], true);
		$zp = ($sum['summ']*$one['stavka'])/100;
		$array_man[$one['id']]['id'] = $one['id'];
		$array_man[$one['id']]['name'] = $one['realname'].'('.$one['username'].')';
		$array_man[$one['id']]['zp'] = $zp;
	}

	$zp = 0;

	foreach ($user_mas as $two) {
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND master_name =".$two['id'], true);
		$zp = ($sum['summ']*$two['stavka'])/100;
		$array_mas[$two['id']]['id'] = $two['id'];
		$array_mas[$two['id']]['name'] = $two['realname'].'('.$two['username'].')';
		$array_mas[$two['id']]['zp'] = $zp;
	}

$cityes = DB::GetQueryResult("SELECT * FROM `city` WHERE `parent_id` = 0", false);

$user_add = DB::GetQueryResult("SELECT * FROM `user`", false);

include template('admin');