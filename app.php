<?php

require_once(dirname(__FILE__). '/include/application.php');

/* session,cache,configure,webroot register */
Session::Init();
$INI = ZSystem::GetINI();
$AJAX = ('XMLHttpRequest' == @$_SERVER['HTTP_X_REQUESTED_WITH']);
if (false==$AJAX) { 
    header('Content-Type: text/html; charset=UTF-8;'); 
} else {
    header("Cache-Control: no-store, no-cache, must-revalidate");
}
/* end */
$login_user_id = false;

$login_user_id = ZLogin::GetLoginId();
$login_user = Table::Fetch('user', $login_user_id);

	
/* not allow access app.php */
if($_SERVER['SCRIPT_FILENAME']==__FILE__){
	Utility::Redirect( WEB_ROOT . '/index.php');
}
/* end */
