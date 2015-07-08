<?php
require_once(dirname(__FILE__) . '/app.php');

if(isset($_SESSION['user_id'])) {
	unset($_SESSION['user_id']);
	ZLogin::NoRemember($login_user_id);
}

redirect( WEB_ROOT . '/index.php');
