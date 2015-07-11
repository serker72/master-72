<?php

header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache');
header('Content-transfer-encoding: binary');
header('Content-Disposition: attachment; filename=statistic.xls');
header('Content-Type: application/x-unknown');

require_once(dirname(__FILE__) . '/app.php');

need_login(true);

$action = $_GET['action'];

if($action == 'manager'){

	$where = '';

	//if($_GET['date1'] == '' || $_GET['date2'] == '') die('Нет даты');

	if($_GET['name'] != ''){
		$where .= 'AND realname LIKE "%'.$_GET['name'].'%"';
	}

	$user_man = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'manager' AND id != 0 {$where}", false);

	$array_man = array();

	//$start_time = mysql_real_escape_string($_GET['date1']);
	//$end_time = mysql_real_escape_string($_GET['date2']);

	//$html .= '<table><tr><td> Даты: с '.$start_time.' по '.$end_time.'</td></tr></table>';
	$html .= '<table class="table table-bordered" border="1" style="border: 1px solid #000;">';
	$html .= '<tr style="text-align: center;"><td style="width: 300px;padding: 0 2px;">ФИО</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">Рассчитано</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">Выплачено</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">З/П</td></tr>';

	foreach ($user_man as $one) {
		//$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND user_id =".$one['id']." AND cost != 0", true);
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE user_id =".$one['id']." AND cost != 0", true);
		$sum_pay = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `pay` WHERE user_id =".$one['id']." AND cost != 0", true);
		$zp_calc = ($sum['summ']*$one['stavka'])/100;
                
                if ($zp_calc > 0) {
                    $zp = $zp_calc - $sum_pay['summ'];
                    
                    $array_man[$one['id']]['id'] = $one['id'];
                    $array_man[$one['id']]['name'] = $one['realname'].' ('.$one['username'].')';
                    $array_man[$one['id']]['zp_calc'] = $zp_calc;
                    $array_man[$one['id']]['zp_pay'] = $sum_pay['summ'];
                    $array_man[$one['id']]['zp'] = $zp;
                }
	}

	foreach ($array_man as $two) {
		$html .= '<tr>';
		$html .= '<td>'.$two['name'].'</td>';
		$html .= '<td style="text-align: right;">'.$two['zp_calc'].'</td>';
		$html .= '<td style="text-align: right;">'.$two['zp_pay'].'</td>';
		$html .= '<td style="text-align: right;">'.$two['zp'].'</td>';
		$html .= '</tr>';
	}
	$html .= '</table>';

	echo $html;

}elseif($action == 'master'){

	//if($_GET['date1'] == '' || $_GET['date2'] == '') die('Нет даты');

	$user_mas = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'master' AND id != 0 ", false);
	$array_mas = array();

	//$start_time = mysql_real_escape_string($_GET['date1']);
	//$end_time = mysql_real_escape_string($_GET['date2']);

	//$html .= '<table><tr><td> Даты: с '.$start_time.' по '.$end_time.'</td></tr></table>';
	$html .= '<table class="table table-bordered" border="1" style="border: 1px solid #000;">';
	$html .= '<tr style="text-align: center;"><td style="width: 300px;padding: 0 2px;">ФИО</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">Рассчитано</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">Выплачено</td>';
        $html .= '<td style="width: 100px;padding: 0 2px;">З/П</td></tr>';

	foreach ($user_mas as $two) {
		//$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND master_name =".$two['id'], true);
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE master_name =".$two['id'], true);
		$sum_pay = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `pay` WHERE user_id =".$two['id']." AND cost != 0", true);
		$zp_calc = ($sum['summ']*$two['stavka'])/100;
                
                if ($zp_calc > 0) {
                    $zp = $zp_calc - $sum_pay['summ'];
                    
                    $array_mas[$two['id']]['id'] = $two['id'];
                    $array_mas[$two['id']]['name'] = $two['realname'].' ('.$two['username'].')';
                    $array_mas[$two['id']]['zp_calc'] = $zp_calc;
                    $array_mas[$two['id']]['zp_pay'] = $sum_pay['summ'];
                    $array_mas[$two['id']]['zp'] = $zp;
                
                    $html .= '<tr>';
                    $html .= '<td>'.$two['realname'].' ('.$two['username'].')'.'</td>';
                    $html .= '<td style="text-align: right;">'.$zp_calc.'</td>';
                    $html .= '<td style="text-align: right;">'.$sum_pay['summ'].'</td>';
                    $html .= '<td style="text-align: right;">'.$zp.'</td>';
                    $html .= '</tr>';
                }
	}

	$html .= '</table>';

	//echo $html;
	die($html);

}

?>