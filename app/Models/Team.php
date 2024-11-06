<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function homeMatches()
    {
        return $this->hasMany(Matches::class, 'home_id');
    }

    public function awayMatches()
    {
        return $this->hasMany(Matches::class, 'away_id');
    }
}
