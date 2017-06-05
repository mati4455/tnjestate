<?php
	function drukuj($co) {
		echo '<pre>';
		print_r($co);
		echo '</pre>';
	} 

	function url($url='') {
		echo __SITE_PATH . $url;
	}

	function get_url($url='') {
		return __SITE_PATH . $url;
	}

	function getUrl($u) {
		return str_replace('-', '_', $u);
	}
	function funkcja($u) {
		return str_replace('-', '_', $u);
	}

	function compareTitles($a, $b)
	{
	    return strcmp($a->title, $b->title);
	}

	function transliterateString($txt) {
		$transliterationTable = array('ą' => 'a', 'Ą' => 'A', 'ć' => 'c', 'Ć' => 'C', 'ę' => 'e', 'Ę' => 'E', 'ł' => 'l', 'Ł' => 'L', 'ń' => 'n', 'Ń' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ś' => 's', 'Ś' => 'S', 'ź' => 'z', 'Ź' => 'Z', 'ż' => 'z', 'Ż' => 'Z');
		$txt = str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
		return $txt;
	}
	
	function makeDir($path)
	{
	     return is_dir($path) || mkdir($path);
	}

	function create_url($string, $limit = 1000){
		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', Core::getTitle(transliterateString($string), $limit));
		return trim(strtolower($slug), '-');
	}

	function show_message($m) {
		$mm = unserialize(MESSAGES);
		/* m = message */
		//$mn = Session::get('message');
		$mn = $_COOKIE['message'];
		if (empty($m) && !empty($mn))
			$m = $mm[$mn];
		
		if (!empty($m)) {
			?>
			<div class="information">
				<h3>Komunikat</h3>
				<span><?php echo $m; ?></span>
			</div>
			<?
		}
	}
	
	function mempty()
	{
	    foreach(func_get_args() as $arg)
	        if(empty($arg))
	            continue;
	        else
	            return false;
	    return true;
	}

	function mapPOST($tablica, $pomin = Array()) {
		if (!is_array($_POST[$tablica])) return false;
		$dane = new stdClass();
		foreach ($_POST[$tablica] as $key => $value) {
			if (in_array($key, $pomin)) continue;
			$tmp = stripslashes($key); 
			$dane->$tmp = ($value);
		}
		return $dane;
	}

	function bindArrayValue ($req, $array, $typeArray = false) {
		if (is_object($req) && ($req instanceof PDOStatement)) {
			$i=0;
			foreach ($array as $key => $value) {
				if ($typeArray[$key]) {
					$req->bindValue(":$key", $value, $typeArray[$key]);
						//echo ++$i . " => :$key => $value - $typeArray[$key]<br/>";
				} else {
					if (is_numeric($value)){
						$value = (int) $value;
						$param = PDO::PARAM_INT;
					}
					elseif (is_bool($value))
						$param = PDO::PARAM_BOOL;
					elseif (is_null($value))
						$param = PDO::PARAM_NULL;
					elseif (is_string($value))
						$param = PDO::PARAM_STR;
					else 
						$param = FALSE;
					
					if ($param) {
						//echo ++$i . " => :$key => $value => $array[$key] => $param<br/>";
						$req->bindValue(":$key", $value, $param);
						//echo ":$key - $value <br>";
					}
				}
			}
			//echo '<pre>'; $req->debugDumpParams(); echo '</pre>';
		}
	}

	function getClientIP() {

		if (isset($_SERVER)) {

			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				return $_SERVER["HTTP_X_FORWARDED_FOR"];

			if (isset($_SERVER["HTTP_CLIENT_IP"]))
				return $_SERVER["HTTP_CLIENT_IP"];

			return $_SERVER["REMOTE_ADDR"];
		}

		if (getenv('HTTP_X_FORWARDED_FOR'))
			return getenv('HTTP_X_FORWARDED_FOR');

		if (getenv('HTTP_CLIENT_IP'))
			return getenv('HTTP_CLIENT_IP');

		return getenv('REMOTE_ADDR');
	}

	function iloscDni($start, $koniec) {
		$startTimeStamp = strtotime(date($start));
		$endTimeStamp = strtotime(date($koniec));
		$timeDiff = abs($endTimeStamp - $startTimeStamp);
		$numberDays = $timeDiff/86400;
		$iloscDni = intval($numberDays) + 1;
		return $iloscDni;
	}
 
	class Core {

		public static function getIdFromName($name) {
			$d = unserialize(NAMES);
			return $d[$name];
		}

		public static function print_menu($menu, $klasa) {
			$html = '<ul class="menu '.$klasa.'">';
			foreach ($menu as $key => $value) { 
				$temp = $value->strona_id == 1 ? '' : 'strony/' . $value->short;
				$active = $_GET['action'] == '' ? '' : $value->short == $_GET['action'] ? 'active' : '';
				$html .= '<li class="'.$active.'"><a href="'.get_url($temp).'">'.$value->title.'</a></li>';
			}
			$html .='</ul>'; 
			echo $html;
			return $html;
		}

		public static function getSelectList($name, $list, $default, $id) {
			$html = '<select class="form-control" name='.$name.'>';
			$html .= '<option value="0">'.$default.'</option>';
			foreach ($list as $k => $v) {
				$tmp = $v->id == $id ? ' selected' : '';
				$html .= '<option value="'.$v->id.'" '.$tmp.'>'.$v->title.'</option>';
			}
			$html .= '</select>';
			return $html;
		}
		


		public static function getTitle($title, $limit) {
			$tmp = strtok(wordwrap($title, $limit, "...\n"), "\n");
			//if (strlen($title) >= $limit) $tmp .= '...';
			return $tmp;
		}

		public static function komunikat($o, $tekst) {
			$o->view->message = $tekst;
			$o->view->show('/message');
		}

		public static function loginRequired($break = false) {
			if (!logged):
				if ($break) exit;
				header ('location: ' . get_url('autoryzacja/logowanie') );
			endif;
		}

		public static function startsWith($haystack, $needle) {
			return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}

		public static function disableAnalytics() {
			define('ANALYTICS', false);
		}

		public static function endsWith($haystack, $needle) {
			return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
		}

		public static function showMessage($m) {
			$mm = unserialize(MESSAGES);
			/* m = message */
			$mn = $_COOKIE['message'];
			Core::removeMessage();
			if (empty($m) && !empty($mn))
				$m = Core::primary_message($mm[$mn]);
			if (is_numeric($m))
				$m = Core::primary_message($mm[$m]);
			
			
			if (!empty($m)) {
				?>
				<div class="information">
					<?php echo $m; ?>
				</div>
				<?
			}
		}

		public function sendMail($to, $temat, $tresc, $zalacznik=null, $zal_name=null) {
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'zdrogi.pl';  							// Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@zdrogi.pl';                 // SMTP username
			$mail->Password = '&Tnk,Hn2M.e2';                           // SMTP password
			$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
			$mail->Port = "465";

			$mail->From = 'no-reply@zdrogi.pl';
			$mail->FromName = 'Serwis zdrogi.pl';
			$mail->addAddress($to);     						  // Add a recipient		

			$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
			if (!empty($zalacznik))
				$mail->addAttachment($zalacznik, $zal_name);         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $temat;
			$mail->Body    = $tresc;
			//$mail->AltBody = $czysta_tresc;

			if(!$mail->send()) {
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				return true;
				//echo 'Message has been sent';
			}
		}

		public static function mapPOST($tablica, $pomin = Array()) {
			if (!is_array($_POST[$tablica])) return false;
			$dane = new stdClass();
			foreach ($_POST[$tablica] as $key => $value) {
				if (in_array($key, $pomin)) continue;
				$tmp = stripslashes($key); 
				$dane->$tmp = ($value);
			}
			return $dane;
		}

		public static function sep1000($number) {
			return number_format($number, 0, ',', ' ');
		}

		public static function fixIndex($tab) {
			$r = Array();
			foreach ($tab as $k => $v)
				$r[$v[0]] = $v[1];
			unset($tab);
			return $r;
		}

		public static function setSort($s) {
			define ('__SORT', $s);
		}

		public static function getSort() {
			if (empty($_GET['sort']))
				if (defined('__SORT'))
					$sort = __SORT;
				else
					$sort = 'pop_mal';
			else
				$sort = $_GET['sort'];

			return $sort;
		}

		public static function returnJSON($array) {
			ob_clean();
			header('Content-Type: application/json');
 			echo json_encode($array);
 			return json_encode($array);
		}

		public static function getOldUrl() {
			$sort = Core::getSort();
			$q = $_GET['q'];

			$path = Core::getCurrentURL();
			$path = str_replace('sort='.$sort, '', $path);
			$path = str_replace('/zapisz-film', '', $path);

			if (substr($path, -1) != '/') $path .= '/';
			$path .= '?';

			if (!empty($q)) $path .= 'q='.$q.'&amp;';

			return $path;
			//echo $path; exit;
		}

		public static function getCurrentURL() {
			$currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
			$currentURL .= $_SERVER["SERVER_NAME"];
		 
			if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
				$currentURL .= ":".$_SERVER["SERVER_PORT"];
			}
			$currentURL .= strtok($_SERVER["REQUEST_URI"],'?');
			return $currentURL;
		}

		public static function checkCaptcha() {
			return $_SESSION['captcha']['code']  == strtolower($_POST['captcha']) ? true : false; 
		}

		public static function error_message($t) {
			return '<p class="bg-danger black">'.$t.'</p>';
		}

		public static function info_message($t) {
			return '<p class="bg-info">'.$t.'</p>';
		}

		public static function success_message($t) {
			return '<p class="bg-success">'.$t.'</p>';
		}

		public static function primary_message($t) {
			return '<p class="bg-primary">'.$t.'</p>';
		}

		public static function removeMessage() {
			if (isset($_COOKIE['message'])) {
				unset($_COOKIE['message']);
				setcookie("message", "", time()-3600);
			}
		}

		public static function parseArray($array) {
			$string = '';
			foreach ($array as $key => $value) 
				$string .= $key . '=' . $value . '&';
			return trim(trim($string, '&'));
		}




	}
?>