<?php

	Abstract Class baseModel {
		protected $db;
		 
		function __construct($db) {
			$this->db = $db;
		}
	}

?>
