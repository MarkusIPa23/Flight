<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Plane;

class FetchPlanes extends Command
{
    // Command name
    protected $signature = 'planes:fetch';

    // Command description
    protected $description = 'Fetch live planes from OpenSky and store them in the database';

    public function handle()
    {
        $this->info('Fetching planes from OpenSky...');

        $response = Http::get('https://opensky-network.org/api/states/all');

        if (!$response->successful()) {
            $this->error('Failed to fetch data from OpenSky');
            return Command::FAILURE;
        }

        $states = $response['states'];

        foreach ($states as $state) {
            if (!$state[5] || !$state[6]) {
                continue;
            }

            Plane::updateOrCreate(
                ['icao24' => $state[0]],
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

        $this->info('Planes updated successfully ✔');

        return Command::SUCCESS;
    }
}
