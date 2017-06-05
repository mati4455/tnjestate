<?php
	class AgentModel extends baseModel {	

		public function getAgents() {
			$stmt = $this->db->prepare('SELECT id, imie_nazwisko, telefon 
				FROM agenci
				ORDER BY imie_nazwisko ASC');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getAgent($id) {
			$stmt = $this->db->prepare('SELECT id, imie_nazwisko, telefon
				FROM agenci WHERE id = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function save() {
			$m = unserialize(MESSAGES);
			
			$dane = Core::mapPOST('agent');

			$stmt = null;
			if (empty($dane->id)) {
				$stmt = $this->db->prepare('INSERT INTO agenci VALUES(null, :nazwa, :tel)');
			} else {
				$stmt = $this->db->prepare('UPDATE agenci SET imie_nazwisko = :nazwa, telefon = :tel WHERE id = :id');
				$stmt->bindValue(':id', $dane->id, PDO::PARAM_INT);
			} 

			if (mempty($dane->telefon, $dane->imie_nazwisko)) return Core::primary_message($m[98]);

			$stmt->bindValue(':nazwa', $dane->imie_nazwisko, PDO::PARAM_STR);
			$stmt->bindValue(':tel', $dane->telefon, PDO::PARAM_STR);
			
			$stmt->execute();
			$stmt->closeCursor();
			
			return Core::primary_message($m[501]);	
		}

		public function delete($id) {
			$stmt = $this->db->prepare('DELETE FROM agenci WHERE id = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();

			$m = unserialize(MESSAGES);
			return Core::primary_message($m[502]);
		}

	}
?>