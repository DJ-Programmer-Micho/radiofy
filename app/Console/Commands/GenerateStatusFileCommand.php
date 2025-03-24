<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RadioConfiguration;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GenerateStatusFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:statusfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Icecast status and update the custom JSON status file and DB statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Fetch Icecast status JSON.
        try {
            $client = new Client();
            $response = $client->get(app('server_ip').':'.app('server_post'), [
                'timeout' => 10,
            ]);
            $icecastStatus = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            $this->error('Failed to fetch Icecast status: ' . $e->getMessage());
            Log::error('Failed to fetch Icecast status: ' . $e->getMessage());
            return;
        }

        // Get the list of dynamic sources.
        $sources = $icecastStatus['icestats']['source'] ?? [];

        // 2. Get all radio configurations from the database.
        $radios = RadioConfiguration::with('radio_configuration_profile', 'plan')->get();

        $mergedData = [];

        // 3. Loop over each radio configuration.
        foreach ($radios as $radio) {
            // Generate mount name from radio name, e.g. "Radio One" becomes "/radio_one"
            $mount = '/' . $radio->radio_name_slug;

            // Try to find a matching dynamic source.
            $sourceData = collect($sources)->first(function ($s) use ($mount) {
                return isset($s['listenurl']) && strpos($s['listenurl'], $mount) !== false;
            });

            // Update highest_peak_listeners if needed.
            if ($sourceData) {
                $currentPeak = (int)$sourceData['listener_peak'] ?? 0;
                if ($radio->radio_configuration_profile) {
                    $storedPeak = (int)$radio->radio_configuration_profile->highest_peak_listeners;
                    if ($currentPeak > $storedPeak) {
                        $radio->radio_configuration_profile->highest_peak_listeners = $currentPeak;
                        $radio->radio_configuration_profile->save();
                    }
                }
            }

            // Build a flattened entry.
            if ($sourceData) {
                $flattened = [
                    'mount' => $mount,
                    'radio-id' => $radio->id,
                    'bitrate' => (string)$sourceData['bitrate'] . 'Kbps',
                    'listener_peak' => (int)$sourceData['listener_peak'],
                    'listeners' => (int)$sourceData['listeners'],
                    'listenurl' => env('APP_URL') . $mount,
                ];
            } else {
                $flattened = [
                    'mount' => $mount,
                    'radio-id' => $radio->id,
                    'bitrate' => null,
                    'listener_peak' => null,
                    'listeners' => null,
                    'listenurl' => "app('server_ip').':'.app('server_post')" . $mount,
                ];
            }

            $mergedData[] = $flattened;
        }

        // 4. Prepare the output JSON.
        $output = [
            'sources' => $mergedData,
        ];

        $outputJson = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // 5. Write the output to a file (for example, in the public folder).
        $filePath = public_path('api/v1/stats/mradiofy-status-json.json');
        try {
            file_put_contents($filePath, $outputJson);
            $this->info('Status file generated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to write status file: ' . $e->getMessage());
            $this->error('Failed to write status file: ' . $e->getMessage());
        }
    }
}
