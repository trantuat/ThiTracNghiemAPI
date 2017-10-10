<?php
     namespace App\Repositories\Question;
     use App\Repositories\BaseRepository;

     class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\Question::class;
        }

        public function getAllQuestionByUserId($userId)
        {
            $column = ['questions.id as question_id','level_id','level_name','topic_id','topic_name','class_id','class_name','user_id','is_multichoise','number_answer','content','questions.created_at','questions.updated_at'];
           return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic.id','=','topic_class.topic_id')
                                ->join('classes','classes.id','=','topic_class.class_id')
                                ->join('levels','levels.id','=','questions.level_id')
                                ->where('user_id',$userId)
                                ->select($column)
                                ->get();
        }

        public function getQuestionByTopic($topicId) {
            return  $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                 ->where('topic_class.topic_id',$topicId)
                                 ->get();
        }

        public function getQuestionByLevel($levelId) {
            return  $this->where([['level_id',$levelId]]);
        }

        public function getQuestionByClass($classId) {
            return  $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
            ->where('topic_class.class_id',$classId)
            ->get();
        }

        public function shuffle() {
            return $this->_model->shuffle();
        }

        public function getAllQuestions($level_id,$topic_class_Id) {
            return $this->where([['level_id',$level_id],['topic_class_Id',$topic_class_Id],['is_public',1]]);
        }

        public function getQuestionIsPublic(){
            return $this->_model->where('is_public',1)
                                ->get();
        }
        public function getQuestionNonPublic(){
            return $this->_model->where('is_public',0)
                                ->get();
        }

        public function getQuestionIsPublicById($userId){
           $column = ['questions.id as question_id','level_id','level_name','topic_id','topic_name','class_id','class_name','user_id','is_multichoise','number_answer','content','questions.created_at','questions.updated_at'];
           return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic.id','=','topic_class.topic_id')
                                ->join('classes','classes.id','=','topic_class.class_id')
                                ->join('levels','levels.id','=','questions.level_id')
                                ->where('user_id',$userId)
                                ->where('is_public',1)
                                ->select($column)
                                ->get();
        }

        public function getQuestionNonPublicById($userId){
             $column = ['questions.id as question_id','level_id','level_name','topic_id','topic_name','class_id','class_name','user_id','is_multichoise','number_answer','content','questions.created_at','questions.updated_at'];
           return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic.id','=','topic_class.topic_id')
                                ->join('classes','classes.id','=','topic_class.class_id')
                                ->join('levels','levels.id','=','questions.level_id')
                                ->where('user_id',$userId)
                                ->where('is_public',0)
                                ->select($column)
                                ->get();
        }
        
        public function getQuestionByQuestionId($question_id){
            return $this->_model->where('id',$question_id)
                                ->get();
        }
     }
?>