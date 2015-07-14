<?php
require_once(dirname(__FILE__) . '/app.php');

need_login(true);

if(($login_user['rang'] != 'master') && ($login_user['rang'] != 'manager')) Utility::Redirect(WEB_ROOT .'/');

$d_end = date('t');
$d_start = '01';
$y_now = date('Y');
$m_now = date('m');

$start_year_time = mysql_real_escape_string('01.01.'.$y_now);
$start_time = mysql_real_escape_string($d_start.'.'.$m_now.'.'.$y_now);
$end_time = mysql_real_escape_string($d_end.'.'.$m_now.'.'.$y_now);


include template('account');