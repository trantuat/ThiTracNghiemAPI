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
    }
?>