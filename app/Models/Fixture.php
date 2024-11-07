<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'matches_id',
        'match_date'
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'matches_id');
    }
}
