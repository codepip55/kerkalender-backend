<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ['service_team_id', 'name'];
    public function members()
    {
        return $this->hasMany(PositionMember::class);
    }
}
