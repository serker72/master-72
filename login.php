<?php
require_once(dirname(__FILE__) . '/app.php');

if($_POST){
	$login_user = ZUser::GetLogin(trim($_POST['username']), $_POST['password']);
	if(!$login_user ) {
		Session::Set('error', 'Ошибка входа!');
		Utility::Redirect(WEB_ROOT . '/login.php');
	}else{
		Session::Set('user_id', $login_user['id']);
		ZLogin::Remember($login_user);
      	Utility::Redirect(WEB_ROOT .'/');          
	}
}

include template('login');