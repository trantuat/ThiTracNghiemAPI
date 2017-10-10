<?php
     namespace App\Repositories\Answer;
     use App\Repositories\BaseRepository;

     class AnswerRepository extends BaseRepository implements AnswerRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\Answer::class;
        }

        public function getAnswer($questionId)
        {
            return  $this->where([['question_id',$questionId]]);
        }

        public function getCorrectAnswer($questionId){
            return $this->_model->select('content')
                                ->where([['question_id',$questionId],['is_correct_answer',1]])
                                ->get();
        }

     }
?>