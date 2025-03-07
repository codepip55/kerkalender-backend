<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'location',
        'notes',
        'service_manager_id',
        'setlist_id'
    ];
    public function serviceManager()
    {
        return $this->belongsTo(User::class, 'service_manager_id');
    }
    public function setlist()
    {
        return $this->hasOne(Setlist::class, 'setlist_id');
    }
    public function teams()
    {
        return $this->hasMany(ServiceTeam::class);
    }

}
