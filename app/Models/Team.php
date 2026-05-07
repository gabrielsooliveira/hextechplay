<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'acronym',
        'region',
        'logo',
        'description',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function titles()
    {
        return $this->morphMany(Title::class, 'titleable');
    }
}
