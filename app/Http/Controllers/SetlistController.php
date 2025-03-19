<?php

namespace App\Http\Controllers;

use App\Models\Setlist;
use App\Models\SetlistItem;
use App\Models\Song;
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
        $setlist->songs()->with('song')->get();
        return response()->json($setlist);
    }
    /**
     * Get setlist by service id
     */
    public function findSetlistByServiceId($service_id)
    {
        // Check required fields
        if (!$service_id) {
            return response()->json(['error' => 'service_id is required']);
        }

        $setlist = Setlist::with('songs.song')->where('service_id', $service_id)->get();
        // If setlist is empty array, return error
        if (count($setlist) === 0) {
            return response()->json(['error' => 'Setlist not found'], 404);
        }


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
    public function updateSetlistById(Request $request) {
        $request->validate([
            'songs' => 'nullable|array',
        ]);
        // Check required fields
        if (!$request->setlist_id) {
            return response()->json(['error' => 'id is required']);
        }

        $setlist = Setlist::find($request->setlist_id);
        if (!$setlist) {
            return response()->json(['error' => 'Setlist not found'], 404);
        }

        // Reset songs
        $setlist->songs()->delete();
        // Loop over songs
        foreach ($request->songs as $song) {
            $setlist_item = new SetlistItem();
            $setlist_item->setlist_id = $setlist->id;
            $setlist_item->key = $song['key'];
            $setlist_item->vocal_notes = $song['vocal_notes'];
            $setlist_item->band_notes = $song['band_notes'];

            // Attach song doc to setlist item
            $song_doc = null;
//                Song::where('title', $song['title'])
//                    ->where('artist', $song['artist'])->first();
            if (!$song_doc) {
                $new_song_doc = new Song();
                $new_song_doc->title = $song['title'];
                $new_song_doc->artist = $song['artist'];
                $new_song_doc->spotify_link = $song['spotify_link'];
                $new_song_doc->save();

                $setlist_item->song_id = $new_song_doc->id;
            } else {
                $setlist_item->song_id = $song_doc->id;
            }
            $setlist_item->save();

            $setlist->songs()->save($setlist_item);
        }

        $setlist->songs()->with('song')->get();
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
