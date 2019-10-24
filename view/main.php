<?php
	require_once "php/question_functions.php";
	
	if (!isset($_SESSION['kernel']['userdata'])) {
		header('Location: ?page=login');
	}
	
	$questions = getAllQuestions();
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
        <a href="#" class="brand-logo">Logo</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
          <li><a href="?logout">Logout</a></li>
          <li>
            <a href="?page=profile&user=<?= $_SESSION['kernel']['userdata']['username'] ?>">Profil</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container question-overview">
      <h3>Hallo <?= $_SESSION['kernel']['userdata']['username'] ?></h3>
      <div class="row">
				<?php
					$limit = 3;
					foreach ($questions as $question) {
						$answers = getAnswersByQuestionId($question['id'], $limit);
						$answers_markup = '
              <p>Keine Antworten</p>
            ';
						if (count($answers) > 0) {
							$answers_markup = '';
							foreach ($answers as $answer) {
								$answers_markup .= '
                  <p class="black-text">' . $answer['answer_text'] . '</p>
					      ';
              }
						}
						echo '
              <div class="col s6">
                <div class="card">
                  <div class="card-content">
                    <a href="?page=question&id=' . $question["id"] . '" class="card-title">
                    ' . $question["question_text"] . '
                    </a>
                    <div>
                      <p class="grey-text lighten-2">Antworten</p>
                      <div class="divider"></div>
                      ' . $answers_markup . '
                    </div>
                  </div>
                  <div class="card-action">
                    <a>Aufrufe: ' . $question["views"] . '</a>
                    <a>Author: ' . $question["username"] . '</a>
                  </div>
                </div>
              </div>
					  ';
					}
				?>
        <div class="fixed-action-btn">
          <a href="?page=question-create" class="btn-floating btn-large blue darken-3 tooltipped" data-position="left" data-tooltip="Neue Frage stellen">
            <i class="large material-icons">add</i>
          </a>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="./js/lib/jquery.min.js"></script>
    <script type="text/javascript" src="./js/lib/materialize.min.js"></script>
    <script type="text/javascript" src="./js/script.js"></script>
  </body>
</html>