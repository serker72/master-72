<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(dirname(__FILE__)) . '/app.php');

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

	$start_time = mysql_real_escape_string($_GET['date1']);
	$end_time = mysql_real_escape_string($_GET['date2']);

	foreach ($user_man as $one) {
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND user_id =".$one['id']." AND cost != 0", true);
		$zp = ($sum['summ']*$one['stavka'])/100;
                if ($zp > 0) {
                    $array_man[$one['id']]['id'] = $one['id'];
                    $array_man[$one['id']]['name'] = $one['realname'].'('.$one['username'].')';
                    $array_man[$one['id']]['zp'] = $zp;
                }
	}

		$html .= '<h4>Подсчет з/п - Менеджеров</h4>';
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
		$html .= '</script>';

		echo $html;
}elseif($action == 'onmaster'){

	$user_mas = DB::GetQueryResult("SELECT * FROM `user` WHERE rang = 'master' AND id != 0 ", false);
	$array_mas = array();

	$start_time = mysql_real_escape_string($_GET['date1']);
	$end_time = mysql_real_escape_string($_GET['date2']);

	foreach ($user_mas as $two) {
		$sum = DB::GetQueryResult("SELECT SUM(cost) AS summ FROM `order` WHERE STR_TO_DATE(time_date, '%d.%m.%Y') BETWEEN STR_TO_DATE('".$start_time."', '%d.%m.%Y') AND STR_TO_DATE('".$end_time."', '%d.%m.%Y') AND master_name =".$two['id'], true);
		$zp = ($sum['summ']*$two['stavka'])/100;
		$array_mas[$two['id']]['id'] = $two['id'];
		$array_mas[$two['id']]['name'] = $two['realname'].'('.$two['username'].')';
		$array_mas[$two['id']]['zp'] = $zp;
	}

		$html .= '<h4>Подсчет з/п - Мастеров с <input type="text" id="date3" style="width:100px; border: none; background: transparent; text-decoration: none;cursor: pointer; box-shadow:none; margin-top: 10px;" value="'.$start_time.'"> по <input type="text" id="date4" style="width:100px; border: none; background: transparent; margin-top: 10px; text-decoration: none;cursor: pointer; box-shadow:none;" value="'.$end_time.'"><a class="btn" href="#" onClick="onMaster(); return false;" style="font-weight:normal;">Показать</a></h4>';
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
		$html .= '</script>';

		echo $html;
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
}


?>