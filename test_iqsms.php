<?php
header('Content-type:text/html; charset=utf-8');

include_once(dirname(__FILE__) . '/lib/iqsms_JsonGate.php');
include_once(dirname(__FILE__) . '/include/library/Utility.class.php');

$gate = new iqsms_JsonGate('z1469184353311', '948621');

if (date_default_timezone_get()) {
    echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
}

if (ini_get('date.timezone')) {
    echo 'date.timezone: ' . ini_get('date.timezone') . '<br />';
}

$now = date("Y-m-d H:i:s");
echo "<p>дата - $now</p>";

$dateTime = new DateTime("now", new DateTimeZone('GMT'));
$mysqldate = $dateTime->format("Y-m-d H:i:s");
echo "<p>дата для mysql - $mysqldate</p>";

echo '<p>узнаем текущий баланс JSON</p>';
var_dump($gate->credits()); // узнаем текущий баланс

echo '<p>узнаем текущий баланс REST</p>';
$rest_credits = Utility::HttpRequest('http://api.iqsms.ru/messages/v2/balance/', array('login' => 'z1469184353311', 'password' => '948621'));
$rest_credits = explode(';', $rest_credits);
var_dump($rest_credits);

echo '<p>получаем список доступных подписей</p>';
var_dump($gate->senders()); // получаем список доступных подписей

$messages = array(
            array(
           "clientId" => "1",
           "phone"=> "79222605250",
           "text"=> "Тестовое сообщение",
           //"sender"=> "TEST" 
       ),/*
       array(
           "clientId" => "2",
           "phone"=> "71234567891",
           "text"=> "second message", 
//           "sender"=> "TEST",
       ),
/*       array(
           "clientId" => "3",
           "phone"=> "71234567892",
           "text"=> "third message",
           "sender"=> "TEST",
       ),*/
   );
//var_dump($gate->send($messages, 'testQueue')); // отправляем пакет sms

/*$messages = array(
    array("clientId"=>"1","smscId"=>1885654369),
//    array("clientId"=>"2","smscId"=>11255143),
//    array("clientId"=>"3","smscId"=>11255144),
);*/

echo '<p>получаем статусы для пакета sms</p>';
var_dump($gate->status($messages)); // получаем статусы для пакета sms

echo '<p>получаем статусы из очереди testQueue</p>';
var_dump($gate->statusQueue('testQueue', 100)); // получаем статусы из очереди 'testQueue'
