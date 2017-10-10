<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Question\QuestionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class AdminController extends Controller
{
    protected $questionRepository;
    protected $userRepository;
    
    public function __construct(QuestionRepositoryInterface $questionRepository,UserRepositoryInterface $userRepository )
    {
        $this->questionRepository = $questionRepository;
        $this->userRepository = $userRepository;        
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
}
