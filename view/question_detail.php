<?php
require_once "php/question_functions.php";
require_once "php/tag_functions.php";
require_once "php/topic_functions.php";
require_once "php/config.inc.php";

if (!isset($_SESSION['kernel']['userdata'])) {
  header('Location: ?page=login');
}

if (isset($_GET['id'])) {
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
      case 'answer-edit':
        if (isset($_GET['answer_id'])) {
          $edited_answer = getAnswerById($_GET['answer_id']);
        }
        break;
      case 'downvote':
      case 'upvote':
        if (isset($_GET['answer_id'])) {
          $isSaved = voteAnswerById($_GET['answer_id'], $_GET['action']);
          if ($isSaved) {
            header('Location: ?page=question&id=' . $_GET['id']);
          }
        }
        break;
    }
  }
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

  if (isset($_POST['edit_answer'])) {
    $form_values = validateForm([
      'answer_text' => [
        'value' => $_POST['answer_text'],
        'type' => 'textarea',
        'required' => TRUE,
      ],
    ]);
    $form_values['values']['uid'] = $_SESSION['kernel']['userdata']['id'];
    $form_values['values']['answer_id'] = $_GET['answer_id'];
    if (count($form_values['errors']) === 0) {
      $isSaved = updateAnswerById($form_values['values']['answer_id'], $form_values['values']['answer_text']);
      if ($isSaved) {
        header('Location: ?page=question&id=' . $_GET['id']);
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
				<li>
					<a href="?page=main">Fragen</a>
				</li>
				<li>
					<a href="?page=profile">Profil</a>
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
					<div class="row">
						<div class="center left">
							<a href="?page=question&id=<?= $_GET['id'] ?>&action=upvote&answer_id=<?= $answer['id'] ?>"
							   class="edit-answer">
								<i class="fas fa-caret-square-up fa-3x green-text"></i>
							</a>
							<h5 class="grey-text lighten-2"><?= $answer['points'] ?></h5>
							<a href="?page=question&id=<?= $_GET['id'] ?>&action=downvote&answer_id=<?= $answer['id'] ?>"
							   class="edit-answer">
								<i class="fas fa-caret-square-down fa-3x red-text"></i>
							</a>
						</div>
						<div class="col s11 right">
							<p><?= $answer['answer_text'] ?></p>
						</div>
					</div>
				</div>
				<div class="card-action">
          <?php if ($question_and_answers['question']['uid'] === $_SESSION['kernel']['userdata']['id']): ?>
						<h6 class="grey-text lighten-2">Authoren aktionen</h6>
						<div class="row">
							<div class="col s6">
								<a href="?page=question&id=<?= $_GET['id'] ?>&action=answer-edit&answer_id=<?= $answer['id'] ?>"
								   class="edit-answer">
									<i class="fas fa-edit fa-lg green-text"></i>
								</a>
								<a href="?page=answer-delete&id=<?= $answer['id'] ?>&question_id=<?= $_GET['id'] ?>"
								   class="delete-answer">
									<i class="fas fa-trash-alt fa-lg red-text"></i>
								</a>
							</div>
						</div>
          <?php endif; ?>
				</div>
			</div>
    <?php endforeach; ?>
		<div class="divider"></div>
		<div class="row">
			<div class="col s12">
        <?php if (isset($_GET['action']) && $_GET['action'] === 'answer-edit' && isset($_GET['answer_id'])) : ?>
					<h5>Antwort bearbeiten</h5>
        <?php else: ?>
					<h5>Kennst du die Antwort?</h5>
        <?php endif; ?>
			</div>
		</div>
		<div class="row">
			<form class="col s12" method="post" action="">
				<div class="row">
					<div class="input-field col s12">
						<textarea id="answer" name="answer_text"
						          class="materialize-textarea"><?= isset($edited_answer['answer_text']) ? $edited_answer['answer_text'] : '' ?></textarea>
						<label for="answer">Antwort</label>
					</div>
				</div>
				<div class="row">
          <?php if (isset($_GET['action']) && $_GET['action'] === 'answer-edit' && isset($_GET['answer_id'])) : ?>
						<button class="btn blue darken-3" type="submit" name="edit_answer">
							Speichere neue Antwort
							<i class="material-icons right">send</i>
						</button>
          <?php else: ?>
						<button class="btn blue darken-3" type="submit" name="submit">Poste
							Antwort
							<i class="material-icons right">send</i>
						</button>
          <?php endif; ?>
				</div>
			</form>
		</div>
	</div>
	<script src="https://kit.fontawesome.com/474c7db49a.js"
	        crossorigin="anonymous"></script>
	<script type="text/javascript" src="./js/lib/jquery.min.js"></script>
	<script type="text/javascript" src="./js/lib/materialize.min.js"></script>
	<script type="text/javascript" src="./js/script.js"></script>
	</body>
	</html>
  <?php
}
