<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $baseUrl = env('RICK_AND_MORTY_API_URL') . '/character';

        $response = Http::get($baseUrl);

        if ($response->successful()) {
            $data = $response->json();
            $numPages = $data['info']['pages'];

            for($page = 1; $page <= $numPages; $page++){
                if($page>1){
                    $url = $baseUrl . '?page=' . $page;
                    $response = Http::get($url);
                    $data = $response->json();
                }
                $characters = $data['results'];
                foreach ($characters as $characterData) {
                    Character::updateOrCreate([
                        'id' => $characterData['id'],
                        'name' => $characterData['name'],
                        'status' => $characterData['status'],
                        'species' => $characterData['species'],
                        'type' => $characterData['type'] ?? '',
                        'gender' => $characterData['gender'],
                        'origin_name' => $characterData['origin']['name'],
                        'origin_url' => $characterData['origin']['url'],
                        'location_name' => $characterData['location']['name'],
                        'location_url' => $characterData['location']['url'],
                        'image' => $characterData['image'],
                        'url' => $characterData['url'],
                        'episode' => $characterData['episode'],
                        'created' => $characterData['created'],
                    ]);
                }
            }
        } else {
            Log::error("Failed to fetch characters from external API.");
            throw new \Exception("Failed to fetch characters from external API");
        }
    }
}
