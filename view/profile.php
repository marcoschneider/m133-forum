<?php

require_once "php/user_functions.php";

if (!isset($_SESSION['kernel']['userdata'])) {
  header('Location: ?page=login');
}

$user = getUserById($_SESSION['kernel']['userdata']['id']);

if (isset($_POST['update_user'])) {
  $form_values = validateForm([
    'first_name' => [
      'value' => $_POST['first_name'],
      'type' => 'text',
      'required' => true,
    ],
    'last_name' => [
      'value' => $_POST['last_name'],
      'type' => 'text',
      'required' => true,
    ],
    'username' => [
      'value' => $_POST['username'],
      'type' => 'text',
      'required' => true,
    ],
  ]);

  $form_values['values']['uid'] = $user['uid'];
  if (count($form_values['errors']) === 0) {
    $isSaved = updateUserdata($form_values['values']);
    if ($isSaved) {
    	header('Location: ?' . $_SERVER['QUERY_STRING'] . '&message=success');
    	$success_message = 'Benutzerdaten wurden gespeichert!';
    }
  }
}

if (isset($_POST['change_password'])) {
  $form_values = validateForm([
    'current_password' => [
      'value' => $_POST['current_password'],
      'type' => 'password',
      'required' => true,
    ],
    'password' => [
      'value' => $_POST['password'],
      'type' => 'password',
      'required' => true,
    ]
  ]);

  $form_values['values']['uid'] = $user['uid'];
  if (count($form_values['errors']) === 0) {
  	$isSaved = updatePassword($form_values['values']);
    if ($isSaved) {
      header('Location: ?' . $_SERVER['QUERY_STRING'] . '&message=success');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<title>Forum</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
	      rel="stylesheet">
	<link
			href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700&display=swap"
			rel="stylesheet">
	<link rel="stylesheet" href="./css/materialize.min.css">
	<link rel="stylesheet" href="./css/styles.css"/>
	<script type="text/javascript" src="./js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="./js/lib/materialize.min.js"></script>
</head>
<body>
<nav>
	<div class="nav-wrapper blue darken-3">
		<a href="?page=main" class="brand-logo">
			<i class="fab fa-buffer fa-2x"></i> stackoverflow
		</a>
		<ul id="nav-mobile" class="right hide-on-med-and-down">
			<li>
				<a href="?page=main">Fragen</a>
			</li>
			<li>
				<a href="?page=profile&profile=userdata">Profil</a>
			</li>
		</ul>
	</div>
</nav>
<div class="container">
	<div class="row">
		<h1>Profil</h1>
		<div class="col s3">
			<ul class="collection">
				<li class="collection-item">
					<a href="?page=profile&profile=userdata">Benutzerdaten</a>
				</li>
				<li class="collection-item">
					<a href="?page=profile&profile=change-password">Passwort ändern</a>
				</li>
				<li class="collection-item">
					<a href="?logout">Abmelden</a>
				</li>
			</ul>
		</div>
		<div class="col s9">
      <?php
      if (isset($_GET['profile'])) {
        switch ($_GET['profile']) {
          case 'userdata':
            include "profile/settings.php";
            break;
          case 'change-password':
            include "profile/change_password.php";
            break;
        }
      }
      ?>
		</div>
	</div>
</div>
<script src="https://kit.fontawesome.com/474c7db49a.js"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="./js/script.js"></script>
</body>
</html>
