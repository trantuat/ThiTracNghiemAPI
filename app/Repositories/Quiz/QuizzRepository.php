<?php
     namespace App\Repositories\Quiz;
     use App\Repositories\BaseRepository;
     use App\Repositories\Answer\AnswerStudentRepositoryInterface;     

     class QuizzRepository extends BaseRepository implements QuizzRepositoryInterface {
       
        public function getModel()
        {
            return \App\Model\Quizz::class;
        }
        
        public function getQuestionInQuizz($quizzId,$column)
        {
             return $this->_model->join('quizz_questions','quizzes.id','=','quizz_questions.quizz_id')
                                ->join('questions','questions.id','=','quizz_questions.question_id')
                                ->where('quizzes.id',$quizzId)
                                ->select($column)
                                ->get();
        }
        
        public function getAnswerDetail($quizzId){
            return $this->_model->join('histories','quizzes.id','=','histories.quizz_id')
                                ->join('answer_student','histories.id','=','answer_student.history_id')
                                ->join('questions','answer_student.question_id','=','questions.id')
                                ->join('answers','answer_student.question_id','=','answers.question_id')
                                //->select('answer_student.history_id','quizzes.id as quizz_id','answer_student.question_id','answer_student.option_choose')
                                ->select('answers.is_correct_answer','answer_student.history_id','answer_student.question_id','questions.content','answer_student.option_choose','questions.img_link','questions.is_multichoise','answers.id','histories.user_id')
                                ->where('histories.id',$quizzId)
                                ->groupBy('answer_student.question_id')
                                ->orderBy('answer_student.id')                                
                                ->get();
        }

        public function getQuestionTimes($quizzId){
            return $this->_model->join('quizz_questions','quizzes.id','=','quizz_questions.quizz_id')
                                ->join('answers','quizz_questions.question_id','=','answers.question_id')
                                ->select('answers.question_id')
                                ->selectRaw('count(*) as count')
                                ->where([['answers.is_correct_answer',1],['quizzes.id',$quizzId]])                             
                                ->groupBy('answers.question_id')                                
                                ->get();
        }

        public function getHistoryIdTimes($quizzId){
            return $this->_model->join('histories','quizzes.id','=','histories.quizz_id')
                                ->join('answer_student','histories.id','=','answer_student.history_id')
                                ->select('answer_student.history_id')
                                ->groupBy('answer_student.history_id')
                                ->get();
        }

        public function getTotalQuestion($quizzId){
            return $this->_model->select('total')
                                ->where('id',$quizzId)
                                ->get();
        }

        public function getQuizzId($historyId){
            return $this->_model->join('histories','quizzes.id','=','histories.quizz_id')
                                ->where('histories.id',$historyId)
                                ->select('quizzes.id')
                                ->get();
        }

        public function getQuizzScore($historyId){
            $answer =  $this->_model->join('histories','quizzes.id','=','histories.quizz_id')
                                    ->join('answer_student','histories.id','=','answer_student.history_id')
                                    ->join('answers','answer_student.question_id','=','answers.question_id')
                                    ->select('answer_student.history_id','quizzes.id as quizz_id','answer_student.question_id','answer_student.option_choose','answers.id','answers.is_correct_answer')
                                    ->where('histories.id',$historyId)
                                    ->get();
            $jsonanswer = json_decode($answer,true);
            $getQuizzId = json_decode($this->getQuizzId($historyId),true);
            $quizzId = $getQuizzId[0]['id'];
            $questionCount = json_decode($this->getQuestionTimes($quizzId),true);
            $getTotalQuestion = json_decode($this->getTotalQuestion($quizzId),true);
            $totalQuestion = $getTotalQuestion[0]['total'];
            $correct = 0;
            $count = 1;
            foreach($jsonanswer as $answer){
                //return $this->answerStudentRepository->numberCorrectAnswer($answer['question_id'],$answer['hsitory_id']);
                if($answer['option_choose'] == $answer['id']){
                    if($answer['is_correct_answer'] == 1){
                        foreach($questionCount as $countq){
                            if($answer['question_id'] == $countq['question_id']){
                                $count = 1/$countq['count'];
                                $correct = $correct + $count;
                            }
                        }
                        $count =1;
                    }
                }
            }
            $json = array();
            $json['correct_answer'] = $correct;
            $json['wrong_answer'] = $totalQuestion - $correct;
            $json['score'] =($correct/$totalQuestion)*10;
            $json1 = array();
            $json1['data'] =$json;
            return $json1;
        }

        public function getHistoryScore($quizzId){
            $answers = json_decode($this->getAnswerDetail($quizzId),true);
            $questionCount = json_decode($this->getQuestionTimes($quizzId),true);
            $historyTimes = json_decode($this->getHistoryIdTimes($quizzId),true);
            $correct = 0;
            $count = 1;
            $getTotalQuestion = json_decode($this->getTotalQuestion($quizzId),true);
            $totalQuestion = $getTotalQuestion[0]['total'];
            foreach($historyTimes as $time){
                foreach($answers as $value1){            
                    if($time['history_id'] == $value1['history_id']){
                        if($value1['option_choose'] == $value1['id']){
                            if($value1['is_correct_answer'] == 1){
                            foreach($questionCount as $value2){
                                if($value1['question_id'] == $value2['question_id']){
                                    $count = 1/$value2['count'];
                                    $correct = $correct + $count;
                                }
                            }
                            $count =1;
                        }
                        }
                    }
                }
                $json = array();
                $json['history_id'] = $time['history_id'];
                $json['correct_answer'] = $correct;
                $json['total_question'] = $totalQuestion;
                $json['score'] = ($correct/$totalQuestion)*10;
                $data[] = $json;
                $correct = 0;
            }
            return $data;
        }

        public function getAllQuizByUserId($userId) {
            $column = ['quizzes.id as quizz_id','user_created_id','quizz_name','duration','total','topic_class_id','level_id','level_name','class_id','class_name','topic_id','topic_name','quizzes.created_at','quizzes.updated_at','username'];
            return $this->_model->join('levels','level_id','=','levels.id')
            ->join('topic_class','topic_class_id','=','topic_class.id')
            ->join('classes','topic_class.class_id','=','classes.id')
            ->join('topic','topic_class.topic_id','=','topic.id')
            ->join('users','quizzes.user_created_id','=','users.id')
            ->where([["user_created_id",$userId]])->select($column)->get();
        }

        public function getAllQuiz() {
            $column = ['quizzes.id as quizz_id','user_created_id','quizz_name','duration','total','topic_class_id','level_id','level_name','class_id','class_name','topic_id','topic_name','quizzes.created_at','quizzes.updated_at','username'];
            return $this->_model->join('levels','level_id','=','levels.id')
            ->join('topic_class','topic_class_id','=','topic_class.id')
            ->join('classes','topic_class.class_id','=','classes.id')
            ->join('topic','topic_class.topic_id','=','topic.id')
            ->join('users','user_created_id','=','users.id')
            ->select($column)->get();
        }

        public function numberQuizz(){
            return $this->_model->count();
        }

        public function getQuizzByTopic($topic_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where('topic_class.topic_id',$topic_id)
                                ->get();
        }

        public function getQuizzByClass($class_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where('topic_class.class_id',$class_id)
                                ->get();
        }

        public function getQuizzByLevel($level_id){
            return $this->_model->where('level_id',$level_id)
                                ->get();
        }

        public function getQuizzByClassAndTopic($class_id,$topic_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where([['topic_class.class_id',$class_id],['topic_class.topic_id',$topic_id]])
                                ->get();
        }
        
        public function getQuizzByClassAndLevel($class_id,$level_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where([['topic_class.class_id',$class_id],['quizzes.level_id',$level_id]])
                                ->get();
        }
        
        public function getQuizzByLevelAndTopic($level_id,$topic_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where([['topic_class.topic_id',$topic_id],['quizzes.level_id',$level_id]])
                                ->get();
        }
        
        public function getQuizzByClassAndTopicAndLevel($class_id,$topic_id,$level_id){
            return $this->_model->join('topic_class','quizzes.topic_class_id','=','topic_class.id')
                                ->where([['topic_class.class_id',$class_id],['topic_class.topic_id',$topic_id],['quizzes.level_id',$level_id]])
                                ->get();
        }
     }
     
?>