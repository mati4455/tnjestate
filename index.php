<?php
	function sanitize_output($buffer) {
	    $search = array(
	        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
	        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
	        '/(\s)+/s'       // shorten multiple whitespace sequences
	    );
	    $replace = array(
	        '>',
	        '<',
	        '\\1'
	    );
	    $buffer = preg_replace($search, $replace, $buffer);
	    return $buffer;
	}
	ob_start("sanitize_output");
	
	//date_default_timezone_set('UTC+1');
	date_default_timezone_set ('Europe/Warsaw');
	setlocale(LC_TIME, "pl");

	header("Content-Type: text/html; charset=utf-8");

	$start = microtime(true);


	$config = parse_ini_file('/includes/config.ini.php', true);
	define ('__CSS', serialize($config['style']));
	define ('__JS', serialize($config['javascript']));
	define ('LANGUAGES', $config['basic']['langs']);


	$_GET['rt'] = filter_var($_GET['rt'], FILTER_SANITIZE_STRING);
	$_GET['action'] = filter_var($_GET['action'], FILTER_SANITIZE_STRING);

	$pathInfo = trim($_SERVER['PATH_INFO'], '/');
	$pathInfo = filter_var($pathInfo, FILTER_SANITIZE_URL);
	if (!empty($pathInfo)) {
		$arr = explode('/',$pathInfo);
		$count = count($arr);

		$k = 0;

		if (strpos($config['basic']['langs'], $arr[$k]) !== false) {
			define('LANG', $arr[$k]);
			$k++;
		} else {
			define('LANG', 'pl');
		}

		$_GET['rt'] = isset($arr[$k]) ? $arr[$k] : '';
		$_GET['action'] = isset($arr[$k+1]) ? $arr[$k+1] : '';
		$_GET['id'] = isset($arr[$k+2]) ? $arr[$k+2] : '';
		
		for ($i=$k+1; $i < $count;$i+=2){
			$_n = $arr[$i];
			$_v = isset($arr[$i+1]) ? $arr[$i+1] : '';
			$_GET[$_n] = $_v;
		}
	} else {
		define('LANG', 'pl');
	}

	/*** error reporting on ***/
	//error_reporting(E_ALL);

	/*** define the site path ***/
	$site_path = realpath(dirname(__FILE__));
	if ($site_path[strlen($site_path-1)] != '/' && strlen($site_path) > 1) $site_path .= '/';
	define ('__SITE_PATH', $site_path);
	define ('URL', $site_path . LANG . '/');
	define ('MODELS_PATH', __SITE_PATH . 'models/');
	
	
	/*** include the init.php file ***/
	require $site_path . 'includes/functions.php';
	require $site_path . 'includes/init.php';
	require $site_path . 'addons/PHPMailer/PHPMailerAutoload.php';
	//require $site_path . 'addons/SimpleImage.php';
	 
	//$subdomain = explode('.', $_SERVER['HTTP_HOST']);
	//$lang = strpos( $config['basic']['langs'], $subdomain[0]) !== false ? $subdomain[0] : 'pl';
	require 'includes/config.php';

	if (constant('SESSION') == true)
		Session::init();
	
	/*** load the router ***/
	$registry->router = new router($registry);
	
	/*** set the controller path ***/
	$registry->router->setPath (__SITE_PATH . 'controllers');
	
	$registry->auth = false;
	
	/*** load up the template ***/
	$registry->template = new template($registry);


	$end = microtime(true);
	$creationtime = ($end - $start);
	$registry->template->load_time = $creationtime;

	/*** load the controller ***/
	$registry->router->loader();

	ob_end_flush();	
?>
