<?php
     namespace App\Repositories\Quiz;
     use App\Repositories\BaseRepository;

     class QuizzQuestionRepository extends BaseRepository implements QuizzQuestionRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\QuizzQuestion::class;
        }

        public function restartQuizz($quizzId) 
        {
            return $this->_model->join('questions','questions.id','=','quizz_questions.question_id')
                ->where('quizz_questions.quizz_id',$quizzId)
                ->get();
        }

        public function startQuizz($quizzId) 
        {
            return $this->_model->join('questions','questions.id','=','quizz_questions.question_id')
                ->where('quizz_questions.quizz_id',$quizzId)
                ->get();
        }

        public function getNumberQuestion($quizzid){
            return $this->_model->where('quizz_id',$quizzid)->count();
        }
    }
?>