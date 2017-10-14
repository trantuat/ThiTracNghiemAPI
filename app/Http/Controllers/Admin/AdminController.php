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
            // if(i==3){
            //     break;
            // }
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

    public function my_sort($a, $b)
    {
        if ($a['score'] > $b['score']) {
            return -1;
        } else if ($a['score'] < $b['score']) {
            return 1;
        } else {
            return 0; 
        }
    }
}
