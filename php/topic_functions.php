<?php
require_once "global_functions.php";
require_once "db_conn.php";

function getAllTopics()
{
	$sql = "
			SELECT
			  id,
			  topic_name
			FROM m133_forum.topic
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

function getTopicById($tid)
{
	$sql = "
			SELECT
			  topic_name,
			FROM m133_forum.topic
			WHERE id = " . $tid . "
		";
	$query = mysqli_query(connection(), $sql);
	
	if ($query) {
		$result = mysqli_fetch_assoc($query);
		if ($result) {
			return $result;
		}
		return $error['message'] = "Konnte das gewünschte Thema nicht finden.";
	}
	return false;
}

function getQuestionsTopics($qid)
{
	$sql = "
			SELECT
			  topic.id,
				topic_name
			FROM m133_forum.question_topic
			INNER JOIN m133_forum.topic ON question_topic.fk_topic = topic.id
			INNER JOIN m133_forum.question ON question_topic.fk_question = question.id
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

function setTopic($qid, $tid)
{
	$sql = "
    INSERT INTO m133_forum.question_topic (
      fk_question,
      fk_topic
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
