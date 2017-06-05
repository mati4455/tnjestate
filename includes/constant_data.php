<?php
	/*
		stałe dane na każdej stronie
	*/

	$model = $controller->loadModel('silnik'); 

	define ('CATEGORIES', serialize ( $controller->view->categories = $model->getCategories() ) );
	define ('TYPES', serialize ( $controller->view->types = $model->getTypes() ) );
	define ('LOCATIONS', serialize ( $controller->view->locations = $model->getLocations() ) );

	define ('MENU_TOP', serialize ( $controller->view->menu_top = $model->getMenu(2) ) );
	define ('MENU_BOTTOM', serialize ( $controller->view->menu_bottom = $model->getMenu(1) ) );
	
	define ('LANGS', serialize( $controller->view->langs = explode(',', LANGUAGES ) ) );

	define ('TEXTS', serialize ( $t = $controller->view->texts = $model->getTexts() ) );

	define ('EMAIL', $t['#EMAIL#']);
	define ('PER_PAGE_DEFAULT', $t['#PER_PAGE#']);
	define ('GOOGLE_ANALYTICS', $t['#GOOGLE_ANALYTICS#']);

	$model = null;
?>