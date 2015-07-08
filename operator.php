<?php
require_once(dirname(__FILE__) . '/app.php');

need_login(true);

if($login_user['rang'] == 'master') Utility::Redirect(WEB_ROOT .'/account.php');

$work_types = DB::GetQueryResult("SELECT * FROM `work_type`", false);

$master = DB::GetQueryResult("SELECT * FROM `master`", false);

$city = DB::GetQueryResult("SELECT * FROM `city` WHERE parent_id = 0", false);

$users_master = DB::GetQueryResult("SELECT id,realname FROM `user` WHERE rang = 'master'", false);


include template('operator');