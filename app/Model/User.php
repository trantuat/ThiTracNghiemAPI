<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "users";

    public function roles()
    {
        return $this->belongsTo(Role::class,'role_user_id','id');
    }

    public function info()
    {
        return $this->hasOne(Info::class,'user_id','id');
    }

    // public function question()
    // {
    //     return $this->hasMany(Question::class,'id','user_id');
    // }

    protected $hidden = [
        'password','role_user_id'
    ];
}
