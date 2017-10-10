<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "questions";

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class,'id','topic_id');
    }

    public function quizz_question()
    {
        return $this->belongsTo(QuizzQuesion::class,'question_id','id');
    }

}
