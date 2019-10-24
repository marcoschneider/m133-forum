<?php
	
	function connection()
	{
		$conn = mysqli_connect('localhost', 'root', 'toor', 'm133_forum');
		
		if (!$conn) {
			echo "Fehler: konnte nicht mit MySQL verbinden." . PHP_EOL;
			echo "Debug-Fehlernummer: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debug-Fehlermeldung: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}

		return $conn;
	}