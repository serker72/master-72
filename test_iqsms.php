<?php
header('Content-type:text/html; charset=utf-8');

include_once(dirname(__FILE__) . '/lib/iqsms_JsonGate.php');

$gate = new iqsms_JsonGate('z1469184353311', '948621');

var_dump($gate->credits()); // узнаем текущий баланс
//var_dump($gate->senders()); // получаем список доступных подписей

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
);
var_dump($gate->status($messages)); // получаем статусы для пакета sms*/
var_dump($gate->statusQueue('testQueue', 100)); // получаем статусы из очереди 'testQueue'
