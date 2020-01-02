<?php
require_once "php/user_functions.php";

$values = [];
$errors = [];
if (isset($_POST['submit'])) {
	if (isset($_POST['username']) && $_POST['username'] != '') {
		$values['username'] = htmlspecialchars($_POST['username']);
	} else {
		$errors['username'] = "Bitte den Benutzernamen angeben";
	}
	
	if (isset($_POST['password']) && $_POST['password'] != '') {
		$values['pass'] = htmlspecialchars($_POST['password']);
	} else {
		$errors['pass'] = "Bitte das Passwort angeben";
	}
	
	
	if (count($errors) === 0) {
		$login = login($values);
		if ($login === true) {
			header('Location: ?page=main');
		} else {
			$errors['login'] = $login;
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/materialize.min.css">
    <link rel="stylesheet" href="./css/styles.css"/>
  </head>
  <body>
    <div class="container login">
      <div class="row card hoverable">
        <div class="card-content ">
          <h4 class="center blue-text">Login</h4>
          <form class="col s12" action="" method="post">
            <div class="row">
              <div class="input-field col s12">
                <input id="username" type="text" name="username" class="validate">
                <label for="username">Benutzername</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="password" type="password" name="password" class="validate">
                <label for="password">Passwort</label>
              </div>
            </div>
            <div class="row">
              <div class="col s12 valign-wrapper">
                <input type="submit" name="submit" class="col s4 btn blue darken-3"
                       value="Anmelden"/>
                <a class="col s8" href="?page=register">Registrieren</a>
              </div>
            </div>
            <div class="row">
							<?php
							if (isset($errors)) {
								echo '<div class="col s12 error-message">';
								foreach ($errors as $error) {
									echo '<p class="red white-text">' . $error . '</p>';
								}
								echo '</div>';
							}
							?>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="https://kit.fontawesome.com/474c7db49a.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="./js/lib/materialize.min.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
  </body>
</html>
