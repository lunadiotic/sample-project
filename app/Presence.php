<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(PresenceDetail::class);
    }
}
