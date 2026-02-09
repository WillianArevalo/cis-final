<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo',
        'institution',
        'academic_level',
        'career',
        'study_level',
        'community_id',
        'project_id',
        'user_id',
        'type',
        'phone'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, "project_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class, "community_id");
    }

    public function asks()
    {
        return $this->hasManyThrough(
            Ask::class,
            AskLevel::class,
            "level",
            "id",
            "study_level",
            "ask_id"
        )->where("ask_levels.type", $this->type);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
