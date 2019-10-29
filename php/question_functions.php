<?php

require_once "db_conn.php";
require_once "global_functions.php";
require_once "answer_functions.php";

function getAllQuestions() {
  $sql = "
			SELECT
			  question.id,
				question_text,
			  question_description,
				views,
				user.username,
				user.points
			FROM m133_forum.question
				 INNER JOIN m133_forum.user ON question.fk_user = user.id
		";
  $query = mysqli_query(connection(), $sql);
  return fetchAll($query);
}

function getQuestionById($qid) {
  $qid = escape($qid);

  $sql = "
			SELECT
				question_text,
			  question_description,
				views,
			  user.id as uid,
				user.username,
				user.points as 'points_of_author'
			FROM m133_forum.question
						 INNER JOIN m133_forum.user ON question.fk_user = user.id
			WHERE question.id = " . $qid . "
		";
  $query = mysqli_query(connection(), $sql);
  return fetch($query, "Konnte die gewünschte Frage nicht finden.");
}

function getQuestionsAnswers($qid) {
  $question = getQuestionById($qid);
  $answers = getAnswersByQuestionId($qid);

  return [
    "question" => $question,
    "answers" => $answers,
  ];
}

function updateQuestionsViews($qid, $current_views) {
  $error = [
    "message" => "",
  ];

  $qid = escape($qid);
  $new_views = $current_views + 1;

  $sql = "
			UPDATE
				m133_forum.question
			SET views = " . $new_views . "
			WHERE question.id = " . $qid . "
		";

  $query = mysqli_query(connection(), $sql);

  if (!$query) {
    return $error['message'] = "Konnte die anzahl Views nicht aktualisieren!";
  }
  return TRUE;
}

function updateQuestion($qid, $values) {
  $conn = connection();
  $queries = [];

  $sql = "
      UPDATE
        m133_forum.question
      SET 
        question_text = '" . $values['question_text'] . "',
        question_description = '" . $values['question_description'] . "'
      WHERE id = " . $qid . "
    ";
  $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  if ($query) {
    $queries['success'] = '';
  }
  else {
    $queries['errors'] = '';
  }

  $delete_question_tag_sql = "
        DELETE FROM
          m133_forum.question_tag
        WHERE fk_question = " . $qid . "
      ";
  $deleteTagsSuccess = mysqli_query($conn, $delete_question_tag_sql) or die(mysqli_error($conn));
  if ($deleteTagsSuccess) {
    $queries['success'] = '';
  }
  else {
    $queries['errors'] = '';
  }

  if ($values['question_tags'] != NULL) {
    foreach ($values['question_tags'] as $tag) {
      $isSaved = setTag($qid, $tag);
      if ($isSaved) {
        $queries['success'] = '';
      }
      else {
        $queries['errors'] = '';
      }
    }
  }

  $delete_question_topic_sql = "
        DELETE FROM
          m133_forum.question_topic
        WHERE fk_question = " . $qid . "
      ";
  $deleteTopicsSuccess = mysqli_query($conn, $delete_question_topic_sql) or die(mysqli_error($conn));
  if ($deleteTopicsSuccess) {
    $queries['success'] = '';
  }
  else {
    $queries['errors'] = '';
  }

  if ($values['question_topics'] != NULL) {
    foreach ($values['question_topics'] as $topic) {
      $isSaved = setTopic($qid, $topic);
      if ($isSaved) {
        $queries['success'] = '';
      }
      else {
        $queries['errors'] = '';
      }
    }
  }

  if (!isset($queries['errors'])) {
    return TRUE;
  }
  return FALSE;
}

function deleteQuestionById($qid) {
  $conn = connection();

  $answers = getQuestionsAnswers($qid);
  foreach ($answers['answers'] as $answer) {
    $sql = "
      DELETE FROM
        m133_forum.answer
      WHERE id = " . $answer['id'] . "
    ";
    $delete_answer = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  }

  $delete_question_tag_sql = "
    DELETE FROM
      m133_forum.question_tag
    WHERE fk_question = " . $qid . "
  ";
  $delete_answer = mysqli_query($conn, $delete_question_tag_sql) or die(mysqli_error($conn));


  $delete_question_topic_sql = "
    DELETE FROM
      m133_forum.question_topic
    WHERE fk_question = " . $qid . "
  ";
  $delete_answer = mysqli_query($conn, $delete_question_topic_sql) or die(mysqli_error($conn));


  $sql = "
    DELETE FROM
      m133_forum.question
    WHERE id = " . $qid . "
  ";
  $delete_question = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  return $delete_question;
}

function setQuestion($values) {
  $conn = connection();
  $escaped_question_text = escape($values["question_text"]);
  $escaped_question_description = escape($values["question_description"]);
  $escaped_uid = escape($values["uid"]);

  $sql = "
			INSERT INTO m133_forum.question (
				question_text,
			  question_description,
			  views,
			  question_timestamp,
				fk_user
			)
			VALUES (
			  '" . $escaped_question_text . "',
			  '" . $escaped_question_description . "',
			  0,
			  " . time() . ",
			  " . $escaped_uid . "
			)
		";

  mysqli_query($conn, $sql) or die(mysqli_error($conn));

  $latest_question_id = mysqli_insert_id($conn);

  if ($latest_question_id !== 0) {
    if (is_array($values['question_tags'])) {
      foreach ($values['question_tags'] as $tag) {
        $sql = "
					INSERT INTO m133_forum.question_tag (
						fk_question,
						fk_tag
					) VALUES (
						" . $latest_question_id . ",
						" . $tag . "
					)
				";
        mysqli_query($conn, $sql) or die(mysqli_error($conn));
      }
    }

    if (is_array($values['question_topics'])) {
      foreach ($values['question_topics'] as $topic) {
        $sql = "
					INSERT INTO m133_forum.question_topic (
						fk_question,
						fk_topic
					) VALUES (
						" . $latest_question_id . ",
						" . $topic . "
					)
				";
        mysqli_query($conn, $sql) or die(mysqli_error($conn));
      }
    }
  }
  return TRUE;
}
