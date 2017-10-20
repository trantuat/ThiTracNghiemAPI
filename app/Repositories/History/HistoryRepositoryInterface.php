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

        public function getDistinctUserID();

        public function getDistinctQuizzID();

        public function getFirstRecord($userID, $quizzID);
    }
?>