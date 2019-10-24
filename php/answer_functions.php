<?php

	require_once "db_conn.php";
	require_once "global_functions.php";

	function getAnswerById($aid) {
		$error = [
			"message" => ''
		];
		$aid = escape($aid);
		$sql = "
			SELECT
				id,
				answer_text
			FROM m133_forum.answer
			WHERE id = " . $aid . "
		";
		$query = mysqli_query(connection(), $sql);
		return fetch($query, "Konnte die gewünschte Antwort nicht finden.");
	}

	function updateAnswerById($aid, $answer_text) {
		$error = [
			"message" => ''
		];

		$aid = escape($aid);
		$answer_text = escape($answer_text);

		$sql = "
			UPDATE
				m133_forum.answer
			SET
				answer_text = '" . $answer_text . "'
			FROM m133_forum.answer
			WHERE id = " . $aid . "
		";
		$query = mysqli_query(connection(), $sql);
		return fetch($query, "Konnte die gewünschte Antwort nicht finden.");
	}

	function getAnswersByQuestionId($qid, $limit = null) {
		$answers = [];
		$error = [
			"message" => ''
		];
		$limit_sql = '';
		if ($limit !== null) {
			$limit_sql = "LIMIT " . $limit;
		}

		$qid = escape($qid);
		$sql = "
			SELECT
				id,
				answer_text
			FROM m133_forum.answer
			WHERE fk_question = " . $qid . "
			" . $limit_sql . "
		";
		$query = mysqli_query(connection(), $sql);
		return fetchAll($query);
	}

	function getAllAnswers() {
		$sql = "
			SELECT
				id,
				answer_text
			FROM m133_forum.answer
		";
		$query = mysqli_query(connection(), $sql);
		return fetch($query, "Konnte die gewünschten Antworten nicht finden.");
	}

	function setAnswer($values) {
		$escaped_answer_text = escape($values["answer_text"]);
		$escaped_question_id = escape($values["question_id"]);
		$escaped_uid = escape($values["uid"]);

		$sql = "
			INSERT INTO m133_forum.answer (
				answer_text,
			  fk_question,
			  points,
				fk_user
			)
			VALUES (
			  '" . $escaped_answer_text . "',
			  " . $escaped_question_id . ",
			  0,
			  " . $escaped_uid . "
			)
		";
		$query = mysqli_query(connection(), $sql);

		if ($query) {
		  return true;
    }
		return false;
	}
