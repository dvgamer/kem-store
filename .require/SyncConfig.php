<?php
class StoreManagement
{
	private $base;
	private $data;
	private $path;
	
	public function __construct()
	{
		$this->base = new SyncDatabase();
		$this->data = new Request();
	}
	
	// Initialize Scritps
	public function IncludeScripts()
	{
		foreach (glob("resourses/font/*.css") as $filename) echo '<link rel="stylesheet" type="text/css" href="'.$filename.'" />'."\n\r"; 
		foreach (glob("resourses/css/*.css") as $filename) echo '<link rel="stylesheet" type="text/css" href="'.$filename.'" />'."\n\r"; 
		foreach (glob("resourses/js/*.js") as $filename) echo '<script type="text/javascript" src="'.$filename.'"></script>'."\n\r"; 
	}
}

class code
{
	/*public static function bb($contents)
	{
		$contents = htmlspecialchars($contents);
		$contents = preg_replace('!\[quote\](.+)\[/quote\]!isU', '<div class="citationforum">$1</div>', $contents);
		$contents = preg_replace("!\[quote\=(.+)\](.+)\[\/quote\]!isU", '<div class="citationforum"><strong>$1 :</strong><br>$2</div>', $contents); 
		$contents = preg_replace("!\[font\=(.+)\](.+)\[\/font\]!isU", '<span style="font-size:$1px">$2</span>', $contents); 
		$contents = preg_replace('!\[b\](.+)\[/b\]!isU', '<strong>$1</strong>', $contents);
		$contents = preg_replace('!\[i\](.+)\[/i\]!isU', '<em>$1</em>', $contents);
		$contents = preg_replace('!\[u\](.+)\[/u\]!isU', '<span style="text-decoration:underline;">$1</span>', $contents);
		$contents = preg_replace('!\[center\](.+)\[/center\]!isU', '<div style="text-align:center;">$1</div>', $contents);
		$contents = preg_replace('!\[right\](.+)\[/right\]!isU', '<div style="text-align:right;">$1</div>', $contents);
		$contents = preg_replace('!\[left\](.+)\[/left\]!isU', '<div style="text-align:left;">$1</div>', $contents);
		$contents = preg_replace('!\[li\](.+)\[/li\]!isU', '<li>$1</li>',$contents);
		$contents = preg_replace('!\[img\](.+)\[/img\]!isU', '<img src="$1" border="0">',$contents);
		$contents = preg_replace('!\[url\](.+)\[/url\]!isU', '<a href="$1" target="_blank">$1</a>',$contents);
		$contents = preg_replace("!\[url\=(.+)\](.+)\[\/url\]!isU", '<a href="$1" target="_blank">$2</a>', $contents); 
		$contents = preg_replace("!(.+)\[br\](.+)!isU", '$1<br>$2', $contents); 
		return $contents;
	}*/

	public static function encrypt($decrypted, $password, $salt='!kQm*fF3pXe1Kbm%9')
	{ 
		$key = hash('SHA256', $salt.$password, true);
		srand();
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
		return $iv_base64.$encrypted;
	} 
	
	public static function decrypt($encrypted, $password, $xcode='!kQm*fF3pXe1Kbm%9')
	{
		$key = hash('SHA256', $salt . $password, true);
		$iv = base64_decode(substr($encrypted, 0, 22) . '==');
		$encrypted = substr($encrypted, 22);
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
		$hash = substr($decrypted, -32);
		$decrypted = substr($decrypted, 0, -32);
		if (md5($decrypted) != $hash) return false;
		return $decrypted;
	}
}

class Request
{	
	public function setSession($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	
	public function setCookie($name, $value, $minute)
	{
		if(!$minute) {
			setcookie($name, $value, $minute, '/');
		} else {
			setcookie($name, $value, time()+($minute*60), '/');
		}
	}
	public function delete($name)
	{
		if(isset($_SESSION[$name])){
			unset($_SESSION[$name]);
		} elseif(isset($_COOKIE[$name])) {
			setcookie($name, '', 0, '/');
		}
	}
	
	public function value($name)
	{
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		} elseif(isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} else {
			return false;
		}
	}
}

class SyncDatabase
{
	protected $dbConnect;
	protected $isConfig;
	
	public function __construct()
	{
		$loadConfig = parse_ini_file('SyncConfig.ini', true);
		foreach($loadConfig as $isGroupConfig) { foreach($isGroupConfig as $name=>$value) { $this->isConfig[$name] = $value; } }
		try {
			$this->dbConnect = @mysql_connect($this->isConfig['host'], $this->isConfig['username'], $this->isConfig['password']);
			if (!$this->dbConnect) {
				throw new Exception('<strong>Error:</strong> '.mysql_error());
			} else {
				mysql_select_db($this->isConfig['dbname']);
				mysql_set_charset('UTF8',$this->dbConnect); 
			}
		} catch(Exception $e) {
			echo '<p>'.$e->getMessage().'</p>';
		}
	}
	
	public function query($sqlString)
	{
		list($sqlType) = explode(' ',$sqlString);
		switch(strtolower($sqlType))
		{
			case 'select':
				$result = array();				
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL SELECT:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						while($tmpValue = mysql_fetch_array($tmpQuery))
						{
							$result[] = $tmpValue;
						}
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
				}
				if(preg_match('(limit 1;)', strtolower($sqlString)) && isset($result[0]) ) { $result = $result[0]; }
				return $result;
			break;
			case 'insert':
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL INSERT:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return mysql_insert_id($this->dbConnect);
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
			case 'update':
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL UPDATE:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return mysql_affected_rows($this->dbConnect);
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
			default:
				try {
					$tmpQuery = @mysql_query($sqlString, $this->dbConnect);
					if(!$tmpQuery)
					{
						throw new Exception('<strong>SQL Query:</strong> '.mysql_error().'<br/><strong>SEL STRING:</strong> '.$sqlString);
					} else {
						return true;
					}
				} catch(Exception $e) {
					echo '<p>'.$e->getMessage().'</p>';
					return false;
				}
			break;
		}
	}

	public function __destruct()
	{
		@mysql_close($this->dbConnect);
	}
}

?>