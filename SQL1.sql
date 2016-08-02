CREATE TABLE IF NOT EXISTS `user_stat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(32) DEFAULT NULL,
  `realname` varchar(32) DEFAULT NULL,
  `rang` varchar(30) DEFAULT NULL,
  `stavka` float(15,2) DEFAULT 0,
  `zp_calc_sum` float(15,2) DEFAULT 0,
  `zp_calc` float(15,2) DEFAULT 0,
  `zp_pay` float(15,2) DEFAULT 0,
  `zp` float(15,2) DEFAULT 0,
  PRIMARY KEY(`id`)
)
ENGINE = MYISAM
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `iqsms_status` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL DEFAULT '',
  `name_r` varchar(250) NOT NULL DEFAULT '',
  `type` tinyint(4) UNSIGNED NOT NULL DEFAULT 0,
  `is_reply` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
)
ENGINE = MYISAM
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Список статусов сервиса IQSMS';

CREATE TABLE IF NOT EXISTS `iqsms_msg` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `dt_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(255) NOT NULL DEFAULT '',
  `text` varchar(255) NOT NULL DEFAULT '',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `id_status` int(10) unsigned NOT NULL DEFAULT '0',
  `dt_status` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `smscid` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) 
ENGINE = MyISAM 
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Список сообщений, отправленных с помощью сервиса IQSMS';

CREATE TABLE IF NOT EXISTS `iqsms_msg_status` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_msg` bigint(20) unsigned NOT NULL DEFAULT '0',
  `id_status` int(10) unsigned NOT NULL DEFAULT '0',
  `dt_status` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (id)
) 
ENGINE = MyISAM 
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Список сообщений, отправленных с помощью сервиса IQSMS';

ALTER TABLE `message` ADD `is_read` TINYINT NOT NULL DEFAULT '0' AFTER `for_user`;

UPDATE `message` SET `is_read` = 1;

/* 
 * Новые параметры:
 * sms_api_min_balance - минимальная сумма на счету, при достижении которой отправляется СМС
 * msg_record_show_cnt - количество отображаемых по умолчанию записей в сообщениях
 */
INSERT INTO `settings`(`name`, `var`) VALUES 
('sms_api_min_balance', '50'),
('msg_record_show_cnt', '3');

/* Обновим параметры для IQSMS */
UPDATE `settings` SET `var` = 'z1469184353311' WHERE `name` = 'sms_api_username';
UPDATE `settings` SET `var` = '948621' WHERE `name` = 'sms_api_password';
UPDATE `settings` SET `var` = '' WHERE `name` = 'sms_api_phone';

/* Список статусов сервиса IQSMS */
INSERT INTO `iqsms_status` (`id`, `name`, `name_r`, `type`, `is_reply`) VALUES
(1, 'accepted', 'Сообщение принято сервисом', 1, 0),
(2, 'invalid mobile phone', 'Неверно задан номер телефона', 1, 1),
(3, 'text is empty', 'Отсутствует текст', 1, 1),
(4, 'sender address invalid', 'Неверная (незарегистрированная) подпись отправителя', 1, 1),
(5, 'wapurl invalid', 'Неправильный формат wap-push ссылки', 1, 1),
(6, 'invalid schedule time format', 'Неверный формат даты отложенной отправки сообщения', 1, 1),
(7, 'invalid status queue name', 'Неверное название очереди статусов сообщений', 1, 0),
(8, 'not enough credits', 'Баланс пуст', 1, 1),
(9, 'queued', 'Сообщение находится в очереди', 2, 0),
(10, 'delivered', 'Сообщение доставлено', 2, 0),
(11, 'delivery error', 'Ошибка доставки SMS (абонент в течение времени доставки находился вне зоны действия сети или номер абонента заблокирован) ', 2, 0),
(12, 'smsc submit', 'Сообщение доставлено в SMSC', 2, 0),
(13, 'smsc reject', 'Сообщение отвергнуто SMSC (номер заблокирован или не существует)', 2, 0),
(14, 'incorrect id', 'Неверный идентификатор сообщения', 2, 0);
