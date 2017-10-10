<?php
    namespace App\Repositories\Quiz;
    interface QuizzQuestionRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   

        public function restartQuizz($quizzId);

        public function startQuizz($quizzId);
    }
?>