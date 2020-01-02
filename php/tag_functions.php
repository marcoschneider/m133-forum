<?php

require_once "global_functions.php";
require_once "db_conn.php";

function getAllTags()
{
	$tags = [];
	$sql = "
			SELECT
			  id,
			  short_tag_name,
				tag_name
			FROM m133_forum.tag
		";
	$query = mysqli_query(connection(), $sql);
	$error = [
		"message" => ""
	];
	$results = [];
	
	if ($query) {
		while ($result = mysqli_fetch_assoc($query)) {
			$results[$result['id']] = $result;
		}
		return $results;
	}
	return $error['message'] = "Konnte die Query nicht ausführen!";
}

function getTagById($tid)
{
	$sql = "
			SELECT
			  short_tag_name,
				tag_name
			FROM m133_forum.tag
			WHERE id = " . $tid . "
		";
	
	$query = mysqli_query(connection(), $sql);
	if ($query) {
		$result = mysqli_fetch_assoc($query);
		if ($result) {
			return $result;
		}
		return $error['message'] = "Konnte den gewünschten Tag nicht finden.";
	}
	return false;
}

function getQuestionsTags($qid)
{
	$sql = "
			SELECT
        t.id,
				short_tag_name,
				tag_name
			FROM m133_forum.question_tag
			INNER JOIN m133_forum.tag t ON question_tag.fk_tag = t.id
			INNER JOIN m133_forum.question ON question_tag.fk_question = question.id
			WHERE question.id = " . $qid . "
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

function setTag($qid, $tid)
{
	$sql = "
    INSERT INTO m133_forum.question_tag (
      fk_question,
      fk_tag
    ) VALUES (
      " . $qid . ",
      " . $tid . "
    )
  ";
	
	$query = mysqli_query(connection(), $sql);
	
	if ($query) {
		return true;
	}
	return false;
}
