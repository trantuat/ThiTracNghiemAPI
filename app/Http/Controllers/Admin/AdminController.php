<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Answer\AnswerRepositoryInterface;
use App\Repositories\Answer\AnswerStudentRepositoryInterface;
use App\Repositories\Quiz\QuizzRepositoryInterface;
use App\Repositories\Quiz\QuizzQuestionRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use App\Model\Topic;
use App\Model\Level;

class AdminController extends Controller
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

    public function getQuestionIsPublic(){
        $questionIsPublic = $this->questionRepository->getQuestionIsPublic();
        return $this->OK($questionIsPublic);
    }

    public function getQuestionNonPublic(){
        $questionIsPublic = $this->questionRepository->getQuestionNonPublic();
        return $this->OK($questionIsPublic);
    }

    public function getQuestionIsPublicById($userID){
        $questionIsPublic = $this->questionRepository->getQuestionIsPublicById($userID);
        return $this->OK($questionIsPublic);
    }

    public function getQuestionNonPublicByID($userID){
        $questionIsPublic = $this->questionRepository->getQuestionNonPublicById($userID);
        return $this->OK($questionIsPublic);
    }

    public function getUserByRole($role_id){
        $user = $this->userRepository->getUserByRole($role_id);
        return $this->OK($user);
    }

    public function numberTeacher(){
        $numberTeacher = $this->userRepository->numberTeacher();
        return $this->OK($numberTeacher);
    }

    public function numberStudent(){
        $numberStudent = $this->userRepository->numberStudent();
        return $this->OK($numberStudent);
    }

    public function numberQuizz(){
        $numberStudent = $this->quizRepository->numberQuizz();
        return $this->OK($numberStudent);
    }

    public function numberQuestion(){
        $numberStudent = $this->questionRepository->numberQuestion();
        return $this->OK($numberStudent);
    }

    public function top10QuestionPosted(){
        $top10QuestionPosted = $this->questionRepository->top10QuestionPosted();
        return $this->OK($top10QuestionPosted);
    }

    public function top10Score(){
        $top10Score = $this->historyRepository->top10Score();
        $i = 0;
        foreach ($top10Score as $top10){
            $historyId = $top10['id'];
            $scorearray = $this->quizRepository->getQuizzScore($historyId);
            $score = $scorearray['data']['score'];
            $top10Score[$i]['score']=$score;
            $i++;
        }
        $topScore = array();        
        $getDistinctUserID = $this->historyRepository->getDistinctUserID();
        $getDistinctQuizzID = $this->historyRepository->getDistinctQuizzID();
        foreach ($getDistinctUserID as $UserID){
            foreach ($getDistinctQuizzID as $QuizzID){
                $getFirstRecord = $this->historyRepository->getFirstRecord($UserID['user_id'],$QuizzID['quizz_id']);
                $maxValue = $getFirstRecord;
                $historyId1 = $getFirstRecord['id'];
                $scorearray1 = $this->quizRepository->getQuizzScore($historyId1);
                $score1 = $scorearray1['data']['score'];
                $maxValue['score'] = $score1;
                for ($j = 0; $j < sizeof($top10Score); $j++){
                    if(($top10Score[$j]['quizz_id'] == $QuizzID['quizz_id'])
                        &&($top10Score[$j]['user_id'] == $UserID['user_id'])
                        &&($top10Score[$j]['score'] > $maxValue['score'])){
                            $maxValue = $top10Score[$j];
                    }
                }$topScore[] = $maxValue;
            }
        }
        usort($topScore,function($a,$b){
            return $a['score'] > $b['score'] ? -1 : 1;
        });
        $k = 0;
        foreach($topScore as $value){
            if($k == 5){
                break;
            }
            $result[] = $value;
            $k++;
        }
        return $this->OK($result);     
    }

    public function topScoreByTopic($topic_id){
        $top10Score = $this->historyRepository->top10ScoreByTopicID($topic_id);
        $i = 0;
        foreach ($top10Score as $top10){
            $historyId = $top10['id'];
            $scorearray = $this->quizRepository->getQuizzScore($historyId);
            $score = $scorearray['data']['score'];
            $top10Score[$i]['score']=$score;
            $i++;
        }
        $topScore = array();        
        $getDistinctUserID = $this->historyRepository->getDistinctUserIDByTopicID($topic_id);
        $getDistinctQuizzID = $this->historyRepository->getDistinctQuizzIDByTopicID($topic_id);
        foreach ($getDistinctUserID as $UserID){
            foreach ($getDistinctQuizzID as $QuizzID){                
                $getFirstRecord = $this->historyRepository->getFirstRecordByTopicID($UserID['user_id'],$QuizzID['quizz_id'],$topic_id);
                $maxValue = $getFirstRecord;
                $historyId1 = $getFirstRecord['id'];
                $scorearray1 = $this->quizRepository->getQuizzScore($historyId1);
                $score1 = $scorearray1['data']['score'];
                $maxValue['score'] = $score1;
                for ($j = 0; $j < sizeof($top10Score); $j++){
                    if(($top10Score[$j]['quizz_id'] == $QuizzID['quizz_id'])
                        &&($top10Score[$j]['user_id'] == $UserID['user_id'])
                        &&($top10Score[$j]['score'] > $maxValue['score'])){
                             $maxValue = $top10Score[$j];
                    }
                }$topScore[] = $maxValue;
            }
        }
        usort($topScore,function($a,$b){
            return $a['score'] > $b['score'] ? -1 : 1;
        });
        $k = 0;
        foreach($topScore as $value){
            if($k == 1){
                break;
            }
            $result[] = $value;
            $k++;
        }
        return $result;     
    }

    public function top10ScoreByTopic(){
        $get_topic_id = $this->historyRepository->getDistinctTopicIDByTopicID();
        //$topic_id = $get_topic_id[0]['topic_id'];
        foreach ($get_topic_id as $topic){
            $topic_id = $topic['topic_id'];
            $result[] = $this->topScoreByTopic($topic_id);
        }
        return $this->OK($result);
    }

    public function blockUser($userID)
    {
        $get_is_active = $this->userRepository->getIsActiveUser($userID);
        $is_active = $get_is_active[0]['is_active'];
        if($is_active == 1){
            $block_user = $this->userRepository->updateWith([['id',$userID]],['is_active'=>0]);
            return $this->OK('Block');
        }else if($is_active == 0){
            $un_block_user = $this->userRepository->updateWith([['id',$userID]],['is_active'=>1]);
            return $this->OK('Unblock');            
        }
    }

    public function verify($questionID){
        $get_is_public = $this->questionRepository->getIsPublicQuestion($questionID);
        $is_public = $get_is_public[0]['is_public'];
        if($is_public == 1){
            $unverify = $this->questionRepository->updateWith([['id',$questionID]],['is_public'=>0]);
            return $this->OK('Unverify'); 
        }else if($is_public == 0){
            $verify = $this->questionRepository->updateWith([['id',$questionID]],['is_public'=>1]);
            return $this->OK('Verify'); 
        }       
    }

    public function addTopic(Request $request){
        $json = json_decode($request->getContent(),true);
        $topic_name = $json['topic_name'];
        $data = ['topic_name'=>$topic_name];
        $getAllTopic = Topic::get();
        foreach ($getAllTopic as $Topic){
            if(strcmp($Topic['topic_name'],$topic_name)){
                return $this->BadRequest('Duplicate Topic');
            }else {
                $insertTopic = Topic::insert($data);
                return $this->OK('Add Topic Success');
            }
        }
    }

    public function addLevel(Request $request){
        $json = json_decode($request->getContent(),true);
        $level_name = $json['level_name'];
        $data = ['level_name'=>$level_name];
        $getAllLevel = Level::get();
        foreach ($getAllLevel as $Level){
            if(strcmp($Level['level_name'],$level_name) == 0){
                return $this->BadRequest('Duplicate Level');
            }else {
                $insertLevel = Level::insert($data);
                return $this->OK('Add Level Success');
            }
        }
    }
}
