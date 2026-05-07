<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'nickname',
        'name',
        'photo',
        'role',
        'team_id',
        'nationality',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function titles()
    {
        return $this->morphMany(Title::class, 'titleable');
    }
}
