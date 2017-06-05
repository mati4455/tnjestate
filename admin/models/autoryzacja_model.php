<?php
	class AutoryzacjaModel extends baseModel {

		public function login($login, $password) {		
			$stmt = $this->db->prepare("SELECT user_id, email, password, salt
				FROM admins
				WHERE login = :login
				LIMIT 1");
			if ($stmt) {
				$stmt->bindValue(':login', $login, PDO::PARAM_STR); 
				$stmt->execute();    // Execute the prepared query.

				// get variables from result.
				list($user_int, $email, $db_password, $salt, $aktywowane) = $stmt->fetch(PDO::FETCH_NUM);
				$user_id = $login;
				
				// hash the password with the unique salt.
				$password = hash('sha512', $password . $salt);
				if ($stmt->rowCount() == 1) {
					// If the user exists we check if the account is locked
					// from too many login attempts 
		 
					if ($this->checkbrute($user_int, $this->db) == true) {
						// Account is locked 
						// Send an email to user saying their account is locked
						return 2;
						//return false;
					} else {
						// Check if the password in the database matches
						// the password the user submitted.
						if ($db_password == $password) {
							// Password is correct!
							
							// Get the user-agent string of the user.
							$user_browser = $_SERVER['HTTP_USER_AGENT'];
							// XSS protection as we might print this value

							Session::set('user_int', $user_int);
							Session::set('user_id', $user_id);						
							Session::set('login_string', hash('sha512', $password . $user_browser));

							$pob = $this->db->prepare('SELECT user_id FROM admins WHERE login = :id LIMIT 1');
							$pob->bindValue(':id', $user_id, PDO::PARAM_STR);
							$pob->execute();
							$odp = $pob->fetch();

							Session::set('user_int', $odp['user_id']);	

							$pob->closeCursor();
							// Login successful.
							return 1;
						} else {
							// Password is not correct
							// We record this attempt in the database
							$now = time();
							$this->db->query("INSERT INTO admins_attempts(user_id, time)
											VALUES ('$user_int', '$now')");
							//return 'Wprowadzono błędny login lub hasło';
							return 3;
						}
					}
				} else {
					// No user exists.
					//return 'Wprowadzono błędny login lub hasło';
					return 4;
				}
			}
		}

		public function checkbrute($user_id, $mysqli) {
			// Get timestamp of current time 
			$now = time();
		 
			// All login attempts are counted from the past 2 hours. 
			$valid_attempts = $now - (2 * 60 * 60);
		 
			if ($stmt = $mysqli->prepare("SELECT time 
									 FROM admins_attempts 
									 WHERE user_id = :id
									AND time > :valid")) {
				$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
				$stmt->bindValue(':valid', $valid_attempts);
		 
				// Execute the prepared query. 
				$stmt->execute();
		 
				// If there have been more than 5 failed logins 
				if ($stmt->rowCount() > 5) {
					return true;
				} else {
					return false;
				}
			}
		}

		public function login_check($mysqli) {
			// Check if all session variables are set 

			if (isset($_SESSION['user_id'], $_SESSION['login_string'])) {
				$user_id = Session::get('user_id');
				$login_string = Session::get('login_string');
		 
				// Get the user-agent string of the user.
				$user_browser = $_SERVER['HTTP_USER_AGENT'];
		 
				if ($stmt = $mysqli->prepare("SELECT password 
											  FROM admins 
											  WHERE login = :id LIMIT 1")) {
					// Bind "$user_id" to parameter. 
					$stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
					$stmt->execute();   // Execute the prepared query.
		 
					if ($stmt->rowCount() == 1) {
						// If the user exists get variables from result.
						$odp = $stmt->fetch();
						$password = $odp['password'];
						$login_check = hash('sha512', $password . $user_browser);
		 
						if ($login_check == $login_string) {
							// Logged In!!!! 
							return true;
						} else {
							// Not logged in 
							return false;
						}
					} else {
						// Not logged in 
						return false;
					}
				} else {
					// Not logged in 
					return false;
				}
			} else {
				// Not logged in 
				return false;
			}
		}

		public function esc_url($url) { 
			if ('' == $url) {
				return $url;
			}
		 
			$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
		 
			$strip = array('%0d', '%0a', '%0D', '%0A');
			$url = (string) $url;
		 
			$count = 1;
			while ($count) {
				$url = str_replace($strip, '', $url, $count);
			}
		 
			$url = str_replace(';//', '://', $url);
		 
			$url = htmlentities($url);
		 
			$url = str_replace('&amp;', '&#038;', $url);
			$url = str_replace("'", '&#039;', $url);
		 
			if ($url[0] !== '/') {
				// We're only interested in relative links from $_SERVER['PHP_SELF']
				return '';
			} else {
				return $url;
			}
		}

		public function wyloguj($redirect) {
			$_SESSION = array();
 
			// get session parameters 
			$params = session_get_cookie_params();
			 
			// Delete the actual cookie. 
			setcookie(session_name(),
					'', time() - 42000, 
					$params["path"], 
					$params["domain"], 
					$params["secure"], 
					$params["httponly"]);
			 
			// Destroy session
			Session::destroy();

			if ($redirect)
				header ('Location: ' . $_SERVER['HTTP_REFERER']);
			else
				header('location: ' . __SITE_PATH .'/autoryzacja/logowanie');
		}

		public function createAccount($em=null, $pp=null) {
			$mysqli = $this->db;
			$error_msg = "";
 			
			if (empty($_POST['email'])) $_POST['email'] = $em; 
			if (empty($_POST['p'])) $_POST['p'] = $pp;

			$username = $_POST['login'];

			if (isset($_POST['email'], $_POST['p'], $username)) {
				// Sanitize and validate the data passed in
				$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
				$email = filter_var($email, FILTER_VALIDATE_EMAIL);
				/*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					// Not a valid email
					$error_msg .= '<p class="bg-danger black">Adres e-mail jest niepoprawny</p>';
				}*/

				$password = filter_var($_POST['p'], FILTER_SANITIZE_STRING);
				if (strlen($password) != 128) {
					// The hashed pwd should be 128 characters long.
					// If it's not, something really odd has happened
					$error_msg .= '<p class="bg-danger black">Hasło jest niepoprawne</p>';
				}
			 
				// Username validity and password validity have been checked client side.
				// This should should be adequate as nobody gains any advantage from
				// breaking these rules.
				//
			 
				$prep_stmt = "SELECT user_id FROM admins WHERE email = :email OR login = :login LIMIT 1";
				$stmt = $mysqli->prepare($prep_stmt);
			 
			   // check existing email  
				if ($stmt) {
					$stmt->bindValue(':email', $email, PDO::PARAM_STR);
					$stmt->bindValue(':login', $username, PDO::PARAM_STR);
					$stmt->execute();
			 
					if ($stmt->rowCount() == 1) {
						// A user with this email address already exists
						$error_msg .= '<p class="bg-danger black">Użytkownik o podanym loginie lub adresie e-mail już istnieje - wpisz inne dane.</p>';
									$stmt->closeCursor();
					}
							$stmt->closeCursor();
				} else {
					$error_msg .= '<p class="bg-danger black">Database error Line 39</p>';
							$stmt->closeCursor();
				}
				$data = date("Y-m-d H:i:s");

			 	if (!Core::checkCaptcha()) {
					$error_msg .= '<p class="bg-danger black">Błędny kod z obrazka</p>';
			 	}


				if (empty($error_msg)) {
					// Create a random salt
					//$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE)); // Did not work
					$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			 
					// Create salted password 
					$password = hash('sha512', $password . $random_salt);
			 		
					// Insert the new user into the database 
					if ($insert_stmt = $mysqli->prepare("INSERT INTO admins (login, email, password, salt, data) VALUES (?, ?, ?, ?, ?)")) {
						$insert_stmt->bindValue(1, $username, PDO::PARAM_STR);
						$insert_stmt->bindValue(2, $email, PDO::PARAM_STR);
						$insert_stmt->bindValue(3, $password, PDO::PARAM_STR);
						$insert_stmt->bindValue(4, $random_salt, PDO::PARAM_STR);
						$insert_stmt->bindValue(5, $data, PDO::PARAM_STR);
						// Execute the prepared query.
						if (!$insert_stmt->execute()) {
							return $error_msg;
						}
					}
					unset($_POST['login']);
					unset($_POST['email']);
					unset($_POST['datau']);
					return '<p class="bg-primary">Konto zostało utworzone!</p>';;
				}
				
				return $error_msg;
			}
		}

		public function error($id) {
			$s = "";
			$m = unserialize(MESSAGES);
			switch ($id) {
				case 1: $s = $m[106]; break;
				case 2: $s = $m[107]; break;
				case 3: $s = $m[108]; break;
				case 4: $s = $m[108]; break;
				case 5: $s = $m[110]; break;
			}
			return '<p class="black bg-danger black">' . $s . '</p>';
		}
	}
?>
