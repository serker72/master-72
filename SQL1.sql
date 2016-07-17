CREATE TABLE IF NOT EXISTS user_stat (
  id bigint(20) UNSIGNED NOT NULL,
  username varchar(32) DEFAULT NULL,
  realname varchar(32) DEFAULT NULL,
  rang varchar(30) DEFAULT NULL,
  stavka float(15,2) DEFAULT 0,
  zp_calc_sum float(15,2) DEFAULT 0,
  zp_calc float(15,2) DEFAULT 0,
  zp_pay float(15,2) DEFAULT 0,
  zp float(15,2) DEFAULT 0,
  PRIMARY KEY(`id`)
)
ENGINE = MYISAM
CHARACTER SET utf8
COLLATE utf8_general_ci;

ALTER TABLE `message` ADD `is_read` TINYINT NOT NULL DEFAULT '0' AFTER `for_user`;

UPDATE `message` SET `is_read` = 1;
