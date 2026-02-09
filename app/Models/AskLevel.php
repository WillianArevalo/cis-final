<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AskLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'ask_id',
        'level',
        'type'
    ];

    public function ask()
    {
        return $this->belongsTo(Ask::class);
    }
}