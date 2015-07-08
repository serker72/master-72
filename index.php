<?php
require_once(dirname(__FILE__) . '/app.php');

need_login(true);

if($login_user['rang'] == 'admin' || $login_user['rang'] == 'operator') Utility::Redirect(WEB_ROOT .'/operator.php');
elseif($login_user['rang'] == 'master') Utility::Redirect(WEB_ROOT .'/account.php');
elseif($login_user['rang'] == 'manager') Utility::Redirect(WEB_ROOT .'/account.php');

include template('index');
?>
