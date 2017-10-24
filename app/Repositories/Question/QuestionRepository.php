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
            $column = ['questions.id as question_id','level_id','level_name','topic_id','topic_name','class_id','class_name','user_id','is_multichoise','number_answer','content','questions.created_at','questions.updated_at'];            
            return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic.id','=','topic_class.topic_id')
                                ->join('classes','classes.id','=','topic_class.class_id')
                                ->join('levels','levels.id','=','questions.level_id')
                                ->where('questions.is_public',1)
                                ->select($column)
                                ->get();
        }
        public function getQuestionNonPublic(){
            $column = ['questions.id as question_id','level_id','level_name','topic_id','topic_name','class_id','class_name','user_id','is_multichoise','number_answer','content','questions.created_at','questions.updated_at'];                        
            return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic.id','=','topic_class.topic_id')
                                ->join('classes','classes.id','=','topic_class.class_id')
                                ->join('levels','levels.id','=','questions.level_id')
                                ->where('is_public',0)
                                ->select($column)
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
            return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->join('classes','topic_class.class_id','=','classes.id')
                                ->join('levels','questions.level_id','=','levels.id')
                                ->where('questions.id',$question_id)
                                ->get();
        }

        public function numberQuestionPublic($userID){
            return $this->_model->where([['user_id',$userID],['is_public',1]])
                                ->count();
        }

        public function numberQuestionNonPublic($userID){
            return $this->_model->where([['user_id',$userID],['is_public',0]])
                                ->count();
        }

        public function numberQuestion(){
            return $this->_model->count();
        }

        public function top10QuestionPosted(){
            $column = ['questions.id','questions.content','questions.img_link','questions.is_public','questions.number_answer','questions.updated_at','info.fullname'];
            return $this->_model->join('info','questions.user_id','=','info.user_id')
                                ->select($column)
                                ->take(10)
                                ->orderBy("updated_at",'desc')
                                ->get();
        }

        public function getIsPublicQuestion($questionID){
            return $this->_model->where('id',$questionID)
                                ->select('is_public')
                                ->get();
        }

        public function getNonPublicQuestion(){
            return $this->_model->where('is_public',0)
                                ->select('id')
                                ->get();
        }

        public function deleteQuestionNonPublic(){
            return $this->_model->where('is_public',0)
                                ->delete();
        }

        public function deleteQuestionByID($question_id){
            return $this->_model->where('id',$question_id)
                                ->delete();
        }

        public function getMaxCountQuestion($topic_id){
            return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('info','questions.user_id','=','info.user_id')
                                ->select('questions.user_id','info.fullname')
                                ->selectRaw('count(*) as count')
                                ->where('topic_class.topic_id',$topic_id)
                                ->groupBy('user_id')
                                ->orderBy('count','desc')
                                ->first();
        }

        public function getTopicQuestion(){
            return $this->_model->join('topic_class','questions.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->select('topic_class.topic_id','topic.topic_name')
                                ->distinct()
                                ->get();
        }

         public function isPublic($questionID) {
             $q = $this->_model->where('id',$questionID)
                                ->select('is_public')
                                ->first();
             return $q->is_public == 1;
         }
     }
?>