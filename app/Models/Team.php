<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('user_id', 'status')
            ->withTimestamps();
    }

}
