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
        $decode = json_decode($top10Score,true);
        usort($decode,function($a,$b){
            return $a['score'] > $b['score'] ? -1 : 1;
        });
        $j = 0;
        foreach($decode as $value){
            if($j == 9){
                break;
            }
            $result[] = $value;
            $j++;
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
}
