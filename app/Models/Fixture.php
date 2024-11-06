<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    use HasFactory;

    protected $fillable = [
        'matches_id',
        'match_date',
        'is_played'
    ];

    public function match()
    {
        return $this->belongsTo(Matches::class, 'matches_id');
    }
}
