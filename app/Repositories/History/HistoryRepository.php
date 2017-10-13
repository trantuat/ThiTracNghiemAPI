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
        
        public function countQuizzId($quizzId){
            return $this->_model->where('quizz_id',$quizzId)
                                ->count('quizz_id');
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
                                          ->select('quizzes.id','histories.id as histories_id','quizzes.quizz_name','quizzes.duration','quizzes.total','histories.quizz_times','histories.created_at')
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
     }
?>