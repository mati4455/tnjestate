<?php
	class LokalizacjeModel extends baseModel {	

		public function getLocations() {
			$stmt = $this->db->prepare('SELECT l.city_id, l.miasto, o.id, o.osiedle FROM `lokalizacje` l
			LEFT JOIN `osiedla` o ON l.city_id = o.city_id ORDER BY l.miasto ASC, o.osiedle ASC');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getLocation($id) {
			$stmt = $this->db->prepare('SELECT * FROM osiedla WHERE id = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function getCities() {
			$stmt = $this->db->prepare('SELECT * FROM `lokalizacje` ORDER BY `miasto` ASC');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getDistrict($id) {
			$stmt = $this->db->prepare('SELECT * FROM `osiedla` WHERE `id` = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function getCity($id) {
			$stmt = $this->db->prepare('SELECT * FROM `lokalizacje` WHERE `city_id` = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function saveDistrict() {
			$m = unserialize(MESSAGES);
			
			$dane = Core::mapPOST('osiedle');

			$stmt = null;
			if (empty($dane->id)) {
				$stmt = $this->db->prepare('INSERT INTO osiedla VALUES(null, :city_id, :osiedle)');
			} else {
				$stmt = $this->db->prepare('UPDATE osiedla SET osiedle = :osiedle, city_id = :city_id WHERE id = :id');
				$stmt->bindValue(':id', $dane->id, PDO::PARAM_INT);
			} 

			if (empty($dane->city)) return Core::primary_message($m[304]);

			$stmt->bindValue(':city_id', $dane->city, PDO::PARAM_INT);
			$stmt->bindValue(':osiedle', trim($dane->district), PDO::PARAM_STR);
			
			$stmt->execute();
			$stmt->closeCursor();
			
			return Core::primary_message($m[301]);	
		}

		public function saveCity() {
			$m = unserialize(MESSAGES);
			
			$dane = Core::mapPOST('miasto');
			$stmt = null;
			if (empty($dane->id)) {
				$stmt = $this->db->prepare('INSERT INTO lokalizacje VALUES(null, :miasto)');
			} else {
				$stmt = $this->db->prepare('UPDATE lokalizacje SET miasto = :miasto WHERE city_id = :city_id');
				$stmt->bindValue(':city_id', $dane->id, PDO::PARAM_INT);
			} 
			$stmt->bindValue(':miasto', $dane->nazwa, PDO::PARAM_STR);
			
			if (empty($dane->nazwa)) return Core::primary_message($m[304]);

			$stmt->execute();
			$stmt->closeCursor();
			
			return  Core::primary_message($m[301]);	
		}

		public function deleteCity($id) {
			$stmt = $this->db->prepare('SELECT id FROM osiedla WHERE city_id = :id');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$dane = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach ($dane as $key => $value) {
				drukuj($value); echo '<br>';
				$this->deleteDistrict($value->id);
			}

			$stmt2 = $this->db->prepare('DELETE FROM lokalizacje WHERE city_id = :id');
			$stmt2->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt2->execute();
			$stmt2->closeCursor();

			$m = unserialize(MESSAGES);
			return Core::primary_message($m[302]);
		}

		public function deleteDistrict($id) {
			$mod = $this->loadModel('oferty');
			$stmt = $this->db->prepare('SELECT id FROM oferty WHERE lokalizacja_id = :id');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$dane = $stmt->fetchAll(PDO::FETCH_CLASS);
			foreach ($dane as $key => $value) {
				drukuj($value); echo '<br>';
				$mod->deleteOffer($value->id);
			}

			$stmt2 = $this->db->prepare('DELETE FROM osiedla WHERE id = :id');
			$stmt2->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt2->execute();
			$stmt2->closeCursor();

			$m = unserialize(MESSAGES);
			return Core::primary_message($m[303]);
		}

	}
?>