<?php
namespace App\Http\Controllers;

use App\Models\Plane;
use Illuminate\Support\Facades\Http;

class PlaneController extends Controller
{
    // Fetch from OpenSky and store in MySQL
    public function fetch()
    {
        $response = Http::get('https://opensky-network.org/api/states/all');

        if (!$response->successful()) {
            return response()->json(['error' => 'API failed'], 500);
        }

        foreach ($response['states'] as $state) {
            if (!$state[5] || !$state[6]) continue; // skip if no coordinates

            Plane::updateOrCreate(
                ['icao24' => $state[0]], // unique key
                [
                    'callsign'       => trim($state[1]),
                    'origin_country' => $state[2],
                    'longitude'      => $state[5],
                    'latitude'       => $state[6],
                    'baro_altitude'  => $state[7],
                    'velocity'       => $state[9],
                    'heading'        => $state[10],
                    'on_ground'      => $state[8],
                ]
            );
        }

        return response()->json(['status' => 'Planes updated']);
    }

    // Return planes from database
    public function index()
    {
        return Plane::whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get();
    }
}
