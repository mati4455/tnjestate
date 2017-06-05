<?php
	class TekstyModel extends baseModel {	

		public function getTexts() {
			$stmt = $this->db->prepare('SELECT * FROM teksty ORDER BY short ASC');
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_CLASS);
		}

		public function getText($id) {
			$stmt = $this->db->prepare('SELECT * FROM teksty WHERE id = :id LIMIT 1');
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_LAZY);
		}

		public function saveTexts() {
			$m = unserialize(MESSAGES);
			
			$dane = Core::mapPOST('dane');

			$stmt = null;
			foreach ($dane as $k => $v) {
				if (!empty($v['id']) && empty($v['short'])) {
					$stmt = $this->db->prepare('DELETE FROM teksty WHERE id = :id LIMIT 1');
					$stmt->bindValue(':id', $v['id'], PDO::PARAM_INT);
				} else {
					if (empty($v['id'])) {
						$stmt = $this->db->prepare('INSERT INTO teksty VALUES(null, :short, :pl, :en)');
					} else {
						$stmt = $this->db->prepare('UPDATE teksty SET short = :short, t_pl = :pl, t_en = :en WHERE id = :id');
						$stmt->bindValue(':id', $v['id'], PDO::PARAM_INT);
					} 
					$stmt->bindValue(':short', $v['short'], PDO::PARAM_STR);
					$stmt->bindValue(':pl', $v['pl'], PDO::PARAM_STR);
					$stmt->bindValue(':en', $v['en'], PDO::PARAM_STR);
				}
				$stmt->execute();
				$stmt->closeCursor();
			}
			return  Core::primary_message($m[201]);	
		}

	}
?>