<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\PositionMember;
use App\Models\Service;
use App\Models\ServiceTeam;
use App\Models\Setlist;
use Illuminate\Http\Request;
use function Laravel\Prompts\error;
use function Pest\Laravel\json;

class ServiceController extends Controller
{
    /**
     * Get Services
     * Filter by planned member, service manager, or date
     * Query has: planned_member as user_id, service_manager as service_manager_id, date as date
     * Order by date
     */
    public function findServices(Request $request)
    {
        $query = Service::query();
        if ($request->has('planned_member')) {
            $query->whereHas('plannedMembers', function ($q) use ($request) {
                $q->where('user_id', $request->planned_member);
            });
        }
        if ($request->has('service_manager')) {
            $query->where('service_manager_id', $request->service_manager);
        }
        if ($request->has('date')) {
            $query->where('date', $request->date);
        }
        return $query->get();
    }
    /**
     * Get service by ID
     */
    public function findServiceById(Request $request) {
        $service = $this->getService($request->service_id);
        // Return service with teams and positions
        $service->teams = $service->teams()->with('positions')->get();
        return $service;
    }
    /**
     * Get service
     * Filter by service_id
     */
    public function getService($service_id) {
        return Service::find($service_id);
    }

    /**
     * Create service
     * Request requires: date, start_time, end_time, location, notes, service_manager_id, teams
     * Response: service
     */
    public function createService(Request $request) {
        // Check if request has required fields
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'notes' => 'required',
            'service_manager_id' => 'required',
            'teams' => 'nullable'
        ]);
        // Create service
        $service = new Service();
        $service->title = $request->title;
        $service->date = $request->date;
        $service->start_time = $request->start_time;
        $service->end_time = $request->end_time;
        $service->location = $request->location;
        $service->notes = $request->notes;
        $service->service_manager_id = $request->service_manager_id;
        $service->save();

        // For each team, attach to service
        foreach ($request->teams as $team) {
            $service_team = new ServiceTeam();
            $service_team->service_id = $service->id;
            $service_team->name = $team['name'];
            $service_team->save();

            // For each position, attach to team
            foreach ($team['positions'] as $position) {
                $service_team_position = new Position();
                $service_team_position->service_team_id = $service_team->id;
                $service_team_position->name = $position['name'];
                $service_team_position->save();

                // Add to service_team
                $service_team->positions()->save($service_team_position);

                // For each member, attach to position
//                foreach ($position['members'] as $member) {
//                    $service_team_position_member = new PositionMember();
//                    $service_team_position_member->position_id = $service_team_position->id;
//                    $service_team_position_member->user_id = $member['user_id'];
//                    $service_team_position_member->save();
//
//                    // Add to service_team_position
//                    $service_team_position->members()->save($service_team_position_member);
//                }
            }

            // Add to service
            $service->teams()->save($service_team);
        }
        $service->save();

        // Create new setlist and attach to service
        $setlist = new Setlist();
        $setlist->service_id = $service->id;
        $setlist->save();

        $service->setlist_id = $setlist->id;
        $service->save();

        return $service;
    }
    /**
     * PUT service
     * Request requires: date, start_time, end_time, location, notes, service_manager_id, setlist_id, teams
     * Response: service
     */
    public function updateService(Request $request) {
        // Check if request has required fields
        $request->validate([
            'title' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'notes' => 'required',
            'service_manager_id' => 'required',
            'setlist_id' => 'required',
            'teams' => 'required'
        ]);
        // Update service
        $service = Service::find($request->id);
        $service->date = $request->date;
        $service->start_time = $request->start_time;
        $service->end_time = $request->end_time;
        $service->location = $request->location;
        $service->notes = $request->notes;
        $service->service_manager_id = $request->service_manager_id;
        $service->setlist_id = $request->setlist_id;
        $service->teams()->sync($request->teams);

        // For each team, attach to service
        foreach ($request->teams as $team) {
            $service->teams()->create($team);
        }

        return $service;
    }

    /**
     * Delete service
     * Request requires: service_id
     * Response: service
     */
    public function deleteService($service_id) {
        $service = Service::find($service_id);
        $service->delete();
        return $service;
    }
}
