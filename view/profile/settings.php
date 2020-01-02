<form class="col s12" action="" method="post">
  <div class="row">
    <div class="input-field col s6">
      <input id="first_name" type="text" name="first_name" class="validate" value="<?= $user['first_name'] ?>">
      <label for="first_name">Vorname</label>
    </div>
    <div class="input-field col s6">
      <input id="last_name" type="text" name="last_name" class="validate" value="<?= $user['last_name'] ?>">
      <label for="last_name">Nachname</label>
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12">
      <input id="username" type="text" name="username" class="validate" value="<?= $user['username'] ?>">
      <label for="username">Benutzername</label>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <input type="submit" name="update_user" class="col s4 btn blue darken-3" value="Speichern"/>
    </div>
  </div>
  <div class="row">
		<?php
		if (isset($form_values['errors'])) {
			echo '<div class="col s12 error-message">';
			foreach ($form_values['errors'] as $error) {
				echo '<p class="red white-text">' . $error['message'] . '</p>';
			}
			echo '</div>';
		}
		if (isset($_GET['message'])) {
			echo '<script>M.toast({html: "Benutzerdaten wurden gespeichert!", classes: "green"})</script>';
		}
		?>
  </div>
</form>
