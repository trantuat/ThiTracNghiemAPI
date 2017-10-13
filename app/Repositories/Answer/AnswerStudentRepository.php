<?php
     namespace App\Repositories\Answer;
     use App\Repositories\BaseRepository;

     class AnswerStudentRepository extends BaseRepository implements AnswerStudentRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\AnswerStudent::class;
        }

        public function getOptionChoose($questionID,$historyID){
            return $this->_model->select('option_choose')
                                ->where([['history_id',$historyID],['question_id',$questionID]])
                                ->get();
            
        }

        public function countOptionChoose($questionID,$historyID){
            return $this->_model->where([['history_id',$historyID],['question_id',$questionID]])
                                ->count();
        }

        public function numberCorrectAnswer($questionID,$historyID){
            return $this->_model->join("answers",'answer_student.option_choose','=','answers.id')
                                ->where([['answer_student.history_id',$historyID],['answer_student.question_id',$questionID],['is_correct_answer',1]])
                                ->select('answer_student.option_choose','answers.is_correct_answer','answer_student.question_id','answers.id as answer_id')
                                ->count();
        }
        
        public function deleteAnswerStudent($historyID){
            return $this->_model->where('history_id',$historyID)
                                ->delete();
        }
     }
?>