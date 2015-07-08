<?php
/**
 
 */
class ZLogin
{
	static public $cookie_name = 'master';

    static public function GetLoginId() {
        $user_id = abs(intval(Session::Get('user_id')));
		if (!$user_id) {
			$u = ZUser::GetLoginCookie(self::$cookie_name);
			$user_id = abs(intval($u['id']));
		}
		if ($user_id) self::Login($user_id);
		return $user_id;
    }

	static public function Login($user_id) {
		Session::Set('user_id', $user_id);
		return true;
	}

    static public function NeedLogin() {
        $user_id = self::GetLoginId();
        return $user_id ? $user_id : False;
    }

	static public function Remember($user) {
		$zone = "{$user['id']}@{$user['password']}";
		cookieset(self::$cookie_name, base64_encode($zone), 7*86400);
	}

	static public function NoRemember($user_id=0) {
		cookieset(self::$cookie_name, null, -1);
                if($user_id>0) Cache::Del(Cache::GetObjectKey('user',$user_id));
	}
}
