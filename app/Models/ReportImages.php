<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportImages extends Model
{
    use HasFactory;

    protected $table = 'report_images';

    protected $fillable = [
        'report_id',
        'path',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}