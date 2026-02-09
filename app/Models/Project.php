<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'community_id',
        'accept',
        'document',
        'sent_by',
        'benefited_population',
        'general_objective',
        'justification',
        'location',
        'map',
        'contextualization',
        'description_activities',
        'projections',
        'challenges',
        'schedule',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function specificObjetives()
    {
        return $this->hasMany(ProjectSpecificObjetive::class, 'project_id');
    }
}
