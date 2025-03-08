<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setlist;
use Illuminate\Http\Request;

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
        error_log($request);
        // Check if request has required fields
        $request->validate([
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required',
            'notes' => 'required',
            'service_manager_id' => 'required',
            'teams' => 'required'
        ]);
        // Create service
        $service = new Service();
        $service->date = $request->date;
        $service->start_time = $request->start_time;
        $service->end_time = $request->end_time;
        $service->location = $request->location;
        $service->notes = $request->notes;
        $service->service_manager_id = $request->service_manager_id;
        $service->teams()->attach($request->teams);

        // Create new setlist and attach to service
        $setlist = new Setlist();
        $setlist->service_id = $service->id;

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

        $service->save();

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
