<?php
    namespace App\Repositories\History;
    interface HistoryRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   
        public function getMaxQuizzTimes($quizzId);

        public function countQuizzId($quizzId,$userId);

        public function countUserId($quizzId);

        public function getHistory($userID);

        public function getHistoryDetail($userID,$quizz_id);
        
        public function getHistoryAnswer($userID,$history_id);

        public function deleteHistory($historyID);

        public function top10Score();

        public function top10ScoreByTopicID($topic_id);

        public function getFirstRecordByTopicID($userID, $quizzID, $topicID);

        public function getDistinctUserID();

        public function getDistinctUserIDByTopicID($topic_id);

        public function getDistinctQuizzID();

        public function getDistinctQuizzIDByTopicID($topic_id);

        public function getDistinctTopicIDByTopicID();

        public function getFirstRecord($userID, $quizzID);
    }
?>