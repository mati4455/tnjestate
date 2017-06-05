<?php
	class CronModel extends baseModel {

		public function aktualizujTekstReklamowy() {
			$m = date('n');
			$r = date('Y');

			$zerowanie = $this->db->prepare('UPDATE tekst_reklamowy SET uruchomiony = :u');
			$zerowanie->bindValue(':u', 0, PDO::PARAM_INT);
			$zerowanie->execute();

			$zap = $this->db->prepare('SELECT obiekt_id FROM tekst_reklamowy');
			$zap->execute();
			$dane = $zap->fetchAll(PDO::FETCH_CLASS);
			foreach ($dane as $id) {
				$spr = $this->db->prepare('SELECT obiekt_id AS spr FROM tekst_reklamowy_data WHERE rok = :rok AND miesiac = :miesiac AND obiekt_id = :id');
				$spr->bindValue(':id', $id->obiekt_id, PDO::PARAM_INT);
				$spr->bindValue(':rok', $r, PDO::PARAM_INT);
				$spr->bindValue(':miesiac', $m, PDO::PARAM_INT);
				$spr->execute();
				if ($spr->rowCount() == 1) {
					$wlacz = $this->db->prepare('UPDATE tekst_reklamowy SET uruchomiony = 1 WHERE obiekt_id = :id');
					$wlacz->bindValue(':id', $id->obiekt_id, PDO::PARAM_INT);
					$wlacz->execute();
					$wlacz->closeCursor();
				}
				$spr->closeCursor();
			}
			$zap->closeCursor();
			$zerowanie->closeCursor();
		}

		public function dodajMiesiacWszedzieWidoczne() {
			$m = date('n');
			$r = date('Y');

			if ($m == 10) {
				$r++;
				for ($i = 1; $i<=12; $i++) {					
					$nowyWpis = $this->db->prepare('INSERT INTO wszedzie_widoczny_spr VALUES (:m, :r, :i)');
					$nowyWpis->bindValue(':m', $i, PDO::PARAM_INT);
					$nowyWpis->bindValue(':r', $r, PDO::PARAM_INT);
					$nowyWpis->bindValue(':i', 0, PDO::PARAM_INT);
					$nowyWpis->execute();
					$nowyWpis->closeCursor();
				}
			}
		}

		public function dodajMiesiacBoksPrestizowy() {
			$m = date('n');
			$r = date('Y');

			if ($m == 10) {
				$r++;
				for ($i = 1; $i<=12; $i++) {
					for ($j = 1; $j<=31; $j++) {
						$nowyWpis = $this->db->prepare('INSERT INTO boks_prestizowy_spr VALUES (:d, :m, :r, :i)');
						$nowyWpis->bindValue(':d', $j, PDO::PARAM_INT);
						$nowyWpis->bindValue(':m', $i, PDO::PARAM_INT);
						$nowyWpis->bindValue(':r', $r, PDO::PARAM_INT);
						$nowyWpis->bindValue(':i', 0, PDO::PARAM_INT);
						$nowyWpis->execute();
						$nowyWpis->closeCursor();						
					}				
				}
			}
		}

		public function dodajMiesiacBigBaner() {
			$m = date('n');
			$r = date('Y');

			if ($m == 10) {
				$r++;
				for ($i = 1; $i<=12; $i++) {
					for ($j = 1; $j<=31; $j++) {
						$nowyWpis = $this->db->prepare('INSERT INTO big_baner_spr VALUES (:d, :m, :r, :i)');
						$nowyWpis->bindValue(':d', $j, PDO::PARAM_INT);
						$nowyWpis->bindValue(':m', $i, PDO::PARAM_INT);
						$nowyWpis->bindValue(':r', $r, PDO::PARAM_INT);
						$nowyWpis->bindValue(':i', 0, PDO::PARAM_INT);
						$nowyWpis->execute();
						$nowyWpis->closeCursor();						
					}				
				}
			}
		}

		public function generujObiektyPromowane() {
			$stmt = $this->db->prepare('TRUNCATE TABLE wszedzie_widoczny_lista');
			$stmt->execute();
			$stmt->closeCursor();

			$stmt = $this->db->prepare('SELECT obiekt_id FROM wszedzie_widoczny WHERE miesiac = :m AND rok = :r');
			$stmt->bindValue(':m', date('n'), PDO::PARAM_INT);
			$stmt->bindValue(':r', date('Y'), PDO::PARAM_INT);
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('INSERT INTO wszedzie_widoczny_lista VALUES (:id, 0)');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function generujBoksRozwijany() {
			$stmt = $this->db->prepare('TRUNCATE TABLE boks_rozwijany_lista');
			$stmt->execute();
			$stmt->closeCursor();

			$stmt = $this->db->prepare('SELECT obiekt_id FROM boks_rozwijany WHERE miesiac = :m AND rok = :r');
			$stmt->bindValue(':m', date('n'), PDO::PARAM_INT);
			$stmt->bindValue(':r', date('Y'), PDO::PARAM_INT);
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('INSERT INTO boks_rozwijany_lista VALUES (:id, 0)');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function aktualizujLiczbeWyswietlenWszedzieWidoczne() {
			$stmt = $this->db->prepare('SELECT * FROM wszedzie_widoczny_lista');
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('UPDATE wszedzie_widoczny SET wyswietlen = :w WHERE obiekt_id = :id AND miesiac = :m AND rok = :r');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->bindValue(':m', date('n'), PDO::PARAM_INT);
				$z->bindValue(':r', date('Y'), PDO::PARAM_INT);
				$z->bindValue(':w', $odp->wyswietlen, PDO::PARAM_INT);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function aktualizujLiczbeWyswietlenBoksRozwijany() {
			$stmt = $this->db->prepare('SELECT * FROM boks_rozwijany_lista');
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('UPDATE boks_rozwijany SET wyswietlen = :w WHERE obiekt_id = :id AND miesiac = :m AND rok = :r');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->bindValue(':m', date('n'), PDO::PARAM_INT);
				$z->bindValue(':r', date('Y'), PDO::PARAM_INT);
				$z->bindValue(':w', $odp->wyswietlen, PDO::PARAM_INT);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function aktualizujBoksPrestizowy() {
			$stmt = $this->db->prepare('TRUNCATE TABLE boks_prestizowy_lista');
			$stmt->execute();
			$stmt->closeCursor();
			
			$stmt = $this->db->prepare('SELECT obiekt_id, zdjecie FROM boks_prestizowy WHERE dzien = :d AND miesiac = :m AND rok = :r');
			$stmt->bindValue(':d', date('j'), PDO::PARAM_INT);
			$stmt->bindValue(':m', date('n'), PDO::PARAM_INT);
			$stmt->bindValue(':r', date('Y'), PDO::PARAM_INT);
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('INSERT INTO boks_prestizowy_lista VALUES (:id, :img)');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->bindValue(':img', $odp->zdjecie, PDO::PARAM_STR);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function aktualizujBigBaner() {
			$stmt = $this->db->prepare('TRUNCATE TABLE big_baner_lista');
			$stmt->execute();
			$stmt->closeCursor();
			
			$stmt = $this->db->prepare('SELECT obiekt_id, zdjecie FROM big_baner WHERE dzien = :d AND miesiac = :m AND rok = :r');
			$stmt->bindValue(':d', date('j'), PDO::PARAM_INT);
			$stmt->bindValue(':m', date('n'), PDO::PARAM_INT);
			$stmt->bindValue(':r', date('Y'), PDO::PARAM_INT);
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('INSERT INTO big_baner_lista VALUES (:id, :img)');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->bindValue(':img', $odp->zdjecie, PDO::PARAM_STR);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}

		public function aktualizujTopBaner() {
			$stmt = $this->db->prepare('TRUNCATE TABLE top_baner_lista');
			$stmt->execute();
			$stmt->closeCursor();
			
			$stmt = $this->db->prepare('SELECT obiekt_id, lok_n, lok_e, zdjecie FROM top_baner WHERE dzien = :d AND miesiac = :m AND rok = :r');
			$stmt->bindValue(':d', date('j'), PDO::PARAM_INT);
			$stmt->bindValue(':m', date('n'), PDO::PARAM_INT);
			$stmt->bindValue(':r', date('Y'), PDO::PARAM_INT);
			$stmt->execute();
			while ($odp = $stmt->fetch(PDO::FETCH_LAZY)) {
				$z = $this->db->prepare('INSERT INTO top_baner_lista VALUES (:id, :lok_n, :lok_e, :img)');
				$z->bindValue(':id', $odp->obiekt_id, PDO::PARAM_INT);
				$z->bindValue(':lok_n', $odp->lok_n, PDO::PARAM_STR);
				$z->bindValue(':lok_e', $odp->lok_e, PDO::PARAM_STR);
				$z->bindValue(':img', $odp->zdjecie, PDO::PARAM_STR);
				$z->execute();
				$z->closeCursor();
			}
			$stmt->closeCursor();
		}
	}
?>