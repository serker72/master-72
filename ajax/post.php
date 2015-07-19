<?php

ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

require_once(dirname(dirname(__FILE__)) . '/app.php');
include_once '../lib/function.php';

$order_status = array(
    "1" => "Выполнен",
    "2" => "Отменен",
    "3" => "Отказ",
    "4" => "Отсутствие заказчика",
);

function date_diff_f($date1, $date2){
    $diff = strtotime($date2) - strtotime($date1);
    return $diff;
}

if ($_POST) {

	if($_POST['action'] == 'add'){

		if(isset($_POST['city'])) $city_id = $_POST['city'];
		else $city_id = '';

		if(isset($_POST['city2'])) $city_id2 = $_POST['city2'];
		else $city_id2 = '';		

		if(isset($_POST['date'])) $date = $_POST['date'];
		else $date = '';

		if(isset($_POST['time'])) $time = $_POST['time'];
		else $time = '';

		if(isset($_POST['date_hope'])) $date_hope = $_POST['date_hope'];
		else $date_hope = '';

		if(isset($_POST['time_hope'])) $time_hope = $_POST['time_hope'];
		else $time_hope = '';

		if(isset($_POST['work_type'])) $work_type = $_POST['work_type'];
		else $work_type = '';		

		if(isset($_POST['master'])) $master = $_POST['master'];
		else $master = '';

		if(isset($_POST['master-name'])) $master_name = $_POST['master-name'];
		else $master_name = '';

		if(isset($_POST['master-name-two'])) $master_name_two = $_POST['master-name-two'];
		else $master_name_two = '';

		if(isset($_POST['master-name-th'])) $master_name_th = $_POST['master-name-th'];
		else $master_name_th = '';

		if(isset($_POST['cost'])) $cost = $_POST['cost'];
		else $cost = '';

		if(isset($_POST['customer-name'])) $client_fio = $_POST['customer-name'];
		else $client_fio = '';

		if(isset($_POST['customer-name2'])) $client_fio2 = $_POST['customer-name2'];
		else $client_fio2 = '';

		if(isset($_POST['customer-name3'])) $client_fio3 = $_POST['customer-name3'];
		else $client_fio3 = '';

		if(isset($_POST['customer-phone'])) $phone = $_POST['customer-phone'];
		else $phone = '';

		if(isset($_POST['customer-phone2'])) $phone2 = $_POST['customer-phone2'];
		else $phone2 = '';

		if(isset($_POST['customer-phone3'])) $phone3 = $_POST['customer-phone3'];
		else $phone3 = '';

		if(isset($_POST['street'])) $street = $_POST['street'];
		else $street = '';

		if(isset($_POST['house'])) $house = $_POST['house'];
		else $house = '';

		if(isset($_POST['corpus'])) $corpus = $_POST['corpus'];
		else $corpus = '';

		if(isset($_POST['flat'])) $flat = $_POST['flat'];
		else $flat = '';

		if(isset($_POST['customer-details'])) $detail = $_POST['customer-details'];
		else $detail = '';																		

		if(isset($_POST['note'])) $note = $_POST['note'];
		else $note = '';	

		if(isset($_POST['img'])) $img = $_POST['img'];
		else $img = '';			

		if(isset($_POST['img1'])) $img1 = $_POST['img1'];
		else $img1 = '';

		if(isset($_POST['img2'])) $img2 = $_POST['img2'];
		else $img2 = '';

		if(isset($_POST['img3'])) $img3 = $_POST['img3'];
		else $img3 = '';

		if(isset($_POST['img4'])) $img4 = $_POST['img4'];
		else $img4 = '';

		if(isset($_POST['img5'])) $img5 = $_POST['img5'];
		else $img5 = '';

		if(isset($_POST['img6'])) $img6 = $_POST['img6'];
		else $img6 = '';

		if(isset($_POST['img7'])) $img7 = $_POST['img7'];
		else $img7 = '';
		
		if(isset($_POST['sms2'])) $sms2 = $_POST['sms2'];
		else $sms2 = 'off';

		if(isset($_POST['sms3'])) $sms3 = $_POST['sms3'];
		else $sms3 = 'off';		

		$order_id = DB::Insert('order', array(
			'user_id' => $login_user['id'],
			'time_date' => $date,
			'time_time' => $time,
			'time_date_hope' => $date_hope,
			'time_time_hope' => $time_hope,
			'work_type' => intval($work_type),
			'master' => intval($master),
			'master_name' => intval($master_name),
			'cost' => intval($cost),
			'client_fio' => $client_fio,
			'client_fio2' => $client_fio2,
			'client_fio3' => $client_fio3,
			'phone' => $phone,
			'phone2' => $phone2,
			'phone3' => $phone3,
			'city_id' => $city_id,
			'city_id2' => $city_id2,
			'street' => $street,
			'house' => $house,
			'corpus' => $corpus,
			'flat' => $flat,
			'details' => $detail,
			'note' => $note,
			'img' => $img,
			'img1' => $img1,
			'img2' => $img2,
			'img3' => $img3,
			'img4' => $img4,
			'img5' => $img5,
			'img6' => $img6,
			'img7' => $img7,
			'sms2' => $sms2,
			'sms3' => $sms3
		));

		$offer = DB::Insert('order_offers', array(
			'order_id' => $order_id,
		));
//////////////
//die();
//////////////
		if(isset($date) && isset($time) && isset($master_name) && isset($street)){
			if($date != '' && $time != '' && $master_name != '' && $street != ''){
				$time_now = date('d-m-Y');
				$date_f = date_diff_f($time_now, $date);
				if($date_f <= 82000 && $date_f >= 0){
					//Отправляем смс
					send_sms_master($order_id);
					send_sms_client($order_id, $phone, $client_fio);
					if(isset($_POST['sms2']) && $_POST['sms2'] == 'on'){
						send_sms_client($order_id, $phone2, $client_fio2);
					}
					if(isset($_POST['sms3']) && $_POST['sms3'] == 'on'){
						send_sms_client($order_id, $phone3, $client_fio3);
					}

				}
			}
		}

		if($login_user['rang'] != 'admin' && $login_user['rang'] != 'operator'){
			send_sms_admin($order_id);
		}

		if($master_name_two != ''){
			$order_id_two = DB::Insert('order', array(
				'user_id' => $login_user['id'],
				'time_date' => $date,
				'time_time' => $time,
				'time_date_hope' => $date_hope,
				'time_time_hope' => $time_hope,
				'work_type' => intval($work_type),
				'master' => intval($master),
				'master_name' => intval($master_name_two),
				'cost' => intval($cost),
				'client_fio' => $client_fio,
				'client_fio2' => $client_fio2,
				'client_fio3' => $client_fio3,
				'phone' => $phone,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'city_id' => $city_id,
				'city_id2' => $city_id2,
				'street' => $street,
				'house' => $house,
				'corpus' => $corpus,
				'flat' => $flat,
				'details' => $detail,
				'note' => $note,
				'img' => $img,
				'img1' => $img1,
				'img2' => $img2,
				'img3' => $img3,
				'img4' => $img4,
				'img5' => $img5,
				'img6' => $img6,
				'img7' => $img7,
				'sms2' => $sms2,
				'sms3' => $sms3
			));

			$offer = DB::Insert('order_offers', array(
				'order_id' => $order_id_two,
			));


			if(isset($date) && isset($time) && isset($master_name_two) && isset($street)){
				if($date != '' && $time != '' && $master_name_two != '' && $street != ''){
					$time_now = date('d-m-Y');
					$date_f = date_diff_f($time_now, $date);
					if($date_f <= 82000 && $date_f >= 0){
						send_sms_client($order_id_two, $phone, $client_fio);
						//Отправляем смс
						send_sms_master($order_id_two);
						if(isset($_POST['sms2']) && $_POST['sms2'] == 'on'){
							send_sms_client($order_id_two, $phone2, $client_fio2);
						}
						if(isset($_POST['sms3']) && $_POST['sms3'] == 'on'){
							send_sms_client($order_id_two, $phone3, $client_fio3);
						}
					}					
				}
			}
		

		}

		if($master_name_th != ''){
			$order_id_th = DB::Insert('order', array(
				'user_id' => $login_user['id'],
				'time_date' => $date,
				'time_time' => $time,
				'time_date_hope' => $date_hope,
				'time_time_hope' => $time_hope,
				'work_type' => intval($work_type),
				'master' => intval($master),
				'master_name' => intval($master_name_th),
				'cost' => intval($cost),
				'client_fio' => $client_fio,
				'client_fio2' => $client_fio2,
				'client_fio3' => $client_fio3,
				'phone' => $phone,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'city_id' => $city_id,
				'city_id2' => $city_id2,
				'street' => $street,
				'house' => $house,
				'corpus' => $corpus,
				'flat' => $flat,
				'details' => $detail,
				'note' => $note,
				'img' => $img,
				'img1' => $img1,
				'img2' => $img2,
				'img3' => $img3,
				'img4' => $img4,
				'img5' => $img5,
				'img6' => $img6,
				'img7' => $img7,
				'sms2' => $sms2,
				'sms3' => $sms3
			));

			$offer = DB::Insert('order_offers', array(
				'order_id' => $order_id_th,
			));

			
			
				if(isset($date) && isset($time) && isset($master_name_th) && isset($street)){
					if($date != '' && $time != '' && $master_name_th != '' && $street != ''){
						$time_now = date('d-m-Y');
						$date_f = date_diff_f($time_now, $date);
						if($date_f <= 82000 && $date_f >= 0){
							send_sms_client($order_id_th, $phone3, $client_fio3);
							//Отправляем смс
							send_sms_master($order_id_th);
							if(isset($_POST['sms2']) && $_POST['sms2'] == 'on'){
								send_sms_client($order_id_th, $phone2, $client_fio2);
							}
							if(isset($_POST['sms3']) && $_POST['sms3'] == 'on'){
								send_sms_client($order_id_th, $phone3, $client_fio3);
							}							
						}
					}		
				}	
	
		}

		//die($order_id);
                die();
	} elseif($_POST['action'] == 'search'){

		$query = '';
		if($_POST['date'] != ''){
			$query .= ' AND time_date LIKE "%'.$_POST['date'].'%"';
		}
		if($_POST['time'] != ''){
			$query .= ' AND time_time LIKE "%'.$_POST['time'].'%"';
		}		
		if($_POST['work_type'] != ''){
			$query .= ' AND work_type = '.$_POST['work_type'];
		}
		if($_POST['master'] != ''){
			$query .= ' AND master = '.$_POST['master'];
		}
		if($_POST['master-name'] != ''){
			$query .= ' AND master_name LIKE "%'.$_POST['master-name'].'%"';
		}
		if($_POST['cost'] != ''){
			$query .= ' AND cost = '.$_POST['cost'];
		}
		if($_POST['customer-name'] != ''){
			$query .= ' AND ((client_fio LIKE "%'.$_POST['customer-name'].'%")';
			$query .= ' OR (client_fio2 LIKE "%'.$_POST['customer-name'].'%")';
			$query .= ' OR (client_fio3 LIKE "%'.$_POST['customer-name'].'%"))';
		}
		/*if($_POST['customer-name2'] != ''){
			$query .= ' AND client_fio2 LIKE "%'.$_POST['customer-name2'].'%"';
		}		
		if($_POST['customer-name3'] != ''){
			$query .= ' AND client_fio3 LIKE "%'.$_POST['customer-name3'].'%"';
		}*/				
		if($_POST['customer-phone'] != ''){
			$query .= ' AND ((phone LIKE "%'.$_POST['customer-phone'].'%")';
			$query .= ' OR (phone2 LIKE "%'.$_POST['customer-phone'].'%")';
			$query .= ' OR (phone3 LIKE "%'.$_POST['customer-phone'].'%"))';
		}
		/*if($_POST['customer-phone2'] != ''){
			$query .= ' AND phone2 LIKE "%'.$_POST['customer-phone2'].'%"';
		}
		if($_POST['customer-phone3'] != ''){
			$query .= ' AND phone3 LIKE "%'.$_POST['customer-phone3'].'%"';
		}*/				
		if($_POST['street'] != ''){
			$query .= ' AND street LIKE "%'.$_POST['street'].'%"';
		}
		if($_POST['house'] != ''){
			$query .= ' AND house LIKE "%'.$_POST['house'].'%"';
		}
		if($_POST['corpus'] != ''){
			$query .= ' AND corpus LIKE "%'.$_POST['corpus'].'%"';
		}
		if($_POST['flat'] != ''){
			$query .= ' AND flat LIKE "%'.$_POST['flat'].'%"';
		}
		if($_POST['customer-details'] != ''){
			$query .= ' AND details LIKE "%'.$_POST['customer-details'].'%"';
		}
		if (($_POST['status'] != '') && ($_POST['status'] != '0')) {
			$query .= ' AND status = '.$_POST['status'];
		}

		$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE time_date != '' ".$query." ORDER BY `id` DESC", false);
		$table = '<table class="table table-striped">';
		$i = 1;

		$master_id = Utility::GetColumn($orders, 'master');
		$masters = Table::Fetch('master', $master_id);

		$city_id = Utility::GetColumn($orders, 'city_id');
		$city = Table::Fetch('city', $city_id);

		$work_id = Utility::GetColumn($orders, 'work_type');
		$works = Table::Fetch('work_type', $work_id);

		if($orders){foreach($orders as $one) {
		    $table .= '<tr id="tr_id_'.$one['id'].'"><td onClick="editpost('.$one['id'].', \'search\');">';
                    
                    $st = $one['time_date'].' '.$works[$one['work_type']]['name'].' '.$masters[$one['master']]['name'].' '.$city[$one['city_id']]['name'].', '.$one['street'].', д.'.$one['house'].', корпус '.$one['corpus'].', кв.'.$one['flat'].', тел: '.$one['phone'];
                    $st .= ', '.($one['cost'] == 0 ? '<strong>сумма '.$one['cost'].'</strong>' : 'сумма '.$one['cost']);
                    
                    if($one['status'] > 0){
                        $st .= ', '.(($one['status'] == 2 || $one['status'] == 3) ? '<strong>статус '.$order_status[$one['status']].'</strong>' : 'статус '.$order_status[$one['status']]);
                    }
                    
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
		    $i++;
		}}
		$table .= '</table>';
		die($table);
	}elseif($_POST['action'] == 'edit'){
		$id = intval($_POST['order_id']);
		if($id){

			Table::UpdateCache('order', $id, array(
				'time_date' => $_POST['date'],
				'time_time' => $_POST['time'],
				'time_date_hope' => $_POST['date_hope'],
				'time_time_hope' => $_POST['time_hope'],
				'work_type' => intval($_POST['work_type']),
				'master' => intval($_POST['master']),
				'master_name' => intval($_POST['master-name']),
				'cost' => $_POST['cost'],
				'client_fio' => $_POST['customer-name'],
				'client_fio2' => $_POST['customer-name2'],
				'client_fio3' => $_POST['customer-name3'],
				'phone' => $_POST['customer-phone'],
				'phone2' => $_POST['customer-phone2'],
				'phone3' => $_POST['customer-phone3'],
				'city_id' => $_POST['city'],
				'city_id2' => $_POST['city2'],
				'street' => $_POST['street'],
				'house' => $_POST['house'],
				'corpus' => $_POST['corpus'],
				'flat' => $_POST['flat'],
				'details' => $_POST['customer-details'],
				'note' => $_POST['note'],
				'status' => $_POST['status'],
				'img' => $_POST['img'],
				'img1' => $_POST['img1'],
				'img2' => $_POST['img2'],
				'img3' => $_POST['img3'],
				'img4' => $_POST['img4'],
				'img5' => $_POST['img5'],
				'img6' => $_POST['img6'],
				'img7' => $_POST['img7']
			));
			$order_id = $id;

			$orders = DB::GetQueryResult("SELECT * FROM `order` WHERE id = {$id}", true);

		if(isset($_POST['city'])) $city_id = $_POST['city'];
		else $city_id = '';

		if(isset($_POST['city2'])) $city_id2 = $_POST['city2'];
		else $city_id2 = '';		

		if(isset($_POST['date'])) $date = $_POST['date'];
		else $date = '';

		if(isset($_POST['time'])) $time = $_POST['time'];
		else $time = '';

		if(isset($_POST['date_hope'])) $date_hope = $_POST['date_hope'];
		else $date_hope = '';

		if(isset($_POST['time_hope'])) $time_hope = $_POST['time_hope'];
		else $time_hope = '';

		if(isset($_POST['work_type'])) $work_type = $_POST['work_type'];
		else $work_type = '';		

		if(isset($_POST['master'])) $master = $_POST['master'];
		else $master = '';

		if(isset($_POST['master-name'])) $master_name = $_POST['master-name'];
		else $master_name = '';

		if(isset($_POST['master-name-two'])) $master_name_two = $_POST['master-name-two'];
		else $master_name_two = '';

		if(isset($_POST['master-name-th'])) $master_name_th = $_POST['master-name-th'];
		else $master_name_th = '';

		if(isset($_POST['cost'])) $cost = $_POST['cost'];
		else $cost = '';

		if(isset($_POST['customer-name'])) $client_fio = $_POST['customer-name'];
		else $client_fio = '';

		if(isset($_POST['customer-name2'])) $client_fio2 = $_POST['customer-name2'];
		else $client_fio2 = '';

		if(isset($_POST['customer-name3'])) $client_fio3 = $_POST['customer-name3'];
		else $client_fio3 = '';

		if(isset($_POST['customer-phone'])) $phone = $_POST['customer-phone'];
		else $phone = '';

		if(isset($_POST['customer-phone2'])) $phone2 = $_POST['customer-phone2'];
		else $phone2 = '';

		if(isset($_POST['customer-phone3'])) $phone3 = $_POST['customer-phone3'];
		else $phone3 = '';

		if(isset($_POST['street'])) $street = $_POST['street'];
		else $street = '';

		if(isset($_POST['house'])) $house = $_POST['house'];
		else $house = '';

		if(isset($_POST['corpus'])) $corpus = $_POST['corpus'];
		else $corpus = '';

		if(isset($_POST['flat'])) $flat = $_POST['flat'];
		else $flat = '';

		if(isset($_POST['customer-details'])) $detail = $_POST['customer-details'];
		else $detail = '';																		

		if(isset($_POST['note'])) $note = $_POST['note'];
		else $note = '';	

		if(isset($_POST['img'])) $img = $_POST['img'];
		else $img = '';			

		if(isset($_POST['img1'])) $img1 = $_POST['img1'];
		else $img1 = '';

		if(isset($_POST['img2'])) $img2 = $_POST['img2'];
		else $img2 = '';

		if(isset($_POST['img3'])) $img3 = $_POST['img3'];
		else $img3 = '';

		if(isset($_POST['img4'])) $img4 = $_POST['img4'];
		else $img4 = '';

		if(isset($_POST['img5'])) $img5 = $_POST['img5'];
		else $img5 = '';

		if(isset($_POST['img6'])) $img6 = $_POST['img6'];
		else $img6 = '';

		if(isset($_POST['img7'])) $img7 = $_POST['img7'];
		else $img7 = '';
		
		if(isset($_POST['sms2'])) $sms2 = $_POST['sms2'];
		else $sms2 = 'off';

		if(isset($_POST['sms3'])) $sms3 = $_POST['sms3'];
		else $sms3 = 'off';	

		if($master_name_th != ''){
			$order_id_th = DB::Insert('order', array(
				'user_id' => $orders['user_id'],
				'time_date' => $date,
				'time_time' => $time,
				'time_date_hope' => $date_hope,
				'time_time_hope' => $time_hope,
				'work_type' => intval($work_type),
				'master' => intval($master),
				'master_name' => intval($master_name_th),
				'cost' => intval($cost),
				'client_fio' => $client_fio,
				'client_fio2' => $client_fio2,
				'client_fio3' => $client_fio3,
				'phone' => $phone,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'city_id' => $city_id,
				'city_id2' => $city_id2,
				'street' => $street,
				'house' => $house,
				'corpus' => $corpus,
				'flat' => $flat,
				'details' => $detail,
				'note' => $note,
				'img' => $img,
				'img1' => $img1,
				'img2' => $img2,
				'img3' => $img3,
				'img4' => $img4,
				'img5' => $img5,
				'img6' => $img6,
				'img7' => $img7,
				'sms2' => $sms2,
				'sms3' => $sms3
			));

			$offer = DB::Insert('order_offers', array(
				'order_id' => $order_id_th,
			));
		}

		if($master_name_two != ''){
			$order_id_two = DB::Insert('order', array(
				'user_id' => $orders['user_id'],
				'time_date' => $date,
				'time_time' => $time,
				'time_date_hope' => $date_hope,
				'time_time_hope' => $time_hope,
				'work_type' => intval($work_type),
				'master' => intval($master),
				'master_name' => intval($master_name_two),
				'cost' => intval($cost),
				'client_fio' => $client_fio,
				'client_fio2' => $client_fio2,
				'client_fio3' => $client_fio3,
				'phone' => $phone,
				'phone2' => $phone2,
				'phone3' => $phone3,
				'city_id' => $city_id,
				'city_id2' => $city_id2,
				'street' => $street,
				'house' => $house,
				'corpus' => $corpus,
				'flat' => $flat,
				'details' => $detail,
				'note' => $note,
				'img' => $img,
				'img1' => $img1,
				'img2' => $img2,
				'img3' => $img3,
				'img4' => $img4,
				'img5' => $img5,
				'img6' => $img6,
				'img7' => $img7,
				'sms2' => $sms2,
				'sms3' => $sms3
			));

			$offer = DB::Insert('order_offers', array(
				'order_id' => $order_id_two,
			));
		}

/*
			if($_POST['date'] != '' && $_POST['time'] != '' && $_POST['master-name'] != '' && $_POST['street'] != ''){
				if($order_id){
					include_once '../lib/function.php';
					//Отправляем смс
					send_sms_serv($order_id);
				}
			}
*/			
		}
	}elseif($_POST['action'] == 'delete_order'){
		$id = intval($_POST['order_id']);
		if($id){
			$order=DB::Query("DELETE FROM `order` WHERE `id` = ".$id, true);
			//die('success');
		}
	}elseif($_POST['action'] == 'copy_order'){
		$id = intval($_POST['order_id']);
		if($id){
			$order = DB::GetQueryResult("SELECT * FROM `order` WHERE id = ".$id." LIMIT 1", true);
			if($order){

					$order_id = DB::Insert('order', array(
						'user_id' => $order['user_id'],
						'time_date' => $order['time_date'],
						'time_time' => $order['time_time'],
						'time_date_hope' => $order['time_date_hope'],
						'time_time_hope' => $order['time_time_hope'],
						'work_type' => intval($order['work_type']),
						'master' => intval($order['master']),
						'master_name' => $order['master_name'],
						'cost' => $order['cost'],
						'client_fio' => $order['client_fio'],
						'client_fio2' => $order['client_fio2'],
						'client_fio3' => $order['client_fio3'],
						'phone' => $order['phone'],
						'phone2' => $order['phone2'],
						'phone3' => $order['phone3'],
						'city_id' => $order['city_id'],
						'city_id2' => $order['city_id2'],
						'street' => $order['street'],
						'house' => $order['house'],
						'corpus' => $order['corpus'],
						'flat' => $order['flat'],
						'details' => $order['details'],
						'note' => $order['note'],
						'img' => $order['img'],
						'img1' => $order['img1'],
						'img2' => $order['img2'],
						'img3' => $order['img3'],
						'img4' => $order['img4'],
						'img5' => $order['img5'],
						'img6' => $order['img6'],
						'img7' => $order['img7']
					));	

					$offer = DB::Insert('order_offers', array(
						'order_id' => $order_id,
					));

				die(json_encode(array('order_id' => $order_id)));	
			}
		}
	}elseif($_POST['action'] == 'update_account'){
		$id = intval($_POST['user_id']);
		if($id){
			Table::UpdateCache('user', $id, array(
				'realname' => $_POST['fio'],
				'phone' => $_POST['phone'],
				'address' => $_POST['address']
			));
		}		
	}elseif($_POST['action'] == 'add_message'){
		$message = strval($_POST['message']);
		if($message){
			$order_id = DB::Insert('message', array(
				'user_id' => $login_user_id,
				'message' => $message,
				'for_user' => 100000
			));			
		}			
	}elseif($_POST['action'] == 'add_admin_message'){
		$user = $_POST['user'];
		$message = strval($_POST['message']);
		if($user != 'all'){
			if($message){
				$order_id = DB::Insert('message', array(
					'user_id' => 100000,
					'message' => $message,
					'for_user' => $user
				));			
			}	
		}else{
			$users = DB::GetQueryResult("SELECT * FROM `user` WHERE id != {$login_user['id']}", false);
			foreach ($users as $one) {
				$order_id = DB::Insert('message', array(
					'user_id' => 100000,
					'message' => $message,
					'for_user' => $one['id']
				));				
			}
		}		
	}
}