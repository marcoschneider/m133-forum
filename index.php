<?php
	session_start();

	if (!isset($_GET['page'])) {
	  header('Location: ?page=main');
  }

	if (isset($_GET['logout'])) {
		session_unset();
		session_destroy();
		header('Location: ?page=login');
	}

	switch ($_GET['page']) {
		case 'login':
			include 'view/login.php';
			break;
		case 'register':
			include 'view/register.php';
			break;
		case 'main':
			include 'view/main.php';
			break;
		case 'question':
			include 'view/question_detail.php';
			break;
		case 'question-create':
			include 'view/question_create.php';
			break;
		case 'question-edit':
			include 'view/question_edit.php';
			break;
    case 'answer-delete':
      include 'view/answer_delete.php';
      break;
    case 'answer-edit':
      include 'view/answer_delete.php';
      break;
		default:
			echo "Diese Seite wurde nicht gefunden";
			break;
	}
