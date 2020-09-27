<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    function rosters() {
        return $this->belongsToMany(Roster::class);
    }

    function activities() {
        $rosters = $this->rosters;
        $activities = Activity::where(function ($query) use ($rosters) {;
            foreach ($rosters as $roster) {
                $query->orWhere('roster_id', $roster->id);
            }
        });
        return $activities;
    }

    function user() {
        return $this->belongsTo(User::class);
    }
}
