<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function writer() {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
