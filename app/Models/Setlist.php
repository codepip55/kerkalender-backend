<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function songs()
    {
        return $this->hasMany(SetlistItem::class);
    }
}
