<?php
	class SettingsModel extends baseModel {	

		public function backgroundForm() {
			$f = new Form(get_url('ustawienia/zapisz-tlo'), 'post', 'p90 margin-auto formularz walidacja', false, true);
			$f->newFile('file', false, 'image/*');
			$f->addControls('zapisz tło');
			return $f->show();
		}

		public function saveBackground() {
			$rozszerzenia = Array('jpg', 'png', 'jpeg', 'bmp', 'gif');
			$nazwaPliku = '';
			$path = '/uploads/img/background';
			
			if (!empty($_FILES)) {
				$tempFile = $_FILES['file']['tmp_name'];
				if (empty($tempFile)) 
					return 'Proszę wybrać plik!';

				$roz = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$target = $path . '.' . $roz;

				if (file_exists($target)) unlink($target);
				move_uploaded_file($tempFile, $target);

				if (in_array($roz, $rozszerzenia)) {
					$img = new SimpleImage($target);
					
					$img->auto_orient();

					if ($img->get_width() > 1920)
						$img->fit_to_width(1920);
					if ($roz != 'jpg') { unlink($target); }
					$img->save($path.'.jpg', 85, 'jpg');	

					return 'Tło zostało zapisane!';			
				}	
			}
		}
	}
?>