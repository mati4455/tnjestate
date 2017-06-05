<?php
	/*** auto load model classes ***/
	function __autoload($class_name) {
		$filename = strtolower($class_name) . '_model.php';
		$file = __SITE_PATH . '/models/' . $filename;
	
		if (file_exists($file) == false) {
			return false;
		}
		include ($file);
	}

	/*** include the session class ***/
	include __SITE_PATH . 'application/' . 'session.class.php';

	include __SITE_PATH . 'application/' . 'calendar.class.php';

	/*** include the controller class ***/
	include __SITE_PATH . 'application/' . 'controller_base.class.php';
	
	/*** include the model class ***/
	include __SITE_PATH . 'application/' . 'model_base.class.php';
	
	/*** include the registry class ***/
	include __SITE_PATH . 'application/' . 'registry.class.php';
	
	/*** include the router class ***/
	include __SITE_PATH . 'application/' . 'router.class.php';

	/*** include the form class ***/
	include __SITE_PATH . 'application/' . 'form.class.php';

	/*** include the template class ***/
	include __SITE_PATH . 'application/' . 'template.class.php';

	/*** include the less class ***/
	include __SITE_PATH . 'application/' . 'less/Less.php';

	/*** include the captcha class ***/
	include __SITE_PATH . '/addons/' . 'captcha/captcha.php';

	/*** include oferta class ***/
	include __SITE_PATH . '/includes/oferta.class.php';

	
	/*** a new registry object ***/
	$registry = new registry;
	
	/*** create the database registry object ***/
	$registry->db = db::getInstance();

?>
