<?php

require_once "db_conn.php";
require_once "global_functions.php";

function getAnswerById($aid)
{
	$conn = connection();
	$aid = escape($aid);
	$sql = "
			SELECT
				id,
				answer_text,
        points
			FROM m133_forum.answer
			WHERE id = " . $aid . "
		";
	$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	if ($query) {
		$result = mysqli_fetch_assoc($query);
		if ($result) {
			return $result;
		}
		return $error['message'] = "Konnte die gewünschte Antwort nicht finden.";
	}
	return false;
}

function deleteAnswerById($aid)
{
	$conn = connection();
	$aid = escape($aid);
	$sql = "
      DELETE FROM
        m133_forum.answer
      WHERE id = " . $aid . "
    ";
	$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
	if ($query) {
		return TRUE;
	}
	return FALSE;
}

function voteAnswerById($aid, $type)
{
	$conn = connection();
	$answer = getAnswerById($aid);
	$points = $answer['points'];
	
	switch ($type) {
		case 'downvote':
			$points = $answer['points'] - 1;
			break;
		case 'upvote':
			$points = $answer['points'] + 1;
			break;
	}
	
	$sql = "
    UPDATE
      m133_forum.answer
    SET points = " . $points . "
    WHERE id = " . $aid . "
  ";
	
	$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	
	if ($query) {
		return TRUE;
	}
	return FALSE;
}

function updateAnswerById($aid, $answer_text)
{
	$conn = connection();
	$aid = escape($aid);
	$answer_text = escape($answer_text);
	
	$sql = "
			UPDATE
				m133_forum.answer
			SET
				answer_text = '" . $answer_text . "'
			WHERE id = " . $aid . "
		";
	return mysqli_query($conn, $sql) or die(mysqli_error($conn));
}

function getAnswersByQuestionId($qid, $limit = NULL)
{
	$limit_sql = '';
	if ($limit !== NULL) {
		$limit_sql = "LIMIT " . $limit;
	}
	
	$qid = escape($qid);
	$sql = "
			SELECT
				id,
				answer_text,
        points,
        approved,
			  fk_user
			FROM m133_forum.answer
			WHERE fk_question = " . $qid . "
			" . $limit_sql . "
		";
	$query = mysqli_query(connection(), $sql);
	$results = [];
	
	if ($query) {
		while ($result = mysqli_fetch_assoc($query)) {
			$results[$result['id']] = $result;
		}
		return $results;
	}
	return false;
}

function setAnswerApproved($aid)
{
	$conn = connection();
	$sql = "
    UPDATE
      m133_forum.answer
    SET approved = 1
    WHERE id = " . $aid . "
  ";
	
	$query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	return $query;
}

function getAllAnswers()
{
	$sql = "
			SELECT
				id,
				answer_text
			FROM m133_forum.answer
		";
	$query = mysqli_query(connection(), $sql);
	if ($query) {
		$result = mysqli_fetch_assoc($query);
		if ($result) {
			return $result;
		}
		return $error['message'] = "Konnte die gewünschten Antworten nicht finden.";
	}
	return false;
}

function setAnswer($values)
{
	$escaped_answer_text = escape($values["answer_text"]);
	$escaped_question_id = escape($values["question_id"]);
	$escaped_uid = escape($values["uid"]);
	
	$sql = "
			INSERT INTO m133_forum.answer (
				answer_text,
			  fk_question,
			  points,
				fk_user,
        approved
			)
			VALUES (
			  '" . $escaped_answer_text . "',
			  " . $escaped_question_id . ",
			  0,
			  " . $escaped_uid . ",
			  0
			)
		";
	$query = mysqli_query(connection(), $sql);
	
	if ($query) {
		return TRUE;
	}
	return FALSE;
}
