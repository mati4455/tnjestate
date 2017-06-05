<?php

	Class error404Controller Extends baseController {

		public function index() {
			$this->view->blog_heading = 'Strona, którą próbujesz odwiedzić, nie istnieje!';
			$this->view->show('error404');
		}

	}
?>
