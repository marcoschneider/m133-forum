<?php
require_once "php/question_functions.php";

if (!isset($_SESSION['kernel']['userdata'])) {
  header('Location: ?page=login');
}

if (isset($_GET['id'])) {
	$result = deleteQuestionById($_GET['id']);
	if ($result) {
		header('Location: ?page=main');
	}
}
