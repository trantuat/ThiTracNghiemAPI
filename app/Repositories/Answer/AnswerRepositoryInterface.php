<?php
    namespace App\Repositories\Answer;
    interface AnswerRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   
        public function getAnswer($questionId);

        public function getCorrectAnswer($questionId);
        
        public function deleteAnswerByQuestionId($questionID);

        public function deleteAnswerByAnswerId($answerID);
    }
?>