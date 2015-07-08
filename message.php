<?php
require_once(dirname(__FILE__) . '/app.php');

need_login(true);

if($login_user['rang'] == 'admin' || $login_user['rang'] == 'operator'){
	$user = DB::GetQueryResult("SELECT * FROM `user` WHERE id != {$login_user['id']}", false);
	include template('admin_message');
}else{
	include template('message');
}