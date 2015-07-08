<?php

function getClientIp() {
		if (!empty($_SERVER["REMOTE_ADDR"])) {
			$Result = $_SERVER["REMOTE_ADDR"];
		} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  {
			$Result = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$Result = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (preg_match('/([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])){3}/', $Result, $Match)) {
			return $Match[0];
		}
		return $Result;
	}

function formatDate($date,$date2){
	$months = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
    $tmp=getdate($date);
    $tmp2=getdate($date2);
		if($tmp['mon']==$tmp2['mon'])  return "с ".$tmp['mday']." по ".$tmp2['mday']." ".$months[$tmp['mon']]; else
		return "с ".$tmp['mday']." ".$months[$tmp['mon']]." по ".$tmp2['mday']." ".$months[$tmp2['mon']];
	}
	
function formatDateDo($date){
	$months = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	$tmp=getdate($date);
	return "до ".$tmp['mday']." ".$months[$tmp['mon']];
}

function template($tFile) {
	global $INI;
	if ($INI['skin']['template']) {
		$templatedir = DIR_TEMPLATE. '/' . $INI['skin']['template'];
		$checkfile = $templatedir . '/html_header.html';
		if ( file_exists($checkfile) ) {
			return __template($INI['skin']['template'].'/'.$tFile);
		}
	}
	return __template($tFile);
}

function render($tFile, $vs=array()) {
    ob_start();
    foreach($GLOBALS AS $_k=>$_v) {
        ${$_k} = $_v;
    }
	foreach($vs AS $_k=>$_v) {
		${$_k} = $_v;
	}
	include template($tFile);
    return render_hook(ob_get_clean());
}

function redirect($url=null) {
    header("Location: {$url}");
    exit;
}

/* user relative */
function need_login($force=false) {

	if (isset($_GET['abc']))
	{
		print_r($_SESSION);
		die;
	}

	if ( isset($_SESSION['user_id']) ) {
		if (is_post()) {
			unset($_SESSION['loginpage']);
			unset($_SESSION['loginpagepost']);
		}
		return $_SESSION['user_id'];
	}
	if ( is_get() ) {
		Session::Set('loginpage', $_SERVER['REQUEST_URI']);
	} else {
		Session::Set('loginpage', $_SERVER['HTTP_REFERER']);
		Session::Set('loginpagepost', json_encode($_POST));
	}
	return redirect(WEB_ROOT . '/login.php?'.$_SERVER['QUERY_STRING']);
}

function is_admin() {
	global $login_user;
	if($login_user['rang'] == 'admin') return true;
	else return false;
}

function is_operator() {
	global $login_user;
	if($login_user['rang'] == 'operator') return true;
	else return false;
}

function is_manager() {
	global $login_user;
	if($login_user['rang'] == 'manager') return true;
	else return false;
}


function is_login() {
	return isset($_SESSION['user_id']);
}

function is_get() { return ! is_post(); }
function is_post() {
	return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
}

function RecursiveMkdir($path) {
	if (!file_exists($path)) {
		RecursiveMkdir(dirname($path));
		@mkdir($path, 0777);
	}
}

function cookieset($k, $v, $expire=0) {
	$pre = substr(md5($_SERVER['HTTP_HOST']),0,4);
	$k = "{$pre}_{$k}";
	if ($expire==0) {
		$expire = time() + 365 * 86400;
	} else {
		$expire += time();
	}
	setCookie($k, $v, $expire, '/');
}

function cookieget($k) {
	$pre = substr(md5($_SERVER['HTTP_HOST']),0,4);
	$k = "{$pre}_{$k}";
	return strval($_COOKIE[$k]);
}