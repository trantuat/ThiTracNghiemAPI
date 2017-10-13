<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Quiz\QuizzRepositoryInterface;

class AdminController extends Controller
{
    protected $questionRepository;
    protected $userRepository;
    protected $quizRepository;    
    
    public function __construct(QuestionRepositoryInterface $questionRepository,UserRepositoryInterface $userRepository, QuizzRepositoryInterface $quizRepository )
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository; 
        $this->quizRepository = $quizRepository;        
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
}
