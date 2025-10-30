<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'user_id',
        'client_type',
        'sex',
        'age',
        'email',
        'SQD1',
        'SQD2',
        'SQD3',
        'SQD4',
        'SQD5',
        'SQD6',
        'SQD7',
        'SQD8',
        'suggestions',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
