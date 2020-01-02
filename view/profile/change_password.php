<form class="col s12" action="" method="post">
  <div class="row">
    <div class="input-field col s12">
      <input id="current_password" type="password" name="current_password" class="validate">
      <label for="current_password">Aktuelles Passwort</label>
    </div>
  </div>
  <div class="row">
    <div class="input-field col s12">
      <input id="password" type="password" name="password" class="validate">
      <label for="password">Neues Passwort</label>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <input type="submit" name="change_password" class="col s4 btn blue darken-3" value="Speichern"/>
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
			echo '<script>M.toast({html: "Passwort wurden gespeichert!", classes: "green"})</script>';
		}
		?>
  </div>
</form>
