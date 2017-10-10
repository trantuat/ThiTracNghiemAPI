<?php

namespace App\Http\Controllers\Question;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Model\Topic;
use App\Model\Level;
use App\Model\Clazz;
use App\Model\TopicClass;

class QuestionController extends Controller
{
    protected $questionRepository;
    protected $userRepository;
    protected $answerRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository,UserRepositoryInterface $userRepository,AnswerRepositoryInterface $answerRepository )
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository;
        $this->answerRepository = $answerRepository;
    }

    public function getAllQuestionByUserId(Request $request) 
    {
        $userId = $this->getUserId($request);       
        if ($userId == -1){
            return  $this->Unauthentication();
        }
        $level_id = $request->header('level_id');
        $topic_id = $request->header('topic_id');
        $class_id = $request->header('class_id');
        $is_public = $request->header('is_public'); 
        $topic_class_id = TopicClass::where('topic_id',$topic_id)->where('class_id',$class_id)->first()['id'];
        if ($topic_class_id==null){
            return $this->BadRequest('topic_class_id Not Found');
        }
        $question = $this->questionRepository->getAllQuestionByUserId($userId,$topic_class_id,$is_public);
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
        return $this->OK($question);
    }

}
