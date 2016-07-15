CREATE TABLE IF NOT EXISTS user_stat (
  id bigint(20) UNSIGNED NOT NULL,
  username varchar(32) DEFAULT NULL,
  realname varchar(32) DEFAULT NULL,
  rang varchar(30) DEFAULT NULL,
  stavka float(15,2) DEFAULT 0,
  zp_calc_sum float(15,2) DEFAULT 0,
  zp_calc float(15,2) DEFAULT 0,
  zp_pay float(15,2) DEFAULT 0,
  zp float(15,2) DEFAULT 0
)
ENGINE = MYISAM
CHARACTER SET utf8
COLLATE utf8_general_ci;

DELETE FROM user_stat;

INSERT INTO user_stat
SELECT u.id, u.username, u.realname, u.rang, u.stavka,
  (SELECT SUM(o.cost) FROM `order` o WHERE o.user_id = u.id AND o.cost <> 0),
  (SELECT ROUND(SUM(o.cost)*u.stavka/100, 2) FROM `order` o WHERE o.user_id = u.id AND o.cost <> 0) AS zp_calc,
  (SELECT SUM(p.cost) FROM `pay` p WHERE p.user_id = u.id AND p.cost <> 0),
  0
  FROM user u
  WHERE u.rang = 'manager'
  AND u.stavka > 0
  HAVING zp_calc > 0
;

INSERT INTO user_stat
SELECT u.id, u.username, u.realname, u.rang, u.stavka,
  (SELECT SUM(o.cost) FROM `order` o WHERE o.master_name = u.id AND o.cost <> 0),
  (SELECT ROUND(SUM(o.cost)*u.stavka/100, 2) FROM `order` o WHERE o.master_name = u.id AND o.cost <> 0) AS zp_calc,
  (SELECT SUM(p.cost) FROM `pay` p WHERE p.user_id = u.id AND p.cost <> 0),
  0
  FROM user u
  WHERE u.rang = 'master'
  AND u.stavka > 0
  HAVING zp_calc > 0
;


UPDATE user_stat SET zp_pay = 0 WHERE zp_pay IS NULL;
UPDATE user_stat SET zp = zp_calc-zp_pay;

SELECT * FROM user_stat;