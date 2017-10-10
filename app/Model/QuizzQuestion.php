<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class QuizzQuestion extends Model
{
    protected $table = "quizz_questions";

    public function question()
    {
        return $this->hasMany(Question::class);
    }

}
