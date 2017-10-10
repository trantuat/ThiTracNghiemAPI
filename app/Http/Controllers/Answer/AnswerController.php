<?php

namespace App\Http\Controllers\Answer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Answer\AnswerStudentRepositoryInterface;

class AnswerController extends Controller
{
    protected $answerStudentRepository;

    public function __construct(AnswerStudentRepositoryInterface $answerStudentRepository)
    {
        $this->answerStudentRepository = $answerStudentRepository;
    }
    
    public function userAnswerQuestion(){
        $json = json_decode($request->getContent(),true);
        $history_id = $json['history_id'];
        $question_id = $json['question_id'];
        $option_choose = $json['option_choose'];
        $answerStudent = $this->$answerStudentRepository->insertGetID([
            'hitory_id'=>$history_id,
            'question_id'=>$question_id,
            'option_choose'=>$option_choose
        ]);
        return $this->OḲ('OK');
    }
}
