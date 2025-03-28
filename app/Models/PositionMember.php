<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionMember extends Model
{
    protected $fillable = ['position_id', 'user_id', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
