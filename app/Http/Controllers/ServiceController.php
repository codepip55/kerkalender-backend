<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Models\Position;
use App\Models\PositionMember;
use App\Models\Service;
use App\Models\ServiceTeam;
use App\Models\Setlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        // Return service with teams and positions with members
        $service->load('teams.positions.members');
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

                // For each member, attach to position
                foreach ($position['members'] as $member) {
                    $service_team_position_member = new PositionMember();
                    $service_team_position_member->position_id = $service_team_position->id;
                    $service_team_position_member->user_id = $member['user_id'];
                    $service_team_position_member->status = $member['status'];
                    $service_team_position_member->save();

                    // Add to service_team_position
                    $service_team_position->members()->save($service_team_position_member);
                }

                // Add to service_team
                $service_team->positions()->save($service_team_position);
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

        $service->load('teams.positions.members');

        return $service;
    }
    /**
     * PUT service
     * Request requires: date, start_time, end_time, location, notes, service_manager_id, setlist_id, teams
     * Response: service
     */
    public function updateService(Request $request) {
        // Validate input
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

        // Find service
        $service = Service::find($request->service_id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        // Update service
        $service->update([
            'title' => $request->title,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
            'service_manager_id' => $request->service_manager_id,
        ]);

        // Load current teams, positions, and members
        $service->load('teams.positions.members');
        $current_members = [];

        foreach ($service->teams as $team) {
            foreach ($team->positions as $position) {
                foreach ($position->members as $member) {
                    $current_members[$member->user_id] = [
                        'user_id' => $member->user_id,
                        'team_id' => $team->id,
                        'position_id' => $position->id,
                        'status' => $member->status
                    ];
                }
            }
        }

        Log::info('current members '.json_encode($current_members));

        // Track new members after update
        $new_members = [];

        foreach ($request->teams as $team) {
            Log::info('team '.json_encode($team));
            // Check if the team already exists in the service
            $service_team = ServiceTeam::where('service_id', $service->id)
                ->where('name', $team['name'])
                ->first();

            // If not found, create a new team
            if (!$service_team) {
                $service_team = new ServiceTeam();
                $service_team->service_id = $service->id;
                $service_team->name = $team['name'];
                $service_team->save();
            }

            foreach ($team['positions'] as $position) {
                Log::info('position '.json_encode($position));
                // Check if the position already exists within this service team
                $service_team_position = Position::where('service_team_id', $service_team->id)
                    ->where('name', $position['name'])
                    ->first();

                // If not found, create a new position
                if (!$service_team_position) {
                    $service_team_position = new Position();
                    $service_team_position->service_team_id = $service_team->id;
                    $service_team_position->name = $position['name'];
                    $service_team_position->save();
                }

                foreach ($position['members'] as $member) {
                    Log::info('position member '.json_encode($member));
                    // Check if the position member already exists
                    $service_team_position_member = PositionMember::where('position_id', $service_team_position->id)
                        ->where('user_id', $member['user_id'])
                        ->first();

                    if (!$service_team_position_member) {
                        // If not found, create a new position member
                        $service_team_position_member = new PositionMember();
                        $service_team_position_member->position_id = $service_team_position->id;
                        $service_team_position_member->user_id = $member['user_id'];
                        $service_team_position_member->status = $member['status'];
                        $service_team_position_member->save();
                    } else {
                        // If the member already exists, you might want to update the status (if necessary)
                        $service_team_position_member->status = $member['status'];
                        $service_team_position_member->save();
                    }

                    // Attach the position member to the position
                    $service_team_position->members()->save($service_team_position_member);

                    // Store new member data
                    $new_members[$member['user_id']] = [
                        'user_id' => $member['user_id'],
                        'team_id' => $service_team->id,
                        'position_id' => $service_team_position->id,
                        'status' => $member['status']
                    ];
                }

                // Save the updated position
                $service_team->positions()->save($service_team_position);
            }

            // Save the updated team
            $service->teams()->save($service_team);
        }

        // Remove any teams, positions, or members that are not in request
        foreach ($service->teams as $team) {
            $found = false;
            foreach ($request->teams as $new_team) {
                if ($team->name == $new_team['name']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Remove team
                $team->delete();
            } else {
                foreach ($team->positions as $position) {
                    $found = false;
                    foreach ($new_team['positions'] as $new_position) {
                        if ($position->name == $new_position['name']) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        // Remove position
                        $position->delete();
                    } else {
                        foreach ($position->members as $member) {
                            $found = false;
                            foreach ($new_position['members'] as $new_member) {
                                if ($member->user_id == $new_member['user_id']) {
                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found) {
                                // Remove member
                                $member->delete();
                            }
                        }
                    }
                }
            }
        }

        $service->save();

        // Compare old vs. new members to detect changes
        $changed_members = [];

        foreach ($new_members as $member) {
            Log::info('member '.json_encode($member));
//            Log::info('member 2 '.json_encode($current_members[$member['user_id']]));
            // Check if member is in current members with same team and position
            if (isset($current_members[$member['user_id']])) {
                Log::info('current member '.json_encode($current_members[$member['user_id']]));
                Log::info('new member '.json_encode($member));
                if ($current_members[$member['user_id']] != $member) {
                    $changed_members[$member['user_id']] = $member;
                }
            } else {
                $changed_members[$member['user_id']] = $member;
            }
        }

        Log::info('changed members '.json_encode($changed_members));
        // Send notification email for affected users
        foreach ($changed_members as $user_id => $member_data) {
            $user = User::find($user_id);
            $team = ServiceTeam::find($member_data['team_id']);
            $position = Position::find($member_data['position_id']);
            $confirmationLink = url('/dashboard');

            if ($user && $team && $position) {
                Mail::to($user->email)->send(new NotificationMail(
                    $user,
                    $service,
                    $team,
                    $position,
                    $confirmationLink
                ));
            }
        }

        $service->load('teams.positions.members');
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


    /**
     * Get all user's requests
     */
    public function findUserRequests(Request $request) {
        // Get user
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Get all services
        $services = Service::all();
        $user_requests = [];

        // Loop through services to find user's requests
        foreach ($services as $service) {
            foreach ($service->teams as $team) {
                foreach ($team->positions as $position) {
                    foreach ($position->members as $member) {
                        if ($member->user_id == $user->id) {
                            $user_requests[] = [
                                'service' => $service,
                                'team' => $team,
                                'position' => $position,
                                'status' => $member->status
                            ];
                        }
                    }
                }
            }
        }

        return response()->json(['data' => $user_requests]);
    }
    /**
     * Update request status
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'v_request' => 'required',
            'status' => 'required',
            'user_id' => 'required'
        ]);

        $service_request = $request->v_request['service']['id'];
        $service = Service::find($service_request);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        // Loop over teams, positions, and members to find the request
        foreach ($service->teams as $team) {
            foreach ($team->positions as $position) {
                foreach ($position->members as $member) {
                    if ($member->user_id == $request->user_id) {
                        $member->status = $request->status;
                        $member->save();
                        return response()->json(['message' => 'Request updated']);
                    }
                }
            }
        }
    }
}
