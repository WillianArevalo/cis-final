<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAssist extends Model
{
    use HasFactory;

    protected $table = 'report_assits';

    protected $fillable = [
        'report_id',
        'scholarship_id',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
