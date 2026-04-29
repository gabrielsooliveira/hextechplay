<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordLolAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'guest_id',
        'daily_word_id',
        'state',
        'finished'
    ];

    protected $casts = [
        'state' => 'array',
        'finished' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyWord()
    {
        return $this->belongsTo(DailyWord::class);
    }
}
