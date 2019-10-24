<?php
require_once "php/question_functions.php";
require_once "php/tag_functions.php";
require_once "php/topic_functions.php";
require_once "php/config.inc.php";

if (!isset($_SESSION['kernel']['userdata'])) {
  header('Location: ?page=login');
}

if (isset($_GET['id'])) {
  $question_and_answers = getQuestionsAnswers($_GET['id']);
  $tags = getQuestionsTags($_GET['id']);
  $topics = getQuestionsTopics($_GET['id']);
  updateQuestionsViews($_GET['id'], $question_and_answers['question']['views']);

  if (isset($_POST['submit'])) {
    $form_values = validateForm([
      'answer_text' => [
        'value' => $_POST['answer_text'],
        'type' => 'textarea',
        'required' => TRUE,
      ],
    ]);
    $form_values['values']['uid'] = $_SESSION['kernel']['userdata']['id'];
    $form_values['values']['question_id'] = $_GET['id'];
    if (count($form_values['errors']) === 0) {
      $isSaved = setAnswer($form_values['values']);
      if ($isSaved) {
      	header('Location: ?' . $_SERVER['QUERY_STRING']);
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
	</head>
	<body>
	<nav>
		<div class="nav-wrapper blue darken-3">
			<a href="?page=main" class="brand-logo">Logo</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="?logout">Logout</a></li>
				<li>
					<a href="?page=profile&user=<?= $_SESSION['kernel']['userdata']['username'] ?>">Profil</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container">
		<div class="question-header">
			<div class="row valign-wrapper">
				<div class="col s9">
					<h3><?= $question_and_answers['question']['question_text'] ?></h3>
				</div>
				<div class="col s3">
          <?php if ($question_and_answers['question']['uid'] === $_SESSION['kernel']['userdata']['id']): ?>
						<a href="?page=question-edit&id=<?= $_GET['id'] ?>"
						   class="btn-floating btn-large right blue darken-3">
							<i class="material-icons">edit</i>
						</a>
          <?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<p class="grey-text lighten-2">
						Views: <?= $question_and_answers['question']['views'] ?></p>
					<p class="grey-text lighten-2">Anzahl
						Antworten: <?= count($question_and_answers['answers']) ?></p>
				</div>
			</div>
		</div>
		<div class="divider"></div>
		<div class="row">
			<div class="col s12">
				<div class="question-content">
					<p><?= $question_and_answers['question']['question_description'] ?></p>
				</div>
			</div>
		</div>
		<div class="divider"></div>
    <?php if (count($tags) > 0): ?>
			<div class="row">
				<div class="col s12 tags-wrapper">
          <?php foreach ($tags as $tag): ?>
						<span
								class="tags grey darken-4 white-text"><?= $tag['tag_name'] ?></span>
          <?php endforeach; ?>
				</div>
			</div>
    <?php endif; ?>
    <?php if (count($topics) > 0): ?>
			<div class="row">
				<div class="col s12">
          <?php if (count($topics) > 1): ?>
						<p class="grey-text lighten-2">Themen:</p>
          <?php else: ?>
						<p class="grey-text lighten-2">Thema:</p>
          <?php endif; ?>
          <?php foreach ($topics as $topic): ?>
						<span
								class="btn-small grey darken-4"><?= $topic['topic_name'] ?></span>
          <?php endforeach; ?>
				</div>
			</div>
    <?php endif; ?>
		<div class="row">
			<div class="col s12">
				<h4>Antworten</h4>
			</div>
		</div>
    <?php if (count($question_and_answers['answers']) === 0): ?>
			<div class="row">
				<div class="col s12 m5">
					<span class="btn-small orange lighten-2 black-text">Diese Frage hat noch keine Antworten!</span>
				</div>
			</div>
    <?php endif; ?>
    <?php foreach ($question_and_answers['answers'] as $answer) : ?>
			<div class="card">
				<div class="card-content">
					<p><?= $answer['answer_text'] ?></p>
				</div>
				<div class="card-action">
					<div class="row">
						<div class="col s6">
							<a class="upvote-answer">
								<i class="material-icons green-text">done</i>
							</a>
							<a class="downvote-answer">
								<i class="material-icons red-text">do_not_disturb_alt</i>
							</a>
						</div>
            <?php if ($question_and_answers['question']['uid'] === $_SESSION['kernel']['userdata']['id']): ?>
							<div class="col s6">
								<a href="?page=delete-answer" class="delete-answer">
									<i class="material-icons red-text">delete</i>
								</a>
							</div>
            <?php endif; ?>
					</div>
				</div>
			</div>
    <?php endforeach; ?>
		<div class="divider"></div>
		<div class="row">
			<div class="col s12">
				<h5>Kennst du die Antwort?</h5>
			</div>
		</div>
		<div class="row">
			<form class="col s12" method="post" action="">
				<div class="row">
					<div class="input-field col s12">
						<textarea id="answer" name="answer_text"
						          class="materialize-textarea"></textarea>
						<label for="answer">Antwort</label>
					</div>
				</div>
				<div class="row">
					<button class="btn blue darken-3" type="submit" name="submit">Poste
						Antwort
						<i class="material-icons right">send</i>
					</button>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript" src="./js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="./js/lib/materialize.min.js"></script>
	<script type="text/javascript" src="./js/script.js"></script>
	</body>
	</html>
  <?php
}
