<?php
	class AgentModel extends baseModel {	

		public function changePassword() {
			$hash = $_POST['hash'];
			$haslo = $_POST['p'];
			
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			$password = hash('sha512', $haslo . $random_salt);

			$id = Session::get('user_int');

			$stmt = $this->db->prepare('UPDATE admins SET password = :pass, salt = :salt WHERE user_id = :id LIMIT 1');
			$stmt->bindValue(':pass', $password, PDO::PARAM_STR);
			$stmt->bindValue(':salt', $random_salt, PDO::PARAM_STR);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$m = unserialize(MESSAGES);
			return Core::primary_message($m[601]);
		}

	}
?>