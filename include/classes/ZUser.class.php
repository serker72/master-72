<?php

class ZUser
{

	const SECRET_KEY = '@4!@#$%@';

	static public function GenPassword($p) {
		return md5($p . self::SECRET_KEY);
	}

	static public function Create($user_row) {
		$user_row['password'] = self::GenPassword($user_row['password']);
		$user_row['login_time'] = time();
		$user_row['id'] = DB::Insert('user', $user_row);
		return $user_row['id'];
	}

	static public function GetUser($user_id) {
		if (!$user_id) return array();
		return DB::GetTableRow('user', array('id' => $user_id));
	}

	static public function GetLoginCookie($cname='ru') {
		$cv = cookieget($cname);
		if ($cv) {
			$zone = base64_decode($cv);
			$p = explode('@', $zone, 2);
			return DB::GetTableRow('user', array(
				'id' => $p[0],
				'password' => $p[1],
			));
		}
		return Array();
	}

	static public function GetLogin($username, $password) {
	    $password = self::GenPassword($password);
		return DB::GetTableRow('user', array(
					'username' => $username,
					'password' => $password,
		));
	}
}
