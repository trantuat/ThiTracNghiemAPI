<?php
    namespace App\Repositories\Question;
    interface QuestionRepositoryInterface {
        /**
        * Get all posts only published
        * @return mixed
        */   
        public function getAllQuestionByUserId($userId,$topic_class_id,$is_public);

        public function getQuestionByTopic($topicId);

        public function getQuestionByLevel($levelId);
        
        public function getQuestionByClass($classId);

        public function shuffle();

        public function getAllQuestions($level_id,$topic_class_Id);

        public function getQuestionIsPublic();

        public function getQuestionNonPublic();

        public function getQuestionIsPublicById($userID);

        public function getQuestionNonPublicById($userID);

        public function getQuestionByQuestionId($question_id);
    }
?>