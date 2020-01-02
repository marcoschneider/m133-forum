<?php
require_once "php/question_functions.php";
require_once "php/tag_functions.php";
require_once "php/topic_functions.php";
require_once "php/config.inc.php";

if (!isset($_SESSION['kernel']['userdata'])) {
	header('Location: ?page=login');
}

$tags = getAllTags();
$topics = getAllTopics();

if (isset($_POST['submit'])) {
	$form_values = validateForm([
		'question_text' => [
			'value' => $_POST['question_text'],
			'type' => 'text',
			'required' => true,
		],
		'question_description' => [
			'value' => $_POST['question_description'],
			'type' => 'textarea',
			'required' => true,
		],
		'question_tags' => [
			'value' => isset($_POST['question_tags']) ? $_POST['question_tags'] : null,
			'type' => 'select_multiple',
			'required' => false,
		],
		'question_topics' => [
			'value' => isset($_POST['question_topics']) ? $_POST['question_topics'] : null,
			'type' => 'select_multiple',
			'required' => false,
		],
	]);
	
	$form_values['values']['uid'] = $_SESSION['kernel']['userdata']['id'];
	if (count($form_values['errors']) === 0) {
		$result = setQuestion($form_values['values']);
		if ($result) {
			header('Location: ?page=main');
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
      <h1>Neue Fragen stellen</h1>
      <div class="row">
        <form class="col s12" method="post" action="">
          <div class="row">
            <div class="input-field col s6">
              <input id="question_text" name="question_text" type="text">
              <label for="question_text">Frage</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <textarea id="question_description" name="question_description" class="materialize-textarea"
                        data-length="2048"></textarea>
              <label for="question_description">Beschreibung</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <select id="question_tags" name="question_tags[]" multiple>
                <option value="" disabled selected>Wähle Tags zu dieser Frage</option>
								<?php foreach ($tags as $tag): ?>
                  <option value="<?= $tag['id'] ?>"><?= $tag['tag_name'] ?></option>
								<?php endforeach; ?>
              </select>
              <label for="question_tags">Tags</label>
            </div>
            <div class="input-field col s12">
              <select id="question_topics" name="question_topics[]" multiple>
                <option value="" disabled selected>Wähle Themen zu dieser Frage</option>
								<?php foreach ($topics as $topic): ?>
                  <option value="<?= $topic['id'] ?>"><?= $topic['topic_name'] ?></option>
								<?php endforeach; ?>
              </select>
              <label for="question_topics">Thema</label>
            </div>
          </div>
          <div class="row">
            <button class="btn blue darken-3" type="submit" name="submit">Frage Posten
              <i class="material-icons right">send</i>
            </button>
          </div>
        </form>
				<?php
				if (isset($form_values['errors']) && count($form_values['errors']) > 0) {
					echo '<div class="col s12 error-message">';
					foreach ($form_values['errors'] as $error) {
						echo '<p class="red white-text">' . $error . '</p>';
					}
					echo '</div>';
				}
				?>
      </div>
    </div>
    <script src="https://kit.fontawesome.com/474c7db49a.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="./js/lib/materialize.min.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
  </body>
</html>
