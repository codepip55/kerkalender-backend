<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTeam extends Model
{
    protected $fillable = ['service_id', 'name'];
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
