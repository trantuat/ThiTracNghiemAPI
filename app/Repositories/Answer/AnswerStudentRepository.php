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
        
     }
?>