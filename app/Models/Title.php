<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $fillable = [
        'name',
        'year',
        'icon',
        'description',
        'titleable_id',
        'titleable_type',
    ];

    public function titleable()
    {
        return $this->morphTo();
    }
}
