<?php

namespace App\Http\Controllers\Question;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Repositories\Answer\AnswerStudentRepositoryInterface;
use App\Model\Topic;
use App\Model\Level;
use App\Model\Clazz;
use App\Model\TopicClass;

class QuestionController extends Controller
{
    protected $questionRepository;
    protected $userRepository;
    protected $answerRepository;
    protected $answerStudentRepository;    

    public function __construct(QuestionRepositoryInterface $questionRepository,UserRepositoryInterface $userRepository,AnswerRepositoryInterface $answerRepository,AnswerStudentRepositoryInterface $answerStudentRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository;
        $this->answerRepository = $answerRepository;
        $this->answerStudentRepository = $answerStudentRepository;        
    }

    public function getAllQuestionByUserId(Request $request) 
    {
        $userId = $this->getUserId($request);
        if ($userId == -1){
            return  $this->Unauthentication();
        }
        $question = $this->questionRepository->getAllQuestionByUserId($userId);
        return $this->OK($question);
    }

    public function getAllQuestion($level_id, $topic_class_id) 
    {
        $userId = $this->getUserId($request);
        if ($userId == -1){
            return  $this->Unauthentication();
        }
        $question = $this->where([['level_id',$level_id],['topic_class_id',$topic_class_id]]);
        return $question;
    }

    public function getQuestionByTopic($topicId) {
        $question = $this->questionRepository->getQuestionByTopic($topicId);
        return $this->OK($question);
    }

    public function getQuestionByLevel($levelId) {
        $question = $this->questionRepository->getQuestionByLevel($levelId);
        return $this->OK($question);
    }

    public function getQuestionByClass($classId) {
        $question = $this->questionRepository->getQuestionByClass($classId);
        return $this->OK($question);
    }

    public function addQuestion(Request $request) {
        try {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
            }

            $json = json_decode($request->getContent(),true);
            $content = $json['content'];
            $img_link = $json['img_link'];
            $topic_id = $json['topic_id'];
            $level_id = $json['level_id'];
            $class_id = $json['class_id'];
            $is_multichoise = $json['is_multichoise'];
            $number_answer = $json['number_answer'];
            $answer = $json['answer'];
            $listAnswer = array();
            $topic_class_id = TopicClass::where('topic_id',$topic_id)->where('class_id',$class_id)->first()['id'];
            $questionId = $this->questionRepository->insertGetId([
                                            'topic_class_id'=>$topic_class_id,
                                            'level_id'=>$level_id,
                                            'img_link'=>$img_link,
                                            'user_id'=>$user_id,
                                            'content'=>$content,
                                            'is_multichoise'=>$is_multichoise,
                                            'number_answer'=>$number_answer,
                                            'is_public'=>0,
                                            'created_at'=>date("Y-m-d H:m:s"),
                                            'updated_at'=>date("Y-m-d H:m:s")
                                        ]);
            for ($i = 0; $i< count($answer); $i++) {
                $data = ['question_id'=>$questionId,'content'=>$answer[$i]['content'],'is_correct_answer'=>$answer[$i]['is_correct_answer'],'img_link'=>$answer[$i]['img_link'],'created_at'=>date("Y-m-d H:m:s"),
                'updated_at'=>date("Y-m-d H:m:s")];
                $this->answerRepository->insert($data);
            }
           
            return $this->OK('Add question successfully');
        } catch (\Exception $e) {
            return $this->BadRequest($e);
        }  
    }

    public function updateQuestion(Request $request) {
            $user_id = $this->getUserId($request);
            if ($user_id == -1){
                return  $this->Unauthentication();
            }

            $json = json_decode($request->getContent(),true);
            $question_id = $json['question_id'];
            $content = $json['content'];
            $img_link = $json['img_link'];
            $topic_id = $json['topic_id'];
            $level_id = $json['level_id'];
            $class_id = $json['class_id'];
            $is_multichoise = $json['is_multichoise'];
            $number_answer = $json['number_answer'];
            $answer = $json['answer'];
            $listAnswer = array();
            $topic_class_id = TopicClass::where('topic_id',$topic_id)->where('class_id',$class_id)->first()['id'];
            $questionId = $this->questionRepository->updateWith([['id',$question_id]], [
                                            'topic_class_id'=>$topic_class_id,
                                            'level_id'=>$level_id,
                                            'img_link'=>$img_link,
                                            'user_id'=>$user_id,
                                            'content'=>$content,
                                            'is_multichoise'=>$is_multichoise,
                                            'number_answer'=>$number_answer,
                                            'is_public'=>0,
                                            'updated_at'=>date("Y-m-d H:m:s")
                                        ]);
            for ($i = 0; $i< count($answer); $i++) {
                $data = ['content'=>$answer[$i]['content'],'is_correct_answer'=>$answer[$i]['is_correct_answer'],'img_link'=>$answer[$i]['img_link'],'updated_at'=>date("Y-m-d H:m:s")];
                $this->answerRepository->updateWith([['id',$answer[$i]['answer_id']]], $data);
            }
           
            return $this->OK('Update question successfully');
    }

    public function getAllTopic($classId) 
    {
      try {
            $topic = Topic::join('topic_class','topic_class.topic_id','=','topic.id')->where('topic_class.class_id',$classId)->select('topic.id','topic_name')->get();
            return $this->OK($topic);
       } catch (\Exception $ex) {
           return $this->BadRequest($ex);
       }
    }

    public function getAllLevel() {
        try {
            $level = Level::all();
            return $this->OK(json_decode($level));
       } catch (\Exception $ex) {
           return $this->BadRequest($ex);
       }
      
    }
    public function getAllClass() {
        try {
             $class = Clazz::all();
            return $this->OK(json_decode($class));
        } catch (\Exception $ex) {
            return $this->BadRequest($ex);
        }
    }

    public function getQuestionByQuestionId($question_id){
        $question = $this->questionRepository->getQuestionByQuestionId($question_id);
        $question[0]['answer'] = $this->answerRepository->getAnswer($question_id);
        return $this->OK($question);
    }

    public function updateAnswer(Request $request){
        $json = json_decode($request->getContent(),true);
        $answer_id = $json['answer_id'];
        $content = $json['content'];
        $img_link = $json['img_link'];
        $is_corerct_answer = $json['is_correct_answer'];
        $updateAnswer = $this->answerRepository->updatewith([['id',$answer_id]],[
                                                    'content'=>$content,
                                                    'img_link'=>$img_link,
                                                    'is_correct_answer'=>$is_corerct_answer,
                                                    'updated_at'=>date("Y-m-d H:m:s")
            ]);
        return $this->OK('Update Answer Success');
    }

    public function deleteQuestionNonPublic(){
        $getNonPublicQuestion = $this->questionRepository->getNonPublicQuestion();
        foreach ($getNonPublicQuestion as $value){
            $question_id = $value['id'];
            $deleteAnswer = $this->answerRepository->deleteAnswerByQuestionId($question_id);

        }
        $deleteQuestionNonPunlic = $this->questionRepository->deleteQuestionNonPublic();
        if($deleteQuestionNonPunlic == null){
           return $this->BadRequest("No Delete");
        }
        return $this->OK('Delete Success');
   }

   public function deleteQuestion($question_id){
       if($this->questionRepository->isPublic($question_id)){
           return $this->BadRequest("Can't delete question is public");
       }
       $deleteAnswer = $this->answerRepository->deleteAnswerByQuestionId($question_id);
       $deleteQuestion = $this->questionRepository->deleteQuestionByID($question_id);
       if($deleteQuestion == null){
           return $this->BadRequest("No delete");
       }
       return $this->OK("Delete Success");
   }

   public function topQuestionByTopic(){
       $getTopic =  $this->questionRepository->getTopicQuestion();
       $i = 0;
       foreach ($getTopic as $topic){
            $topic_id = $topic['topic_id'];
            $getTopic[$i]['max'] = $this->questionRepository->getMaxCountQuestion($topic_id);
            $i++;
       }
       return $this->OK($getTopic);
   }

   public function getClassByTopicId($topicID){
        try {
            $class = Clazz::join('topic_class','topic_class.class_id','=','classes.id')->where('topic_class.topic_id',$topicID)->select('classes.id','classes.class_name')->get();        
            return $this->OK($class);
        } catch (\Exception $ex) {
        return $this->BadRequest($ex);
    }   
}

}
