<?php
require_once "global_functions.php";
require_once "db_conn.php";

function getAllTopics() {
  $sql = "
			SELECT
			  id,
			  topic_name
			FROM m133_forum.topic
		";
  $query = mysqli_query(connection(), $sql);
  return fetchAll($query);
}

function getTopicById($tid) {
  $sql = "
			SELECT
			  topic_name,
			FROM m133_forum.topic
			WHERE id = " . $tid . "
		";

  return fetch($sql, "Konnte das gewünschte Thema nicht finden.");
}

function getQuestionsTopics($qid) {
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
  return fetchAll($query);
}

function setTopic($qid, $tid) {
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
