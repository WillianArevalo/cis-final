<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSpecificObjetive extends Model
{
    use HasFactory;

    protected $table = 'project_specific_objectives';

    protected $fillable = [
        'project_id',
        'specific_objective',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
