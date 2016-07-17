<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(dirname(__FILE__)) . '/app.php');

$order_status = array(
    "1" => "Выполнен",
    "2" => "Отменен",
    "3" => "Отказ",
    "4" => "Отсутствие заказчика",
);

$action = strval($_GET['action']);

if ($action == 'get_cities') {
    $city_id = abs(intval($_GET['city_id']));
    if(is_numeric($city_id) && $city_id != ''){
		$cityes = '<option value="" selected="selected">Выбрать</option>';
	    $city = DB::GetQueryResult("SELECT * FROM `city` WHERE parent_id =".$city_id, false);
	    if($city){foreach ($city as $one) {
	        $cityes .= '<option value="'.$one['id'].'">'.$one['name'].'</option>'; 
	    }}
	}else{
		$cityes = '<option value="" selected="selected">Нет населенных пунктов</option>';
	}
	die($cityes);
}elseif($action == 'get_orders'){

	$now = date('d.m.Y');
	$tommorow = time()+86000;
	$tommorow = date('d.m.Y', $tommorow);	

	if(isset($_GET['place'])){
		$place = $_GET['place'];
	}

	$query = '';

	if($_GET['user_id']){
		$user_id = intval($_GET['user_id']);
		$query .= " AND user_id=".$user_id;
	}
	
	if($_GET['filter_master'] != ''){
		$query .= " AND master_name=".$_GET['filter_master'];
	}

	/*
	if(isset($_GET['filter_date']) && isset($_GET['filter_date2']) && $_GET['filter_date'] != '' && $_GET['filter_date2'] != ''){
		$filter_date = $_GET['filter_date'];
		$filter_date2 = $_GET['filter_date2'];
		$query .= " AND STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$filter_date."', '%d.%m.%Y') AND STR_TO_DATE('".$filter_date2."', '%d.%m.%Y')";
	}else{
		$query .= " AND time_date LIKE '%".$now."%' OR time_date LIKE '%".$tommorow."%'";
	}

	die("SELECT * FROM `order` WHERE time_time = '' AND time_date = '' AND master_name = 0  {$query} ORDER BY `id` DESC LIMIT 0,25");
	*/
	$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE time_time = '' AND time_date = '' AND master_name = 0  {$query} ORDER BY STR_TO_DATE( time_date,  '%d.%m.%Y' ) DESC , STR_TO_DATE( time_time,  '%h:%i' ) DESC  LIMIT 0,25", false);
	
	$table = '<table class="table table-striped">';
	$i = 1;
	
	$master_id = Utility::GetColumn($orders, 'master');
	$masters = Table::Fetch('master', $master_id);

	$city_id = Utility::GetColumn($orders, 'city_id');
	$city = Table::Fetch('city', $city_id);

	$work_id = Utility::GetColumn($orders, 'work_type');
	$works = Table::Fetch('work_type', $work_id);

	if($orders){foreach($orders as $one) {
		if($place != 'account'){
			if($one['edited'] != 0 && $login_user['rang'] != 'manager'){
				$onclick = 'onClick="editpost('.$one['id'].', \'\', \'admin\');"';
			}elseif($one['edited'] == 0){
				$onclick = 'onClick="editpost('.$one['id'].', \'\', \'admin\');"';
			}else{
				$onclick = '';
			}
		}

		$user_name = DB::GetQueryResult("SELECT realname, username FROM `user` WHERE id = {$one['user_id']}", true);

		if($one['city_id2'] != 0){
			$city2 = DB::GetQueryResult("SELECT * FROM `city` WHERE id = {$one['city_id2']}", true);
			$city_name2 = $city2['name'].', ';
		}else $city_name2 = '';

	   	$table .= '<tr id="tr_id_'.$one['id'].'">';
	    $table .= '<td '.$onclick.'>';
/*            
            $table .= $one['time_date'].' '.$one['time_time'].' '.$works[$one['work_type']]['name'].' '.$masters[$one['master']]['name'].' '.$city[$one['city_id']]['name'].','.$city_name2;
	    $table .= ' '.$one['street'].', ';
	    if($one['house'] != '' && $one['house'] != NULL && $one['house'] != 'NULL' ){
	    	$table .= 'д.'.$one['house'].',';
	    }
	    if($one['corpus'] != '' && $one['corpus'] != NULL && $one['corpus'] != 'NULL' ){
	    	$table .= ' корпус '.$one['corpus'].',';
	    }
	    if($one['flat'] != '' && $one['flat'] != NULL && $one['flat'] != 'NULL' ){
	   		$table .= ' кв.'.$one['flat'].',';
	   	}
	    $table .= ' тел: '.$one['phone'];
            
	    if($one['status'] > 0){
	    	$table .= ', статус: '.$order_status[$one['status']];
	    }
            
	    $table .= ' - '.$user_name['realname'].'('.$user_name['username'].')';
 */
             $st = $one['time_date'].' '.$one['time_time'].' '.$works[$one['work_type']]['name'].' '.$masters[$one['master']]['name'].' '.$city[$one['city_id']]['name'].','.$city_name2;
	    $st .= ' '.$one['street'].', ';
	    if($one['house'] != '' && $one['house'] != NULL && $one['house'] != 'NULL' ){
	    	$st .= 'д.'.$one['house'].',';
	    }
	    if($one['corpus'] != '' && $one['corpus'] != NULL && $one['corpus'] != 'NULL' ){
	    	$st .= ' корпус '.$one['corpus'].',';
	    }
	    if($one['flat'] != '' && $one['flat'] != NULL && $one['flat'] != 'NULL' ){
	   	$st .= ' кв.'.$one['flat'].',';
            }
	    $st .= ' тел: '.$one['phone'];

            $st .= ', '.($one['cost'] == 0 ? '<strong>сумма '.$one['cost'].'</strong>' : 'сумма '.$one['cost']);
            
	    if($one['status'] > 0){
                $st .= ', '.(($one['status'] == 2 || $one['status'] == 3) ? '<strong>статус '.$order_status[$one['status']].'</strong>' : 'статус '.$order_status[$one['status']]);
	    }
            
	    $st .= ' - '.$user_name['realname'].'('.$user_name['username'].')';
            
            if ($one['cost'] == 0) {
                $table .= '<font color="red">'.$st.'</font></strong>';
            }
            else if (($one['status'] == 2) || ($one['status'] == 3)) {
                $table .= '<font color="#f6a828">'.$st.'</font>';
            }
            else {
                $table .= $st;
            }
           
	    $table .= '</td></tr>';


	$i++;}}
	$table .= '</table>';
	die($table);
}elseif($action == 'get_edit_order'){
	$id = intval($_GET['id']);
	$order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$id." LIMIT 1", true);
	$offer_num = DB::GetQueryResult("SELECT * FROM `order_offers` WHERE order_id = ".$order['id'], true);
	$offer_num = str_pad($offer_num['offer_id'], 7, '0', STR_PAD_LEFT) . PHP_EOL;
	$order['offer_number'] = $offer_num;

	die(json_encode($order));
}elseif($action == 'download'){

	if($_GET['user_id']){
		$user_id = intval($_GET['user_id']);
		if($user_id != $login_user['id']){ 
			die('Вы пытаете скачать файл другого пользователя!');
		}
		$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE user_id = ".$user_id,false);
	}

	header('Content-Type: text/html; charset=utf-8');
	header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');
	header('Content-transfer-encoding: binary');
	header('Content-Disposition: attachment; filename=orders.xls');
	header('Content-Type: application/x-unknown');

	$master_id = Utility::GetColumn($orders, 'master');
	$masters = Table::Fetch('master', $master_id);

	$city_id = Utility::GetColumn($orders, 'city_id');
	$city = Table::Fetch('city', $city_id);

	$work_id = Utility::GetColumn($orders, 'work_type');
	$works = Table::Fetch('work_type', $work_id);

	echo '<table border="1">';
		echo '<tr><td>Дата и время</td><td>Желаемые дата и время</td><td>Номер акта</td><td>Тип работ</td><td>Мастер</td><td>Сумма заказа</td><td>ФИО заказчика</td><td>Телефон</td><td>Город</td><td>Улица</td><td>Дом</td><td>Квартира</td><td>Корпус</td><td>Детали</td><td>Примечания</td><td>ФИО мастера</td></tr>';
		foreach ($orders as $one) {
			$offer_num = DB::GetQueryResult("SELECT * FROM `order_offers` WHERE order_id = ".$one['id'], true);
			$offer_num = str_pad($offer_num['offer_id'], 7, '0', STR_PAD_LEFT) . PHP_EOL;
			$one['offer_number'] = $offer_num;
			echo '<tr><td>'.$one['time_date'].' '.$one['time_time'].'</td><td>'.$one['time_date_hode'].' '.$one['time_time_hope'].'</td><td>'.$one['offer_number'].'</td><td>'.$works[$one['work_type']]['name'].'</td><td>'.$masters[$one['master']]['name'].'</td><td>'.$one['cost'].'</td><td>'.$one['client_fio'].'</td><td>'.$one['phone'].'</td><td>'.$city[$one['city_id']]['name'].'</td><td>'.$one['street'].'</td><td>'.$one['house'].'</td><td>'.$one['flat'].'</td><td>'.$one['corpus'].'</td><td>'.$one['details'].'</td><td>'.$one['note'].'</td><td>'.$one['master_name'].'</td></tr>';
		}
	echo '</table>';
}elseif($action == 'get_streets'){
	$q = addslashes($_GET['q']);

	$streets = DB::GetQueryResult("SELECT name, id FROM `street` WHERE name LIKE '%{$q}%' LIMIT 10",false);
	foreach ($streets as $st) {
		echo $st['name']."\n";
	}
}elseif($action == 'get_orders_done'){
	
	$now = date('d.m.Y');
	$tommorow = time()+86000;
	$tommorow = date('d.m.Y', $tommorow);

	$query = '';

	if($_GET['user_id']){
		$user_id = intval($_GET['user_id']);
		$query .= " AND user_id=".$user_id;
	}
	
	if($_GET['filter_master'] != ''){
		$query .= " AND master_name=".$_GET['filter_master'];
	}

	if($_GET['filter_date'] != '' && $_GET['filter_date2'] != ''){
		$filter_date = $_GET['filter_date'];
		$filter_date2 = $_GET['filter_date2'];
		$query .= " AND STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$filter_date."', '%d.%m.%Y') AND STR_TO_DATE('".$filter_date2."', '%d.%m.%Y')";
	}else{
		$query .= " AND STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$now."', '%d.%m.%Y') AND STR_TO_DATE('".$tommorow."', '%d.%m.%Y')";
	}
        
        if ($_GET['filter_cost_done']) {
            $query .= " AND cost = 0";
        }

	$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE master_name != 0 {$query} ORDER BY STR_TO_DATE( time_date,  '%d.%m.%Y' ) DESC , STR_TO_DATE( time_time,  '%h:%i' ) DESC LIMIT 0,25", false);

	
	$table = '<table class="table table-striped">';
	$i = 1;
	
	$master_id = Utility::GetColumn($orders, 'master');
	$masters = Table::Fetch('master', $master_id);

	$city_id = Utility::GetColumn($orders, 'city_id');
	$city = Table::Fetch('city', $city_id);

	$work_id = Utility::GetColumn($orders, 'work_type');
	$works = Table::Fetch('work_type', $work_id);

	if(isset($_GET['place'])){
		$place = $_GET['place'];
	}

	if($orders){foreach($orders as $one) {
		
		if($place != 'account'){
			if($one['edited'] != 0 && $login_user['rang'] != 'manager'){
				$onclick = 'onClick="editpost('.$one['id'].', \'admin\');"';
			}elseif($one['edited'] == 0){
				if($login_user['rang'] != 'manager'){
					$onclick = 'onClick="editpost('.$one['id'].', \'done\', \'admin\');"';
				}else{
					$onclick = 'onClick="editpost('.$one['id'].', \'done\', \'manager\');"';
				}
			}else{
				$onclick = '';
			}
		}else{
			if($login_user['rang'] == 'manager'){
				$onclick = 'onClick="give_img('.$one['id'].')"';
			}
		}

		$user_name = DB::GetQueryResult("SELECT realname, username FROM `user` WHERE id = {$one['user_id']}", true);


		if($one['city_id2'] != 0){
			$city2 = DB::GetQueryResult("SELECT * FROM `city` WHERE id = {$one['city_id2']}", true);
			$city_name2 = $city2['name'].', ';
		}else $city_name2 = '';

	    $table .= '<tr id="tr_id_'.$one['id'].'">';
	    $table .= '<td '.$onclick.'>';
            
            $st = $one['time_date'].' '.$one['time_time'].' '.$works[$one['work_type']]['name'].' '.$masters[$one['master']]['name'].' '.$city[$one['city_id']]['name'].','.$city_name2;
	    $st .= ' '.$one['street'].', ';
	    if($one['house'] != '' && $one['house'] != NULL && $one['house'] != 'NULL' ){
	    	$st .= 'д.'.$one['house'].',';
	    }
	    if($one['corpus'] != '' && $one['corpus'] != NULL && $one['corpus'] != 'NULL' ){
	    	$st .= ' корпус '.$one['corpus'].',';
	    }
	    if($one['flat'] != '' && $one['flat'] != NULL && $one['flat'] != 'NULL' ){
	   	$st .= ' кв.'.$one['flat'].',';
            }
	    $st .= ' тел: '.$one['phone'];

            $st .= ', '.($one['cost'] == 0 ? '<strong>сумма '.$one['cost'].'</strong>' : 'сумма '.$one['cost']);
            
	    if($one['status'] > 0){
                $st .= ', '.(($one['status'] == 2 || $one['status'] == 3) ? '<strong>статус '.$order_status[$one['status']].'</strong>' : 'статус '.$order_status[$one['status']]);
	    }
            
	    $st .= ' - '.$user_name['realname'].'('.$user_name['username'].')';
            
            if ($one['cost'] == 0) {
                $table .= '<font color="red">'.$st.'</font></strong>';
            }
            else if (($one['status'] == 2) || ($one['status'] == 3)) {
                $table .= '<font color="#f6a828">'.$st.'</font>';
            }
            else {
                $table .= $st;
            }
                    
	    $table .= '</td></tr>';

	$i++;}}
	$table .= '</table>';
	die($table);
}elseif($action == 'get_orders_master'){

	$now = date('d.m.Y');
	$tommorow = time()+86000;
	$tommorow = date('d.m.Y', $tommorow);

        
	$query = '';

	if($_GET['filter_date'] != '' && $_GET['filter_date2'] != ''){
		$filter_date = $_GET['filter_date'];
		$filter_date2 = $_GET['filter_date2'];
		$query .= " AND STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$filter_date."', '%d.%m.%Y') AND STR_TO_DATE('".$filter_date2."', '%d.%m.%Y')";
	}else{
		$query .= " AND STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$now."', '%d.%m.%Y') AND STR_TO_DATE('".$tommorow."', '%d.%m.%Y')";
	}
	
	if($_GET['user_id']){
		$user_id = intval($_GET['user_id']);
		$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE master_name=".$user_id." {$query} ORDER BY STR_TO_DATE( time_date,  '%d.%m.%Y' ) DESC , STR_TO_DATE( time_time,  '%h:%i' ) DESC LIMIT 0,25", false);
	}

	$table = '<table class="table table-striped">';
	$i = 1;
	
	$master_id = Utility::GetColumn($orders, 'master');
	$masters = Table::Fetch('master', $master_id);

	$city_id = Utility::GetColumn($orders, 'city_id');
	$city = Table::Fetch('city', $city_id);

	$work_id = Utility::GetColumn($orders, 'work_type');
	$works = Table::Fetch('work_type', $work_id);

	if(isset($_GET['place'])){
		$place = $_GET['place'];
	}

	if($orders){foreach($orders as $one) {

		$offer_num = DB::GetQueryResult("SELECT * FROM `order_offers` WHERE order_id = ".$one['id'], true);
		if($offer_num){
			$offer_num = str_pad($offer_num['offer_id'], 7, '0', STR_PAD_LEFT) . PHP_EOL;
		}else{
			$offer_num = '';
		}

		if($place != 'account'){
			if($one['edited'] != 0 && $login_user['rang'] != 'manager'){
				$onclick = 'onClick="editpost('.$one['id'].', \'done\', \'admin\');"';
			}elseif($one['edited'] == 0){
				$onclick = 'onClick="editpost('.$one['id'].', \'done\', \'admin\');"';
			}else{
				$onclick = '';
			}
		}else{
			if($login_user['rang'] == 'manager'){
				$onclick = 'onClick="give_img('.$one['id'].')"';
			}
		}

		if($one['city_id2'] != 0){
			$city2 = DB::GetQueryResult("SELECT * FROM `city` WHERE id = {$one['city_id2']}", true);
			$city_name2 = $city2['name'].', ';
		}else $city_name2 = '';

	    $table .= '<tr id="tr_id_'.$one['id'].'">';
	    $table .= '<td '.$onclick.'>'.$one['time_date'].' '.$one['time_time'].' '.$works[$one['work_type']]['name'].' '.$masters[$one['master']]['name'].' '.$city[$one['city_id']]['name'].','.$city_name2;
	    $table .= ' '.$one['street'].', ';
	    if($one['house'] != '' && $one['house'] != NULL && $one['house'] != 'NULL' ){
	    	$table .= 'д.'.$one['house'].',';
	    }
	    if($one['corpus'] != '' && $one['corpus'] != NULL && $one['corpus'] != 'NULL' ){
	    	$table .= ' корпус '.$one['corpus'].',';
	    }
	    if($one['flat'] != '' && $one['flat'] != NULL && $one['flat'] != 'NULL' ){
	   		$table .= ' кв.'.$one['flat'].',';
	   	}
	    $table .= ' тел: '.$one['phone'].',';
		if($one['note'] != ''){
	    	$table .= ' примечание: '.$one['note'];
	    }
	    if($offer_num){
	    	$table .= ' '.$offer_num;
	    }
            
	    if($one['status'] > 0){
	    	$table .= ', Статус: '.$order_status[$one['status']];
	    }

	    $table .= '</td></tr>';
	$i++;}}
	$table .= '</table>';
	die($table);
}elseif($action == 'get_imgs'){

	if($_GET['id']){
                $img_count = 0;
		$id = intval($_GET['id']);
		$img = DB::GetQueryResult("SELECT * FROM `order` WHERE id=".$id, true);
		$data = '<div id="img_done_link" class="info-block">';
		if($img['img'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img'].'" target="_blank">Cтраница 1</a><br/>';
                        $img_count++;
		}
		if($img['img1'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img1'].'" target="_blank">Cтраница 2</a><br/>';
                        $img_count++;
		}
		if($img['img2'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img2'].'" target="_blank">Cтраница 3</a><br/>';
                        $img_count++;
		}
		if($img['img3'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img3'].'" target="_blank">Cтраница 4</a><br/>';
                        $img_count++;
		}
		if($img['img4'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img4'].'" target="_blank">Cтраница 5</a><br/>';
                        $img_count++;
		}		
		if($img['img5'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img5'].'" target="_blank">Cтраница 6</a><br/>';
                        $img_count++;
		}
		if($img['img6'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img6'].'" target="_blank">Cтраница 7</a><br/>';
                        $img_count++;
		}
		if($img['img7'] != ''){
			$data .= '<a href="/static/uploads/'.$img['img7'].'" target="_blank">Cтраница 8</a><br/>';
                        $img_count++;
		}												
		$data .= '</div>';
		die($data);
	}
}elseif($action == 'get_zp'){

		$start_time = mysql_real_escape_string($_GET['date_one']);
		$end_time = mysql_real_escape_string($_GET['date_two']);
		$m_start_time = mysql_real_escape_string($_GET['date_three']);

		if($login_user['rang'] == 'manager'){
			$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN '".$start_time."' AND '".$end_time."' AND user_id =".$login_user['id'], true);
		}else{
			$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN '".$start_time."' AND '".$end_time."' AND master_name =".$login_user['id'], true);
		}
		
		$sum_pay = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `pay` WHERE `date_start` BETWEEN '".$start_time."' AND '".$end_time."' AND `date_end` BETWEEN '".$start_time."' AND '".$end_time."' AND user_id =".$login_user['id']." AND cost != 0", true);
                
		$zp_calc = ($sum['summ']*$login_user['stavka'])/100;
                $zp = round($zp_calc - $sum_pay['summ'], 2);

		$html .= '<div>З/П с <input id="date1" name="date1" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="'.$start_time.'" > по <input id="date2" name="date2" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none;" value="'.$end_time.'" > <a hre="#" onClick="get_zp(); return false;" class="btn">Показать</a><br/>
		З/П: <span id="zp">'.$zp.'</span> руб. (начислено: '.$zp_calc.' руб., выплачено: '.($sum_pay['summ'] ? $sum_pay['summ'] : '0').' руб.)<br/></div>';
                
                // Вывод з/п помесячно
                $start_time1 = substr($m_start_time, 6, 4).substr($m_start_time, 3, 2);
                $end_time1 = substr($end_time, 6, 4).substr($end_time, 3, 2);
                $sql_str = "SELECT PERIOD_DIFF(".$end_time1.", ".$start_time1.")+1 AS mon_cnt";
                $mon_cnt = DB::GetQueryResult($sql_str, true);
                $mon_cnt = $mon_cnt['mon_cnt'];
                
                $sql_str = "SELECT PERIOD_ADD(".$start_time1.", `id`-1) AS `dt`, 
  LEFT(CONCAT(PERIOD_ADD(".$start_time1.", `id`-1)), 4) AS `year`, 
  RIGHT(CONCAT(PERIOD_ADD(".$start_time1.", `id`-1)), 2) AS `mon`, 
  CASE RIGHT(CONCAT(PERIOD_ADD(".$start_time1.", `id`-1)), 2)
    WHEN '01' THEN 'Январь'
    WHEN '02' THEN 'Февраль'
    WHEN '03' THEN 'Март'
    WHEN '04' THEN 'Апрель'
    WHEN '05' THEN 'Май'
    WHEN '06' THEN 'Июнь'
    WHEN '07' THEN 'Июль'
    WHEN '08' THEN 'Август'
    WHEN '09' THEN 'Сентябрь'
    WHEN '10' THEN 'Октябрь'
    WHEN '11' THEN 'Ноябрь'
    WHEN '12' THEN 'Декабрь'
    ELSE ''
  END AS `mes`,
  0.00 AS `summ`
  FROM `street` s
  WHERE `id` <= ".$mon_cnt.";"; 
                
// WHERE STR_TO_DATE(`time_date`, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y')
//  AND ".($login_user['rang'] == 'master' ? 'master_name' : 'user_id')." = ".$login_user['id']."
                $sum_year1 = DB::GetQueryResult($sql_str, false);
                
                $sum_year = array();
                foreach ($sum_year1 as $row) {
                    $sum_year[$row['dt']]['year'] = $row['year'];
                    $sum_year[$row['dt']]['mon']  = $row['mon'];
                    $sum_year[$row['dt']]['mes']  = $row['mes'];
                    $sum_year[$row['dt']]['summ'] = $row['summ'];
                }

                $sql_str = "SELECT CONCAT(RIGHT(`time_date`, 4), LEFT(SUBSTRING(`time_date`, 4), 2)) AS dt, 
  LEFT(SUBSTRING(`time_date`, 4), 2) AS mon, 
  SUM(o.cost) AS summ
  FROM `order` o
 WHERE STR_TO_DATE(`time_date`, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$m_start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y')
  AND ".($login_user['rang'] == 'master' ? 'master_name' : 'user_id')." = ".$login_user['id']."
  GROUP BY 1,2";
                $sum_year2 = DB::GetQueryResult($sql_str, false);
                
                foreach ($sum_year2 as $row) {
                    $sum_year[$row['dt']]['summ'] = $row['summ'];
                }
                
		$html .= '<div class="info-block" style="width: 400px; padding-top: 15px;">
			<table id="table-manager" class="table table-bordered">
                            <thead>
				<tr>
                                    <td colspan="3" style="text-align: center;">Информация о з/п за период '.$m_start_time.' - '.$end_time.'</td>
				</tr>
				<tr>
                                    <td style="text-align: center;">Год</td>
                                    <td style="text-align: center;">Месяц</td>
                                    <td style="text-align: center;">З/П</td>
				</tr>
                            </thead>
                            <tbody>';
                
                foreach ($sum_year as $row) {
                    $html .= '<tr>';
                    $html .= '<td>'.$row['year'].'</td>';
                    $html .= '<td>'.$row['mes'].'</td>';
                    $html .= '<td style="text-align: right;">'.round(($row['summ']*$login_user['stavka'])/100, 2).'</td>';
                    $html .= '</tr>';
                }
                
		/*$html .= '<tr>
                            <td colspan="2">Сумма выплат за период</td>
                            <td style="text-align: right;">'.($sum_pay['summ'] ? $sum_pay['summ'] : '0').'</td>
			</tr>';
		$html .= '</tbody></table></div>';
                */
                //
		$html .= '<script type="text/javascript">';
		$html .= '$(document).ready(function () { $(\'#date1\').datepicker({format: \'dd.mm.yyyy\', language: \'ru\', autoclose: true}); $(\'#date2\').datepicker({format: \'dd.mm.yyyy\', language: \'ru\', autoclose: true}) });';
		$html .= '</script>';

		echo $html;
}elseif($action == 'get_offer_number'){
	die();
	$ber = DB::GetQueryResult("SELECT offer_number FROM `order` WHERE offer_number != '' ORDER BY `id` DESC LIMIT 1", true);
	$num = (int) $ber['offer_number']+1;

	echo str_pad($num, 7, '0', STR_PAD_LEFT) . PHP_EOL; 
}elseif($action == 'get_messages'){
	$html = '';
	$mail = DB::GetQueryResult("SELECT * FROM message WHERE for_user = ".$login_user['id']." OR user_id = ".$login_user['id'], false);
	foreach ($mail as $one) {
		$html .= '<div style="width:500px; margin-bottom: 10px; ';
		if($one['for_user'] == $login_user['id']){
			$html .= 'background: #EEEEEE;';
		}

		if($one['user_id'] == 100000){
			$name = 'Администратора';
		}else{
			$name = 'Вас';
		}

		$html .='">';
		$html .='<div style="width:500px;">От:'.$name.'. Дата: '.$one['date'].'</div>';
		$html .='<div style="width:500px;">Сообщение: '.$one['message'].'</div>';
		$html .='</div>';
	}

	die($html);
}elseif($action == 'get_admin_messages'){
	$html = '';
	$mail = DB::GetQueryResult("SELECT * FROM message WHERE for_user = 100000 GROUP BY user_id", false);
	foreach ($mail as $one) {

		$user_name = DB::GetQueryResult("SELECT realname, username FROM user WHERE id = {$one['user_id']}", true);

		$html .= '<div onClick="get_all('.$one['user_id'].'); return false;" style="width:400px; margin-bottom: 10px; ';
		if($one['for_user'] == $login_user['id']){
			$html .= 'background: #EEEEEE;';
		}

		$name = $user_name['realname'].'('.$user_name['username'].')';

		$html .='">';
		$html .='<div style="width:400px;">От:'.$name.'. Дата: '.$one['date'].'</div>';
		$html .='<div style="width:400px;">Сообщение: '.$one['message'].'</div>';
		$html .='</div>';
	}

	die($html);
}elseif($action == 'get_admin_one_message'){
	$html = '';

	$id = $_GET['id'];

	$mail = DB::GetQueryResult("SELECT * FROM message WHERE for_user = 100000 AND user_id = {$id} OR for_user = {$id}", false);
	foreach ($mail as $one) {

		$user_name = DB::GetQueryResult("SELECT realname, username FROM user WHERE id = {$one['user_id']}", true);

		$html .= '<div style="width:400px; margin-bottom: 10px; ';
		if($one['for_user'] == $login_user['id']){
			$html .= 'background: #EEEEEE;';
		}

		if($one['user_id'] == 100000){
			$name = 'Администратора';
		}else{
			$name = $user_name['realname'].'('.$user_name['username'].')';
		}

		$html .='">';
		$html .='<div style="width:400px;">От:'.$name.'. Дата: '.$one['date'].'</div>';
		$html .='<div style="width:400px;">Сообщение: '.$one['message'].'</div>';
		$html .='</div>';
	}

	die($html);
}elseif($action == 'get_contacts_messages'){
	$html = '';
	$mail = DB::GetQueryResult("SELECT u.`id`, u.`username`, u.`realname`,
(SELECT count(m.`id`) FROM `message` m WHERE m.`user_id`=u.`id` AND m.`is_read`=0) as count_send_not_read_msg,
(SELECT count(m.`id`) FROM `message` m WHERE m.`user_id`=u.`id`) as count_send_msg,
(SELECT count(m.`id`) FROM `message` m WHERE m.`for_user`=u.`id`) as count_recieve_msg
FROM `user` u
WHERE u.`id`<>{$login_user['id']}
HAVING count_send_not_read_msg>0 OR count_send_msg>0 OR count_recieve_msg>0
ORDER BY 4 DESC,5 DESC,6 DESC, 2 ASC", false);

	foreach ($mail as $one) {
            $html .= '<li id="href_contact_messages_'.$one['id'].'"><a href="" onclick="get_contact_messages('.$one['id'].', \''.$one['username'].'\'); return false;">'.$one['username'].'<br>'.$one['realname'];
            if ($one['count_send_not_read_msg'] > 0) $html .= ' ('.$one['count_send_not_read_msg'].')';
            $html .= '</a></li>';
	}
        
        if ($html !== '') $html = '<ul>'.$html.'</ul>';

	die($html);
}elseif($action == 'get_contact_messages'){
	$html = '';

	$id = $_GET['id'];

	$mail = DB::GetQueryResult("SELECT * FROM message WHERE for_user = 100000 AND user_id = {$id} OR for_user = {$id}", false);
	foreach ($mail as $one) {
		$user_name = DB::GetQueryResult("SELECT realname, username FROM user WHERE id = {$one['user_id']}", true);

		if($one['user_id'] == 100000){
                    $name = 'Администратор';
                    $html .= '<div class="div-send-message">';
                } else {
                    $name = $user_name['realname'].'('.$user_name['username'].')';
                    $html .= '<div class="div-recieve-message'.($one['is_read'] == 0 ? ' div-recieve-message-unread' : '').'">';
                }
                
		$html .= '<strong>'.$name.' '.$one['date'].'</strong><hr>';
                $html .= $one['message'];
		$html .='</div>';
                
                // Отмечаем как прочитанные сообщения
                if ($one['is_read'] == 0) {
                    $result = DB::Query("UPDATE `message` SET `is_read` = 1 WHERE `id` = {$one['id']}");
                }
	}

	die($html);
}