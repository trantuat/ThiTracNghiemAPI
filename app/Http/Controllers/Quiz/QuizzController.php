<?php

namespace App\Http\Controllers\Quiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Repositories\Answer\AnswerStudentRepositoryInterface;
use App\Repositories\Quiz\QuizzRepositoryInterface;
use App\Repositories\Quiz\QuizzQuestionRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use App\Model\TopicClass;

class QuizzController extends Controller
{
    protected $questionRepository;
    protected $userRepository;
    protected $answerRepository;
    protected $quizRepository;
    protected $quizQuestionRepository;
    protected $answerStudentRepository;
    protected $historyRepository;
    
    public function __construct(QuestionRepositoryInterface $questionRepository,
                                UserRepositoryInterface $userRepository,
                                AnswerRepositoryInterface $answerRepository,
                                QuizzRepositoryInterface $quizRepository,
                                QuizzQuestionRepositoryInterface $quizQuestionRepository,
                                AnswerStudentRepositoryInterface $answerStudentRepository,
                                HistoryRepositoryInterface $historyRepository )
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository;
        $this->answerRepository = $answerRepository;
        $this->quizRepository = $quizRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
        $this->answerStudentRepository = $answerStudentRepository;
        $this->historyRepository = $historyRepository;
    }

    public function getAllQuiz(Request $request) {
        // try {
            
            $quiz = $this->quizRepository->getAllQuiz(); 
            return $this->OK($quiz);
        // } catch (\Exception $e) {
        //     return $this->BadRequest($e);
        // }   
    }

    public function getAllQuizByUserId(Request $request) {
        //    try {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
            }
            $quiz = $this->quizRepository->getAllQuizByUserId($user_id);
            return $this->OK($quiz);
      //  } catch (\Exception $e) {
          //  return $this->BadRequest($e);
        //}   
    }

    public function createQuizz(Request $request) {
        //  try {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
            }
            $class_id = $request->class_id;
            $level_id = $request->level_id;
            $quizName = $request->quizz_name;
            $duration = $request->duration;
            $total = $request->total;
            $topic_id = $request->topic_id;

            $topic_class_id = TopicClass::where('topic_id',$topic_id)->where('class_id',$class_id)->first()['id'];
            
            $questions = $this->questionRepository->getAllQuestions($level_id,$topic_class_id);
            $questionsRandom = $questions->shuffle()->slice(0,$total);
            $json = json_decode($questionsRandom);
            if (count($json) < $total ) {
                return $this->BadRequest('Not enough number question');
            }
             $quiz_id = $this->addQuizz(['quizz_name'=>$quizName,
                                        'duration'=>$duration,
                                        'user_created_id'=>$user_id,
                                        'topic_class_id'=>$topic_class_id,
                                        'level_id'=>$level_id,
                                        'total'=>$total,
                                        'created_at'=>date("Y-m-d H:m:s"),
                                        'updated_at'=>date("Y-m-d H:m:s")]);            
             foreach ($json as $value) {
                  $this->addQuizzQuestion(['quizz_id'=>$quiz_id,
                                            'question_id'=>$value->id,
                                            'created_at'=>date("Y-m-d H:m:s"),
                                            'updated_at'=>date("Y-m-d H:m:s")
                                            ]);
             }
             return $this->OK($questionsRandom);
        //  } catch (\Exception $e) {
        //     return $this->BadRequest($e);
        // }
    }
    public function startQuizz(Request $request, $quizzId) {
        try {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
            }
            $column = ['quizzes.id as quizz_id','questions.content','is_multichoise','number_answer','questions.img_link','quizz_questions.question_id as question_id','duration'];
            $quizzes = $this->quizRepository->getQuestionInQuizz($quizzId,$column);
            for ($i=0;$i<count($quizzes);$i++) {
                $quizzes[$i]['answer'] = $this->answerRepository->getAnswer($quizzes[$i]['question_id']) ;
            }
            return $this->OK($quizzes);
        } catch (\Exception $e) {
            return $this->BadRequest($e);
        }
    }


    public function restartQuizz($quizzId) {
        // try {
            $quizz =  $this->quizRepository->getQuizzDetail($quizzId);
            return $this->OK($quizz);
        // } catch (\Exception $e) {
        //     return $this->BadRequest($e);
        // }
    }

    public function getAnswer(Request $request,$quizzId) {
        try {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
                
            }
            $column = ['quizzes.id as quizz_id','quizz_questions.question_id as question_id','duration'];
            $quizzes = $this->quizRepository->getQuestionInQuizz($quizzId,$column);
            for ($i=0;$i<count($quizzes);$i++) {
                $quizzes[$i]['answer'] = $this->answerRepository->getAnswer($quizzes[$i]['question_id']) ;
            }
            return $this->OK($quizzes);
        } catch (\Exception $e) {
            return $this->BadRequest($e);
        }
       
    }

    // Private region
    private function addQuizz(array $attribute) {
        try {
            $quizId = $this->quizRepository->insertGetId($attribute);
            return $quizId;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function saveAnswer(array $attribute) {
        try {
         $this->quizQuestionRepository->updateWith(
                [['question_id',$attribute['question_id']],['quizz_id',$attribute['quizz_id']]], 
                ['option_choose'=>$attribute['option_choose'],
                 'updated_at'=>date("Y-m-d H:m:s")]);
         } catch (\Exception $e) {
            throw $e;
         }
    }

    private function addQuizzQuestion(array $attribute) {
        try {
            $quizId = $this->quizQuestionRepository->insert($attribute);
            return $quizId;
        } catch (\Exception $e) {
            throw $e;
        }
    }

     public function userAnswerQuestion(Request $request){
        $json = json_decode($request->getContent(),true);
        $user_id = $this->getUserId($request);
        if ($user_id == -1){
            return  $this->Unauthentication();
            
        }
        $quizz_id = $json['quizz_id'];
        $answer = $json['answer'];
        $maxQuizzTimes = $this->historyRepository->getMaxQuizzTimes($quizz_id);
        $countQuizzId = $this->historyRepository->countQuizzId($quizz_id);
        if($countQuizzId > 0 ) $quizzTimes = $maxQuizzTimes + 1;
        else $quizzTimes=1;
        $history_id = $this->historyRepository->insertGetID([
            'user_id'=>$user_id,
            'quizz_id'=>$quizz_id,
            'quizz_times'=>$quizzTimes
        ]);
        
        for( $i=0; $i < count($answer); $i++){
            $data = ['history_id'=>$history_id,'question_id'=>$answer[$i]['question_id'],'option_choose'=>$answer[$i]['option_choose']];
            $this->answerStudentRepository->insert($data);
        }
        return $this->OK("$history_id");
    }

    public function getNumberQuestion($quizzid){
        return $this->quizQuestionRepository->getNumberQuestion($quizzid);
    }

    public function getHistoryScore($quizzId){
        return $this->quizRepository->getHistoryScore($quizzId);
    }

    public function getQuizzScore($historyId){
        return $this->quizRepository->getQuizzScore($historyId);
    }

    public function getHistory(Request $request){
        $user_id = $this->getUserId($request);
        if ($user_id == -1){
            return  $this->Unauthentication();
        }
        return $this->historyRepository->getHistory($user_id);
    }

    public function getHistoryDetail(Request $request,$quizz_id){
        $user_id = $this->getUserId($request);
        if ($user_id == -1){
            return  $this->Unauthentication();
        }
        return $this->historyRepository->getHistoryDetail($user_id,$quizz_id);
    }

    public function getHistoryAnswer(Request $request,$history_id){
        $user_id = $this->getUserId($request);
        if ($user_id == -1){
            return  $this->Unauthentication();
        }
        return $this->historyRepository->getHistoryAnswer($user_id,$history_id);
    }

    public function getAnswerDetail($historyId){
        $answer = json_decode($this->quizRepository->getAnswerDetail($historyId),true);
        $json1 = array();        
        foreach($answer as $value){
            $json = array();
            $question_id = $value['question_id'];
            $answer1 = json_decode($this->answerRepository->getAnswer($question_id),true);
            foreach($answer1 as $value1){
                $quest_id = $value1['question_id'];
                array_push($json,$quest_id);                                                                        
                $content=$value1['content'];
                array_push($json,$content);    
            }
            $data[] = $json;
            unset($json);
        }
        
        return $data;
    }

    public function getHistoryAnswerDetail($historyId){
        $answer = $this->getAnswerDetail($historyId);
        $question =  json_decode($this->quizRepository->getAnswerDetail($historyId),true);
        $json = array();
        $json1 = array();
        
        foreach($answer as $value){
             $json['question_id'] = $value[0];
           try{                       
             $json['answer1'] = $value[1];
             $json['answer2'] = $value[3];
             $json['answer3'] = $value[5];
             $json['answer4'] = $value[7];
             $json['answer5'] = $value[9];
             $json['answer6'] = $value[11];             
             $json['answer7'] = $value[13];        
           }catch(\Exception $ex){              
           }             
             $data[]=$json;
        }
        foreach($question as $quest){
             foreach($data as $dt){
                if($dt['question_id'] == $quest['question_id']){                  
                    $json1 = $dt;
                    $json1['content'] = $quest['content'];
                    $json1['img_link'] = $quest['img_link'];             
                    $json1['is_multichoise'] = $quest['is_multichoise'];
                    $option_choose = $this->answerStudentRepository->getOptionChoose($dt['question_id'],$historyId);
                    try{
                        $json1['option_choose1'] = $option_choose[0]['option_choose'];
                        $json1['option_choose2'] = $option_choose[1]['option_choose'];                
                        $json1['option_choose3'] = $option_choose[2]['option_choose'];                
                        $json1['option_choose4'] = $option_choose[3]['option_choose'];                
                        $json1['option_choose5'] = $option_choose[4]['option_choose'];                                    
                    }catch(\Exception $ex){
                    }
                    $correct_answer =  $this->answerRepository->getCorrectAnswer($dt['question_id']);
                    try{
                        $json1['correct_answer1'] = $correct_answer[0]['content'];
                        $json1['correct_answer2'] = $correct_answer[1]['content'];                
                        $json1['correct_answer3'] = $correct_answer[2]['content'];                
                        $json1['correct_answer4'] = $correct_answer[3]['content'];                
                        $json1['correct_answer5'] = $correct_answer[4]['content'];                                    
                    }catch(\Exception $ex){
                    }
                    $data1[] = $json1;
                       
                 }             
              }
        }
        $result = array();
        $result['data'] = $data1;
        return $result ;
    }
}
