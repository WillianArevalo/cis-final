<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $casts = [
        'date' => 'datetime',
        'is_editable' => 'boolean',
    ];

    protected $fillable = [
        'project_id',
        'month',
        'theme',
        'number_participants',
        'description',
        'obstacles',
        'sent_by',
        'date',
        'is_editable'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function images()
    {
        return $this->hasMany(ReportImages::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function assists()
    {
        return $this->hasMany(ReportAssist::class, 'report_id');
    }
}
