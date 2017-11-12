<?php
     namespace App\Repositories\History;
     use App\Repositories\BaseRepository;

     class HistoryRepository extends BaseRepository implements HistoryRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\History::class;
        }

        public function getMaxQuizzTimes($quizzId){
            return $this->_model->where('quizz_id',$quizzId)
                                ->max('quizz_times');
        }
        
        public function countQuizzId($quizzId,$userId){
            return $this->_model->where([['quizz_id',$quizzId],['user_id',$userId]])
                                ->count('quizz_id');
        }
        
        public function countUserId($quizzId){
            return $this->_model->where('user_id',$quizzId)
                                ->count('user_id');
        }
        
        public function getHistory($userID){
            $history =  $this->_model->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->where([['histories.user_id',$userID],['quizz_times',1]])
                                ->select('quizzes.id','quizzes.quizz_name','quizzes.duration','quizzes.total')
                                ->get();
            $json = array();
            $json['data'] = $history;
            return $json;
        }

        public function getHistoryDetail($userID,$quizz_id){
            $historyDetail = $this->_model->join('quizzes','histories.quizz_id','=','quizzes.id')
                                          ->where([['histories.user_id',$userID],['quizz_id',$quizz_id]])
                                          ->select('quizzes.id','histories.id as histories_id','quizzes.quizz_name','quizzes.duration','quizzes.total','histories.quizz_times','histories.created_at','histories.start_time','histories.end_time')
                                          ->get();
            return $historyDetail;
        }

        public function getHistoryAnswer($userID,$history_id){
            $historyAnswer= $this->_model->join('answer_student','histories.id','=','answer_student.history_id')
                                         ->join('answers','answer_student.question_id','=','answers.question_id')
                                         ->join('questions','answer_student.question_id','questions.id')
                                         ->where([['histories.user_id',$userID],['histories.id',$history_id]])
                                         ->get();
            $json = array();
            $json['data'] = $historyAnswer;
            return $json;
        }

        public function deleteHistory($historyID){
            return $this->_model->where('id',$historyID)
                                ->delete();
        }

        public function top10Score(){
            return $this->_model->join('info','histories.user_id','=','info.user_id')
                                ->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->select('histories.id','histories.user_id','info.fullname','histories.quizz_times','histories.quizz_id','topic.topic_name')
                                ->get();
        }

        public function top10ScoreByTopicID($topic_id){
            return $this->_model->join('info','histories.user_id','=','info.user_id')
                                ->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->where('topic_class.topic_id',$topic_id)
                                ->select('histories.id','histories.user_id','info.fullname','histories.quizz_times','histories.quizz_id','topic.topic_name')
                                ->get();
        }

        public function getDistinctUserID(){
            return $this->_model->select('user_id')
                                ->distinct()
                                ->get();
        }
        
        public function getDistinctUserIDByTopicID($topic_id){
            return $this->_model->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->select('user_id')
                                ->where('topic_class.topic_id',$topic_id)
                                ->distinct()
                                ->get();
        }   
        
        public function getDistinctQuizzID(){
            return $this->_model->select('quizz_id')
                                ->distinct()
                                ->get();
        } 

        public function getDistinctQuizzIDByTopicID($topic_id){
            return $this->_model->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->select('quizz_id')
                                ->where('topic_class.topic_id',$topic_id)
                                ->distinct()
                                ->get();
        }  
        
        public function getDistinctTopicIDByTopicID(){
            return $this->_model->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->select('topic_class.topic_id')
                                ->distinct()
                                ->get();
        }   
        
        public function getFirstRecord($userID, $quizzID){
            return $this->_model->join('info','histories.user_id','=','info.user_id')
                                ->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->select('histories.id','histories.user_id','info.fullname','histories.quizz_times','histories.quizz_id','topic.topic_name')
                                ->where([['histories.user_id',$userID],['histories.quizz_id',$quizzID]])
                                ->first();
        }

        public function getFirstRecordByTopicID($userID, $quizzID, $topicID){
            return $this->_model->join('info','histories.user_id','=','info.user_id')
                                ->join('quizzes','histories.quizz_id','=','quizzes.id')
                                ->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->join('topic','topic_class.topic_id','=','topic.id')
                                ->select('histories.id','histories.user_id','info.fullname','histories.quizz_times','histories.quizz_id','topic.topic_name')
                                ->where([['histories.user_id',$userID],['histories.quizz_id',$quizzID],['topic.id',$topicID]])
                                ->first();
        }
     }
?>