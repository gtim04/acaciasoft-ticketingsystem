<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function ticket(){
    	return $this->belongsToMany(Ticket::class, 'comment_ticket')->using(CommentTicket::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}