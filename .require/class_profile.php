<?php
class profile
{
	private $db;
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	public function GetMember($userid)
	{
		$result = $this->db->query("SELECT u.nickname, ul.name AS level, (CASE u.rank_id WHEN 0 THEN '' ELSE '-' END) AS rank, 
		u.email, u.sex, u.birthday, u.website, u.location, u.interests, u.signature, u.avatar, u.coins, u.post, 
		(CASE WHEN ul.level_id<7 THEN 1 ELSE 0 END) AS adult, u.signup, u.warn, u.baned
		FROM dl_user AS u INNER JOIN dl_user_level AS ul ON u.level_id = ul.level_id WHERE u.user_id = $GLOBALS[USER] LIMIT 1;");
		return $result;
	}
	
	public static function uservaild($username)
	{
		$valid = "/^[a-z\d_-]{4,50}$/i";
		if(preg_match($validusername, trim($username))) {
			
		}
	}
	
	public static function sex($id)
	{
		$sex = _SEX_MALE;
		switch($id)
		{
			case 1: $sex = _SEX_MALE2; break;
			case 2: $sex = _SEX_FEMALE2; break;
			case 3: $sex = _SEX_FEMALE3; break;
			case 4: $sex = _SEX_FEMALE; break;
			case 5: $sex = _SEX_MALE3; break;
			case 6: $sex = _SEX_FEMALE4; break;
			case 7: $sex = _SEX_OTHER; break;
		}
		return $sex;
	}
	
	public static function conis($val)
	{
		$money = "";
		$platinum = floor($val/1000000);
		$gold = floor(($val%1000000)/10000);
		$silver = floor(($val%10000)/100);
		$copper = floor($val%100);
		if($platinum>0) $money .= '<coins id="platinum">'.$platinum.' Platinum</coins>';
		if($gold>0) $money .= '<coins id="gold">'.$gold.' Gold</coins>';
		if($silver>0) $money .= '<coins id="silver">'.$silver.' Silver</coins>';
		if($copper>0 || $val==0) $money .= '<coins id="copper">'.$copper.' Copper</coins>';
		if($val<0) $money .= '<coins id="platinum">Infinity</coins>';
		
		return $money;
	}
	
	public static function experience($minute)
	{
		$day = floor(($minute%43920)/1440);
		$hour = floor(($minute%1440)/60);
		$minute = floor($minute%60);
		$exps = "";
		if($day>0) $exps .= '<exp id="day"> '.$day.' '._TIME_DAY.'</exp>';
		if($hour>0) $exps .= '<exp id="hour"> '.$hour.' '._TIME_HOUR.'</exp>';
		if($minute>0) $exps .= '<exp id="minute"> '.$minute.' '._TIME_MINUTE.'</exp>';
		else $exps .= '<exp id="minute"> '._TIME_NONE.'</exp>';
		return $exps;
	}
	
}
?>
