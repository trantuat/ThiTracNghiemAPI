<?php
    namespace App\Repositories\Quiz;
    interface QuizzRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   

        public function getQuestionInQuizz($quizzId,$column);

        public function getAllQuizByUserId($userId);

        public function getAllQuiz();

        public function getAnswerDetail($quizzid);

        public function getQuizzScore($historyId);

        public function getQuizzId($historyId);

        public function getTotalQuestion($quizzId);

        public function getHistoryIdTimes($quizzId);

        public function numberQuizz();

        public function getQuizzByTopic($topic_id);

        public function getQuizzByClass($class_id);

        public function getQuizzByLevel($level_id);

        public function getQuizzByClassAndTopic($class_id,$topic_id);

        public function getQuizzByClassAndLevel($class_id,$level_id);

        public function getQuizzByLevelAndTopic($level_id,$topic_id);

        public function getQuizzByClassAndTopicAndLevel($class_id,$topic_id,$level_id);
        
        
    }
?>