<?php
	class SettingsModel extends baseModel {	

		public function backgroundForm() {
			$f = new Form(get_url('settings/zapisz-tlo'), 'post', 'p90 margin-auto formularz walidacja', false, true);
			$f->newFile('file', false, 'image/*');
			$f->addControls('zapisz tło');
			return $f->show();
		}

	}
?>