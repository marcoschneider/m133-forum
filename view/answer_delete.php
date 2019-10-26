<?php
require_once "php/answer_functions.php";

if (!isset($_SESSION['kernel']['userdata'])) {
  header('Location: ?page=login');
}

if (isset($_GET['id'])) {
	$result = deleteAnswerById($_GET['id']);
	var_dump($result);
	if ($result) {
		header('Location: ?page=question&id=' . $_GET['question_id']);
	}
}
