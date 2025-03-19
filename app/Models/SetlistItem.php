<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'setlist_id',
        'song_id',
        'key',
        'vocal_notes',
        'band_notes'
    ];

    public function setlist()
    {
        return $this->belongsTo(Setlist::class);
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
