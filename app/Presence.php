<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(PresenceDetail::class);
    }

    public function scopeCountPresence($query, $status)
    {
        return $query->whereDate('created_at', Carbon::today())
            ->where('status', $status)->count();
    }
}
