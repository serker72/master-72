<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(dirname(__FILE__)) . '/app.php');
require_once(dirname(dirname(__FILE__)) . '/lib/ksk_functions.php');
require_once(dirname(dirname(__FILE__)) . '/include/classes/PHPExcel.php');

function GenPassword($p) {
	return md5($p.'@4!@#$%@');
}


$action = strval($_GET['action']);

if($action == "list"){
	
	$where = '';

	if($_POST){
		if($_POST['username'] != ''){
			$where .= 'AND username LIKE "'.$_POST['username'].'"';
		}
		if($_POST['realname'] != ''){
			$where .= 'AND realname LIKE "'.$_POST['realname'].'"';
		}
		if($_POST['rang'] != ''){
			$where .= 'AND rang LIKE "'.$_POST['rang'].'"';
		}
		if($_POST['phone'] != ''){
			$where .= 'AND phone LIKE "'.$_POST['phone'].'"';
		}
		if($_POST['dogovor'] != ''){
			$where .= 'AND dogovor LIKE "'.$_POST['dogovor'].'"';
		}
		if($_POST['address'] != ''){
			$where .= 'AND address LIKE "'.$_POST['address'].'"';
		}
		if($_POST['company'] != ''){
			$where .= 'AND company LIKE "'.$_POST['company'].'"';
		}										
	}

	$result = DB::GetQueryResult("SELECT * FROM `user` WHERE id != 0 {$where}", false);
		
	$rows = array();
	if($result){foreach($result as $one) {
	    $rows[] = $one;
	}}

	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	$jTableResult['Records'] = $rows;
	print json_encode($jTableResult);
}elseif($action == 'create'){

		if(isset($_POST['username'])) $username = $_POST['username'];
		else $username = '';

		if(isset($_POST['realname'])) $realname = $_POST['realname'];
		else $realname = '';

		if(isset($_POST['password'])) $password = GenPassword($_POST['password']);
		else $password = '';

		if(isset($_POST['rang'])) $rang = $_POST['rang'];
		else $rang = '';

		if(isset($_POST['phone'])) $phone = $_POST['phone'];
		else $phone = '';

		if(isset($_POST['sms'])) $sms = $_POST['sms'];
		else $sms = '';

		if(isset($_POST['dogovor'])) $dogovor = $_POST['dogovor'];
		else $dogovor = '';

		if(isset($_POST['address'])) $address = $_POST['address'];
		else $address = '';

		if(isset($_POST['stavka'])) $stavka = $_POST['stavka'];
		else $stavka = '';

		if(isset($_POST['company'])) $company = $_POST['company'];
		else $company = '';

		$user_id = DB::Insert('user', array(
			'username' => $username,
			'realname' => $realname,
			'password' => $password,
			'rang' => $rang,
			'phone' => $phone,
			'sms' => $sms,
			'dogovor' => $dogovor,
			'address' => $address,
			'stavka' => $stavka,
			'company' => $company
		));
		
		//Get last inserted record (to return to jTable)
		$result = DB::GetQueryResult("SELECT * FROM `user` WHERE id = LAST_INSERT_ID();", true);
		//$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $result;
		print json_encode($jTableResult);	
}elseif($action == 'delete'){
		$id = intval($_POST['id']);
		if($id){
			$order=DB::Query("DELETE FROM `user` WHERE `id` = ".$id, true);
			$jTableResult = array();
			$jTableResult['Result'] = "OK";
			print json_encode($jTableResult);
		}
}elseif($action == 'update'){
	$id = intval($_POST['id']);

	$user = DB::GetQueryResult("SELECT * FROM `user` WHERE id = ".$id, true);

	if($id){

		if($_POST['password'] == $user['password']){
			$password = $_POST['password'];
		}else{
			$password = GenPassword($_POST['password']);
		}

		if(isset($_POST['username'])) $username = $_POST['username'];
		else $username = '';

		if(isset($_POST['realname'])) $realname = $_POST['realname'];
		else $realname = '';

		if(isset($_POST['rang'])) $rang = $_POST['rang'];
		else $rang = '';

		if(isset($_POST['phone'])) $phone = $_POST['phone'];
		else $phone = '';

		if(isset($_POST['sms'])) $sms = $_POST['sms'];
		else $sms = '';

		if(isset($_POST['dogovor'])) $dogovor = $_POST['dogovor'];
		else $dogovor = '';

		if(isset($_POST['address'])) $address = $_POST['address'];
		else $address = '';

		if(isset($_POST['stavka'])) $stavka = $_POST['stavka'];
		else $stavka = '';

		if(isset($_POST['company'])) $company = $_POST['company'];
		else $company = '';


		Table::UpdateCache('user', $id, array(
			'username' => $username,
			'realname' => $realname,
			'password' => $password,
			'rang' => $rang,
			'phone' => $phone,
			'sms' => $sms,
			'dogovor' => $dogovor,
			'address' => $address,
			'stavka' => $stavka,
			'company' => $company
		));
	}

	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	print json_encode($jTableResult);

}elseif($action == 'onmanager'){

	$where = '';

	if($_GET['name'] != ''){
		$where .= 'AND realname LIKE "%'.$_GET['name'].'%"';
	}

	$user_man = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'manager' AND id != 0 {$where}", false);

	$array_man = array();

	//$start_time = mysql_real_escape_string($_GET['date1']);
	//$end_time = mysql_real_escape_string($_GET['date2']);
        
        $html = '';

	foreach ($user_man as $one) {
		//$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND user_id =".$one['id']." AND cost != 0", true);
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE user_id = ".$one['id']." AND cost != 0", true);
		$sum_pay = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `pay` WHERE user_id =".$one['id']." AND cost != 0", true);
		$zp_calc = ($sum['summ']*$one['stavka'])/100;
                if ($zp_calc > 0) {
                    $zp = $zp_calc - $sum_pay['summ'];
                    
                    $array_man[$one['id']]['id'] = $one['id'];
                    $array_man[$one['id']]['name'] = $one['realname'].'('.$one['username'].')';
                    $array_man[$one['id']]['zp_calc'] = round($zp_calc, 2);
                    $array_man[$one['id']]['zp_pay'] = round($sum_pay['summ'], 2);
                    $array_man[$one['id']]['zp'] = round($zp, 2);
                    
                    $html .= '<tr>';
                    $html .= '<td id="name_'.$one['id'].'">'.$one['realname'].' ('.$one['username'].')</td>';
                    $html .= '<td id="zp_calc_'.$one['id'].'">'.$zp_calc.'</td>';
                    $html .= '<td id="zp_pay_'.$one['id'].'">'.$sum_pay['summ'].'</td>';
                    $html .= '<td id="zp_'.$one['id'].'">'.$zp.'</td>';
                    $html .= '</tr>';
                }
	}

		/*$html .= '<h4>Подсчет з/п - Менеджеров</h4>';
		$html .= '<div class="div-manager-zp">';
		$html .= 'Даты: с <input type="text" id="date1" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="'.$start_time.'"> по <input type="text" id="date2" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="'.$end_time.'"><br/>Имя: <input type="text" id="manager-zp" name="manager-zp" value="'.$_GET['name'].'" /><a class="btn" href="#" onClick="onManager(); return false;" style="font-weight:normal; margin-top: -11px; margin-left: 20px;">Показать</a>';
		$html .= '<a class="btn" href="#" onClick="get_xls(\'manager\'); return false;">Скачать</a>';
		$html .='<table id="table-manager" class="table table-bordered">
				<tr>
					<td>ФИО</td>
					<td>З/П</td>
				</tr>';
		foreach ($array_man as $two) {
		$html	.= '<tr>
						<td id="name_'.$two['id'].'">'.$two['name'].'</td>
						<td id="zp_'.$two['id'].'">
							'.$two['zp'].'
						</td>
					</tr>';
				}
		$html	.= '</table>';
		$html .= '</div>';
		$html .= '<script type="text/javascript">';
		$html .= '$(document).ready(function () { $(\'#date1\').datepicker({format: \'dd.mm.yyyy\'}); $(\'#date2\').datepicker({format: \'dd.mm.yyyy\'}) });';
		$html .= '</script>';*/

		echo $html;
}elseif($action == 'onmaster'){

	$user_mas = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'master' AND id != 0 ", false);
	$array_mas = array();

	//$start_time = mysql_real_escape_string($_GET['date1']);
	//$end_time = mysql_real_escape_string($_GET['date2']);

	foreach ($user_mas as $two) {
		//$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND master_name =".$two['id'], true);
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE master_name =".$two['id'], true);
		$sum_pay = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `pay` WHERE user_id =".$two['id']." AND cost != 0", true);
		$zp_calc = ($sum['summ']*$two['stavka'])/100;
                
                if ($zp_calc > 0) {
                    $zp = $zp_calc - $sum_pay['summ'];
                    
                    $array_mas[$two['id']]['id'] = $two['id'];
                    $array_mas[$two['id']]['name'] = $two['realname'].'('.$two['username'].')';
                    $array_man[$two['id']]['zp_calc'] = round($zp_calc, 2);
                    $array_man[$two['id']]['zp_pay'] = round($sum_pay['summ'], 2);
                    $array_mas[$two['id']]['zp'] = round($zp, 2);
                    
                    $html .= '<tr>';
                    $html .= '<td id="name_'.$two['id'].'">'.$two['realname'].' ('.$two['username'].')</td>';
                    $html .= '<td id="zp_calc_'.$two['id'].'">'.round($zp_calc, 2).'</td>';
                    $html .= '<td id="zp_pay_'.$two['id'].'">'.round($sum_pay['summ'], 2).'</td>';
                    $html .= '<td id="zp_'.$two['id'].'">'.round($zp, 2).'</td>';
                    $html .= '</tr>';
                }
	}

		/*$html .= '<h4>Подсчет з/п - Мастеров с <input type="text" id="date3" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="'.$start_time.'"> по <input type="text" id="date4" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="'.$end_time.'"><a class="btn" href="#" onClick="onMaster(); return false;" style="font-weight:normal;">Показать</a></h4>';
		$html .= '<a class="btn" href="#" onClick="get_xls(\'master\'); return false;">Скачать</a>';
		$html .='<table id="table-manager" class="table table-bordered">
				<tr>
					<td>ФИО</td>
					<td>З/П</td>
				</tr>';
		foreach ($array_mas as $two) {
		$html	.= '<tr>
						<td id="name_'.$two['id'].'">'.$two['name'].'</td>
						<td id="zp_'.$two['id'].'">
							'.$two['zp'].'
						</td>
					</tr>';
				}
		$html	.= '</table>';
		$html .= '<script type="text/javascript">';
		$html .= '$(document).ready(function () { $(\'#date3\').datepicker({format: \'dd.mm.yyyy\'}); $(\'#date4\').datepicker({format: \'dd.mm.yyyy\'}) });';
		$html .= '</script>';*/

		//echo $html;
		die($html);
}elseif($action == 'pay'){

	$start_time = mysql_real_escape_string($_GET['date1']);
	$end_time = mysql_real_escape_string($_GET['date2']);
	
	$pays = DB::GetQueryResult("SELECT * FROM `pay` WHERE `date_start` >= '".$start_time."' AND `date_end` <= '".$end_time."' ORDER BY `id` DESC", false);

	$table = '<table class="table table-striped">';
	$table .= '<tr><td>Начало</td><td>Конец</td><td>Кому</td><td>Сколько</td></tr>';
	$i = 1;

	
	$pays_id = Utility::GetColumn($pays, 'user_id');
	$pays_table = Table::Fetch('user', $pays_id);

	if($pays){foreach($pays as $one) {
	    $table .= '<tr id="tr_id_'.$one['id'].'"><td>'.$one['date_start'].'</td><td>'.$one['date_end'].'</td><td>'.$pays_table[$one['user_id']]['realname'].'</td><td>'.$one['cost'].'</td></tr>';
	$i++;}}

	$table .= '</table>';
	die($table);
}elseif($action == 'post_pay'){
	$start_time = mysql_real_escape_string($_GET['date1']);
	$end_time = mysql_real_escape_string($_GET['date2']);
	$user_id = intval($_GET['user_id']);
	$cost = intval($_GET['cost']);

	$order_id = DB::Insert('pay', array(
			'admin_id' => $login_user['id'],
			'user_id' => $user_id,
			'cost' => $cost,
			'date_start' => $start_time,
			'date_end' => $end_time
	));
}elseif($action == 'city_add'){
	$name = strval($_GET['name']);
	$user_id = DB::Insert('city', array(
			'name' => $name,
			'parent_id' => 0
	));
	die();
}elseif($action == 'punkt_add'){
	$name = strval($_GET['name']);
	$city = intval($_GET['city']);

	$user_id = DB::Insert('city', array(
			'name' => $name,
			'parent_id' => $city
	));
	die();
}elseif($action == 'city_go'){
	$cityes = DB::GetQueryResult("SELECT * FROM `city` WHERE `parent_id` = 0", false);
	$table = '';
	foreach ($cityes as $one) {
		$table .= '<option value="'.$one['id'].'">'.$one['name'].'</option>';
	}
	echo $table;
}elseif($action == 'city_gos'){
	$cityes = DB::GetQueryResult("SELECT * FROM `city` WHERE `parent_id` = 0", false);
	$table = '';
	foreach ($cityes as $one) {
		$table .= '<option value="'.$one['id'].'">'.$one['name'].'</option>';
	}
	//echo '<select>'.$table.'</select>';
	die('<select>'.$table.'</select>');
}elseif($action == 'city_pos'){
	$cityes = DB::GetQueryResult("SELECT `id`,`name`, `parent_id`, if(`parent_id` = 0, `id`, `parent_id`) as new_parent_id FROM `city` ORDER by new_parent_id, `parent_id`, `name`", false);
	$table = '';
	foreach ($cityes as $one) {
		$table .= '<option value="'.$one['id'].'">'.($one['parent_id'] == 0 ? '### ' : '').$one['name'].($one['parent_id'] == 0 ? ' ###' : '').'</option>';
	}
	echo '<select>'.$table.'</select>';
}elseif($action == 'street_go'){
	$city=intval($_GET['city']);
	$cityes = DB::GetQueryResult("SELECT * FROM `city` WHERE `parent_id` = {$city}", false);
	$table = '<option value="">Выбрать</option>';
	foreach ($cityes as $one) {
		$table .= '<option value="'.$one['id'].'">'.$one['name'].'</option>';
	}
	echo $table;
}elseif($action == 'street_add'){
	
	$name = strval($_GET['name']);
	$city = intval($_GET['city']);
	$punkt = intval($_GET['punkt']);
	if($punkt == '') $punkt = $city;
	else  $punkt = $punkt;

	$user_id = DB::Insert('street', array(
		'name' => $name,
		'city_id' => $punkt
	));

	die();
} elseif($action == 'sms_api_options_change'){
	
	$sms_api_username = strval($_GET['sms_api_username']);
	$sms_api_password = strval($_GET['sms_api_password']);
	$sms_api_phone    = strval($_GET['sms_api_phone']);
        
        $ret_flag = set_sms_api_options($sms_api_username, $sms_api_password, $sms_api_phone);
        
        if ($ret_flag) {
            $html  = '<table class="table table-bordered" style="width: 400px;">';
            $html .= '<tr>';
            $html .= '<th>Параметр</th>';
            $html .= '<th>Значение</th>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>Логин</td>';
            $html .= '<td>'.$sms_api_username.'</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>Пароль</td>';
            $html .= '<td>'.$sms_api_password.'</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>N телефона</td>';
            $html .= '<td>'.$sms_api_phone.'</td>';
            $html .= '</tr>';
            $html .= '</table';
        } else {
            $html = '';
        }
       

	die($html);
}elseif($action == 'user_stat_update'){
    $result = DB::Query("DELETE FROM `user_stat`");
    
    $result = DB::Query("
INSERT INTO `user_stat`
SELECT u.`id`, u.`username`, u.`realname`, u.`rang`, u.`stavka`,
  (SELECT SUM(o.`cost`) FROM `order` o WHERE o.`user_id` = u.`id` AND o.`cost` <> 0),
  (SELECT ROUND(SUM(o.`cost`)*u.`stavka`/100, 2) FROM `order` o WHERE o.`user_id` = u.`id` AND o.`cost` <> 0) AS zp_calc,
  (SELECT SUM(p.`cost`) FROM `pay` p WHERE p.`user_id` = u.`id` AND p.`cost` <> 0),
  0
  FROM `user` u
  WHERE u.`rang` = 'manager'
  AND u.`stavka` > 0
  HAVING zp_calc > 0");
    
    $result = DB::Query("
INSERT INTO `user_stat`
SELECT u.`id`, u.`username`, u.`realname`, u.`rang`, u.`stavka`,
  (SELECT SUM(o.`cost`) FROM `order` o WHERE o.`master_name` = u.`id` AND o.`cost` <> 0),
  (SELECT ROUND(SUM(o.`cost`)*u.`stavka`/100, 2) FROM `order` o WHERE o.`master_name` = u.`id` AND o.`cost` <> 0) AS zp_calc,
  (SELECT SUM(p.`cost`) FROM `pay` p WHERE p.`user_id` = u.`id` AND p.`cost` <> 0),
  0
  FROM `user` u
  WHERE u.`rang` = 'master'
  AND u.`stavka` > 0
  HAVING zp_calc > 0");
    
    $result = DB::Query("UPDATE `user_stat` SET `zp_pay` = 0 WHERE `zp_pay` IS NULL");
    
    $result = DB::Query("UPDATE `user_stat` SET `zp` = `zp_calc` - `zp_pay`");
}elseif($action == 'user_list'){
	$users = DB::GetQueryResult("SELECT * FROM `user` WHERE `id` != 0 ORDER by `realname`", false);
	$table = '';
	foreach ($users as $one) {
		$table .= '<option value="'.$one['id'].'">'.$one['username'].' - '.$one['realname'].'</option>';
	}
	echo '<select>'.$table.'</select>';
}elseif($action == 'street_xls_import'){
    $ret_msg = '';
    $uploaddir = getcwd() . '/../static/uploads/';
    $uploadfile = $uploaddir . basename($_FILES['street_xls']['name']);
    
    if (is_writable($uploaddir)) {
        if (copy($_FILES['street_xls']['tmp_name'], $uploadfile))
        {
            // Сформируем массив имеющихся улиц для поиска
            $streets = DB::GetQueryResult("SELECT * FROM `street` ORDER by `city_id`, `name`", false);
            $street_array = array();
            foreach ($streets as $one) {
                $street_array[$one['id']] = $one['city_id'] . '-' . $one['name'];
            }
            
            // Чтение Excel
            // Создаем новый экземпляр Reader
            $objReader = PHPExcel_IOFactory::createReader(PHPExcel_IOFactory::identify($uploadfile));

            $spreadsheetInfo = $objReader->listWorksheetInfo($uploadfile);
            
            // Загружаем файл XLS
            $objPHPExcel = $objReader->load($uploadfile);
            
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getActiveSheetIndex());
            
            // Считываем значения
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            
            // Закрываем файл
            $objPHPExcel->disconnectWorksheets();
            
            $out_str = "";
            $add_rec = 0;
            $query = "INSERT INTO `street`(`city_id`, `name`) VALUES (#AAAA#, '#BBBB#')";
            
            for ($i = 0; $i < count($sheetData); $i++) {
                if (($sheetData[$i][0] != '') && ($sheetData[$i][1] != '')) {
                    // Поиск артикула в массиве товаров
                    $search_reference = array_search($sheetData[$i][0].'-'.$sheetData[$i][1], $street_array, true);
                    if ($search_reference !== false) {
                        
                    } else {
                        $out_str = str_replace('#AAAA#', $sheetData[$i][0], str_replace('#BBBB#', $sheetData[$i][1], $query)) . PHP_EOL;
                        $result = DB::Query($out_str);
                        if ($result) $add_rec++;
                    }
                }
            }
            
            /*if ($out_str !== "") {
                $result = DB::Query($out_str);
                $ret_msg = "Успешно добавлено $add_rec строк в таблицу !"; 
            }*/
            if ($add_rec > 0) {
                $ret_msg = "Успешно добавлено $add_rec строк в таблицу !"; 
            }
        }
        else { 
            $ret_msg = "Ошибка! Не удалось загрузить файл на сервер !"; 
        }
    } else {
        $ret_msg = 'Нет доступа на запись в каталог ' . $uploaddir;
    }
    
    //echo json_encode($ret_msg);
    echo $ret_msg;
}elseif($action == 'save_settings'){
    $err_array = array();
    foreach ($_POST as $key => $value) {
        $fl = DB::Update('settings', $key, array('var' => $value), 'name');
        if (!$fl) {
            $err_array[] = 'Ошибка обновления значения параметра '.$key;
        }
    }
    
    if (count($err_array) > 0) {
        $ret_msg = implode('<br>', $err_array);
    } else {
        $ret_msg = 'Настройки успешно сохранены...';
    }
    
    echo json_encode(array(
        'err_count' => count($err_array),
        'ret_msg' => $ret_msg,
    ));
}


?>