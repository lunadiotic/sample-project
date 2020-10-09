<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresenceDetail extends Model
{
    protected $guarded = [];

    public function mainData()
    {
        return $this->belongsTo(Presence::class);
    }
}
