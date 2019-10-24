<?php
	
	require_once "global_functions.php";
	require_once "db_conn.php";
	
	function login($values)
	{
		$error = [
			"message" => ''
		];
		$escaped_username = mysqli_real_escape_string(connection(), $values["username"]);
		$escaped_pass = mysqli_real_escape_string(connection(), hash('sha256', $values["pass"]));
		
		$sql = "
			SELECT
				id,
			 	username
			FROM m133_forum.user
			WHERE
				username = '" . $escaped_username . "'
			AND
				pass = '" . $escaped_pass . "'
		";
		
		$query = mysqli_query(connection(), $sql);
		
		if ($query) {
			$result = mysqli_fetch_assoc($query);
			if ($result) {
				$_SESSION['kernel']['userdata'] = $result;
				return TRUE;
			}
			return $error['message'] = "Benutzername oder Passwort falsch";
		}
		return $error['message'] = "Konnte die Query nicht ausführen!";
	}
	
	function register($values)
	{
		$error = [
			"message" => ''
		];
		$escaped_first_name = mysqli_real_escape_string(connection(), $values["first_name"]);
		$escaped_last_name = mysqli_real_escape_string(connection(), $values["last_name"]);
		$escaped_username = mysqli_real_escape_string(connection(), $values["username"]);
		$escaped_pass = mysqli_real_escape_string(connection(), hash('sha256', $values["pass"]));
		
		
		$sql = "
			INSERT INTO m133_forum.user(
				first_name,
				last_name,
				username,
			  pass
			)
			VALUES (
				'" . $escaped_first_name . "',
				'" . $escaped_last_name . "',
				'" . $escaped_username . "',
				'" . $escaped_pass . "'
			)
		";
		
		$query = mysqli_query(connection(), $sql);
		if ($query) {
			$result = mysqli_fetch_assoc($query);
			
			if ($result) {
				return TRUE;
			}
			return $error['message'] = "Benutzer konnte nicht erstellt werden!";
		}
		return $error['message'] = "Konnte die Query nicht ausführen!";
	}