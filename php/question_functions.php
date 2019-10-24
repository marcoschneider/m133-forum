<?php

	require_once "db_conn.php";
	require_once "global_functions.php";
	require_once "answer_functions.php";

	function getAllQuestions()
	{
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

	function getQuestionById($qid)
	{
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

	function getQuestionsAnswers($qid)
	{
		$question = getQuestionById($qid);
		$answers = getAnswersByQuestionId($qid);

		return [
			"question" => $question,
			"answers" => $answers
		];
	}

	function updateQuestionsViews($qid, $current_views)
	{
		$error = [
			"message" => ""
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
		return true;
	}

	function updateQuestion($qid, $values) {
	  $conn = connection();

    $sql = "
      UPDATE
        m133_forum.question
      SET 
        question_text = '" . $values['question_text'] . "',
        question_description = '" . $values['question_description'] . "'
      WHERE id = " . $qid . "
    ";
    $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    foreach($values['question_tags'] as $tag) {
      $update_question_tag_sql = "
        UPDATE
          m133_forum.question_tag
        SET fk_tag = " . $tag . "
        WHERE fk_question = " . $qid . "
      ";
      mysqli_query($conn, $update_question_tag_sql) or die(mysqli_error($conn));
    }

    foreach($values['question_topics'] as $topic) {
      $update_question_topic_sql = "
        UPDATE
          m133_forum.question_topic
        SET fk_topic = " . $topic . "
        WHERE fk_question = " . $qid . "
      ";
      mysqli_query($conn, $update_question_topic_sql) or die(mysqli_error($conn));
    }

    if ($query) {
      return true;
    }
    return false;
  }

	function setQuestion($values)
	{
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

		mysqli_query($conn, $sql);

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
          mysqli_query($conn, $sql);
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
          mysqli_query($conn, $sql);
        }
      }
    }
	}
