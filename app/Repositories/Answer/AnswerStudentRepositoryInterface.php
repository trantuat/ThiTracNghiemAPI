<?php
    namespace App\Repositories\Answer;
    interface AnswerStudentRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   
        public function getOptionChoose($questionID,$historyID);
        public function countOptionChoose($questionID,$historyID);
        public function numberCorrectAnswer($questionID,$historyID);
    }
?>