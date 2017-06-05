<?php
	class stronyModel extends baseModel {	
		
		public function getPages() {
			$stmt = $this->db->prepare('SELECT s.strona_id, d.title FROM `strony` s
				INNER JOIN `strony_tlumaczenia` d ON s.strona_id = d.strona_id
				WHERE d.language_code = "pl"
				ORDER BY d.title ASC');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getPage($id) {
			$stmt = $this->db->prepare('SELECT s.*, d.* FROM `strony` s
				INNER JOIN `strony_tlumaczenia` d ON s.strona_id = d.strona_id
				WHERE s.strona_id = :id');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function savePage() {
			$texts = unserialize(MESSAGES);
			$dane = Core::mapPOST('dane');

			$stmt = $this->db->prepare('UPDATE strony SET short = :short, klasa = :klasa 
				WHERE strona_id = :id');
			$stmt->bindValue(':short', $dane->short, PDO::PARAM_STR);
			$stmt->bindValue(':klasa', $dane->klasa, PDO::PARAM_STR);
			$stmt->bindValue(':id', $dane->id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();

			$langs = unserialize(LANGS);
			foreach ($langs as $k => $v) {
				if ( empty($dane->$v) ) continue;
				$d = $dane->$v;
				$stmt = $this->db->prepare('UPDATE strony_tlumaczenia SET
					title = :ti, tresc = :tr, keywords = :ke, description = :de
					WHERE id = :id');
				$stmt->bindValue(':id', $d['id'], PDO::PARAM_INT);
				$stmt->bindValue(':ti', $d['title'], PDO::PARAM_STR);
				$stmt->bindValue(':tr', $d['tresc'], PDO::PARAM_STR);
				$stmt->bindValue(':ke', $d['keywords'], PDO::PARAM_STR);
				$stmt->bindValue(':de', $d['description'], PDO::PARAM_STR);
				if ($stmt->execute())				
					$stmt->closeCursor();
				else
					return Core::primary_message($texts[402]);
			}
			return Core::primary_message($texts[401]);		
		}
	}
?>