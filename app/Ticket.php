<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comment(){
    	return $this->hasMany(Comment::class);
    }

    public function log(){
    	return $this->hasMany(Log::class);
    }
}
