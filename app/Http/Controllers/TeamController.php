<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Get Teams
     * Optional Params: member_id
     */
    public function getTeams(Request $request)
    {
        $query = Team::query();
        if ($request->has('member_id')) {
            $query->whereHas('members', function ($q) use ($request) {
                $q->where('user_id', $request->member_id);
            });
        }
        $teams = $query->get();
        return response()->json($teams);
    }
    /**
     * Get Team by id
     * Required: id
     */
    public function getTeamById($id) {
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }

        $team = Team::find($id);
        return response()->json($team);
    }
    /**
     * Create Team
     * Required: name
     */
    public function createTeam(Request $request)
    {
        if (!$request->name) {
            return response()->json(['error' => 'name is required']);
        }

        $team = Team::create($request->all());
        return response()->json($team);
    }
    /**
     * Update Team by id
     * Required: id, name, members
     */
    public function updateTeamById(Request $request, $id)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }

        $team = Team::find($id);
        $team->update($request->all());
        return response()->json($team);
    }
    /**
     * Delete Team by id
     * Required: id
     */
    public function deleteTeamById($id)
    {
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }
        $team = Team::find($id);
        $team->delete();

        return response()->json($team);
    }
}
