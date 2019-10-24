<?php

	require_once "db_conn.php";

	function escape($string)
	{
		return htmlspecialchars(mysqli_real_escape_string(connection(), $string));
	}

	function validateForm($form_items)
	{
		$values = [];
		$errors = [];
		foreach ($form_items as $key => $form_item) {
			switch ($form_item['type']) {
				case 'select':
				case 'textarea':
				case 'text':
					if ($form_item['required']) {
						if (isset($form_item['value']) && $form_item['value'] !== '') {
							$values[$key] = escape($form_item['value']);
						} else {
							$errors[$key]['message'] = "Bitte das Feld " . $key . " ausfüllen";
						}
					} else {
						$values[$key] = $form_item['value'];
					}
					break;
				case 'number':
					if ($form_item['required']) {
						if (isset($form_item['value']) && $form_item['value'] > 0) {
							$values[$key] = escape($form_item['value']);
						} else {
							$errors[$key]['message'] = "Bitte das Feld " . $key . " ausfüllen";
						}
					} else {
						$values[$key] = $form_item['value'];
					}
					break;
				case 'select_multiple':
					if ($form_item['required']) {
						if (isset($form_item['value']) && is_array($form_item['value'])) {
							$values[$key] = $form_item['value'];
						} else {
							$errors[$key]['message'] = "Bitte das Feld " . $key . " ausfüllen";
						}
					} else {
						$values[$key] = $form_item['value'];
					}
					break;
			}
		}
		return [
			"values" => $values,
			"errors" => $errors
		];
	}

	function fetchAll($query)
	{
		$error = [
			"message" => ""
		];
		$results = [];

		if ($query) {
			while ($result = mysqli_fetch_assoc($query)) {
				$results[$result['id']] = $result;
			}
			return $results;
		}
		return $error['message'] = "Konnte die Query nicht ausführen!";
	}

	function fetch($query, $error_message)
	{
		$error = [
			"message" => ""
		];
		if ($query) {
			$result = mysqli_fetch_assoc($query);
			if ($result) {
				return $result;
			}
			return $error['message'] = $error_message;
		}
		return $error['message'] = "Konnte die Query nicht ausführen!";
	}
