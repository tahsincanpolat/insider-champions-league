<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_id',
        'away_id',
        'home_team_goal',
        'away_team_goal',
        'week'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_id');
    }

    public function fixture()
    {
        return $this->hasOne(Fixture::class, 'matches_id');
    }
}
