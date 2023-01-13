<?php

namespace App\Models;

use App\Models\Comment as ModelsComment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $guarded = false;

    protected $with = ['user', 'parent']; // что бы не было дополнительного запроса

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function parent(){
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }
}
