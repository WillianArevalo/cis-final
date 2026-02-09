<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes_answer';

    protected $fillable = [
        'content',
        'answer_id',
        'user_id',
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}