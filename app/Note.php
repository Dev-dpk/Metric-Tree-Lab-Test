<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $fillable=['user_id','user_profile_id','note','file'];

    public function user(){

        return $this->belongsTo('App\User');
    }
}
