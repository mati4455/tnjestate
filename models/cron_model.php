<?php
	class CronModel extends baseModel {

		public function resetujDzienneWyswietlenia() {			
			$this->db->exec('UPDATE filmy SET wyswietlen_dzien = 0;');
		}
		
	}
?>