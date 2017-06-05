<?php
	/*
		stałe dane na każdej stronie
	*/

	$model = $controller->loadModel('silnik'); 
	$model2 = $controller->loadModel('search'); 
	//serialize ( $controller->view->kategorie = $model->pobierzKategorie() ) ;
	//file_put_contents('/database/uzytkownicy.bin', serialize ( $controller->view->users = $model->pobierzUzytkownikow() ) );
	//define ('USERS', serialize ( $controller->view->users = $model->pobierzUzytkownikow() ) );
	//
	define ('CATEGORIES', serialize ( $controller->view->categories = $model->getCategories() ) );
	define ('TYPES', serialize ( $controller->view->types = $model->getTypes() ) );
	define ('LOCATIONS', serialize ( $controller->view->locations = $model->getLocations() ) );
	define ('AGENTS', serialize ( $controller->view->agents = $model->getAgents() ) );

	define ('SEARCH_FROM', serialize ( $controller->view->search_form = $model2->getForm($_GET['rodzaj'], $_GET['typ'], $_GET['lokalizacja']) ) );

	define ('TEXTS', serialize ( $controller->view->texts = $model->getTexts() ) );

	define ('LANGS', serialize( $controller->view->langs = explode(',', LANGUAGES ) ) );

	define ('MESSAGES', serialize (
		$controller->view->messages = Array(
			98 => 'Proszę wyepłnić wszystkie pola',
			99 => 'Wystąpił nieoczekiwany błąd',

			100 => 'Proszę wpisać tytuł filmu',
			101 => 'Proszę wybrać kategorię filmu',
			102 => 'Proszę podać link do filmu',

			103 => 'Błędny kod z obrazka',
			104 => 'Oferta została zapisana',
			105 => 'Wystąpił problem podczas zapisywania',

			106 => 'Zostałeś zalogowany',
			107 => 'Twoje konto zostało zablokowane', 
			108 => 'Podany login lub hasło jest błędne',
			109 => 'Zostałeś pomyślnie wylogowany',
			110 => 'Aby móc się zalogować musisz aktywować swoje konto - sprawdź swoją skrzynkę e-mail!',
			111 => 'E-mail z linkiem do zresetowania hasła został wysłany na Twój adres!',
			112 => 'Twoje hasło zostało zmienione. Możesz się teraz zalogować.',
			113 => 'Wygląda na to, że Twój link wygasł!',
			114 => 'Wystąpił problem z tą operacją. Przepraszamy.',
			115 => 'W bazie użytkowników nie istnieje podany adres e-mail',
			116 => 'Film, który próbujesz zamieścić znajduje się już w bazie. Przepraszamy.',
			117 => 'W naszej bazie nie istnieje szukany film',
			118 => 'Ten film nie ma jeszcze komentarzy',
			119 => 'Aby dodawać komentarze musisz być zalogowany',
			120 => 'Twój komentarz został zapisany',
			121 => 'Wpisany komentarz jest za krótki (min 5 znaków)',
			122 => 'Wpisant komentarz jest za długi (max 2000 znaków)',
			123 => 'Wpisany użytkownik nie istnieje w bazie danych',
			124 => 'Wypełnij wszystkie pola',
			125 => 'Twoja wiadomość została wysłana',
			126 => 'Twoje zgłoszenie zostało zapisane. Dziękujemy za Twoje uwagi.',
			127 => 'Zmiany zostały wprowadzone',
			128 => 'Twoje konto zostało zablokowane, skontaktuj się z administratorem.',

			201 => 'Tłumaczenia zostały zapisane',

			301 => 'Zmiany w lokalizacjach zostały zapisane',
			302 => 'Miejscowość, jej osiedla i wszystkie oferty powiązane zostały usunięte!',
			303 => 'Osiedle i wszystkie oferty powiązane zostały usunięte!',
			304 => 'Proszę wypełnić pola formularza',

			401 => 'Zmiany wprowadzone na stronie statycznej zozstały zapisane',
			402 => 'Zapisywanie strony nie powiodło się - spróbuj jeszcze raz',

			501 => 'Zmiany w profilu agenta zostały zapisane',
			502 => 'Wybrany agent został pomyślnie usunięty',

			601 => 'Twoje hasło zostało zmienione!'
		)
	) );

	$model = $model2 = null;
?>