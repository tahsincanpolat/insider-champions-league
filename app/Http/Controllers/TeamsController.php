<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Matches;
use App\Models\Fixture;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeamsController extends Controller
{
    public function getTeams()
    {
        $teams = Team::all();
        return response()->json($teams);
    }
}
