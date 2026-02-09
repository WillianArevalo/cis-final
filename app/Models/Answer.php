<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'ask_id',
        'scholarship_id',
        'status',
    ];

    public function ask()
    {
        return $this->belongsTo(Ask::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'answer_id');
    }
}
