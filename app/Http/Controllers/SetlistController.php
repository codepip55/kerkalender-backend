<?php

namespace App\Http\Controllers;

use App\Models\Setlist;
use Illuminate\Http\Request;

class SetlistController extends Controller
{
    /**
     * Get setlist by id
     * Required: id
     * Resturns setlist
     */
    public function getSetlistById($id) {
        // Check required fields
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }

        $setlist = Setlist::find($id);
        return response()->json($setlist);
    }
    /**
     * Create Setlist
     * Required: service_id, songs
     */
    public function createSetlist(Request $request)
    {
        // Check required fields
        if (!$request->service_id) {
            return response()->json(['error' => 'service_id is required']);
        }
        if (!$request->songs) {
            return response()->json(['error' => 'songs is required']);
        }

        $setlist = Setlist::create($request->all());
        return response()->json($setlist);
    }
    /**
     * Update setlist by id
     */
    public function updateSetlistById(Request $request, $id) {
        // Check required fields
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }

        $setlist = Setlist::find($id);
        $setlist->update($request->all());
        return response()->json($setlist);
    }
    /**
     * Delete setlist by id
     */
    public function deleteSetlistById($id) {
        // Check required fields
        if (!$id) {
            return response()->json(['error' => 'id is required']);
        }
        $setlist = Setlist::find($id);
        $setlist->delete();

        return response()->json($setlist);
    }
}
