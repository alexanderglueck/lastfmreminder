<?php

namespace App\Console\Commands;

use App\Mail\DisconnectReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CheckScrobbles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lastfm:check-scrobbles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $users = $this->getUsers();

        if ($users === null) {
            $this->info('No users.json or empty users.json file provided');
            return 0;
        }

        if (count($users) == 0) {
            $this->info('Empty users.json file provided');
            return 0;
        }

        $apiKey = config('services.lastfm.key');

        $this->info('Checking ' . count($users) . ' users');

        foreach ($users as $user) {
            if ( ! isset($user->email) || ! isset($user->username)) {
                $this->info('User record is missing one of the required properties. Required properties: username, email');
                continue;
            }

            $jsonResponse = $this->getRecentTracks($apiKey, $user);

            if (count($jsonResponse['recenttracks']['track']) == 0) {
                // No tracks played yet
                continue;
            }

            if (isset($jsonResponse['recenttracks']['track'][0]['@attr']['nowplaying'])) {
                // A song is playing right now
                continue;
            }

            $lastPlayedTimestamp = $jsonResponse['recenttracks']['track'][0]['date']['uts'];

            $carbon = Carbon::createFromTimestampUTC($lastPlayedTimestamp);
            if ($carbon->greaterThan(now()->subHours(24))) {
                // A song was played within the last 24 hours
                continue;
            }

            Mail::to($user->email)->send(new DisconnectReminder($user->username));
        }

        $this->info('Done');

        return 0;
    }

    protected function getUsers(): ?array
    {
        return json_decode(Storage::get('users.json'));
    }

    private function getRecentTracks(mixed $apiKey, mixed $user)
    {
        $response = Http::get('https://ws.audioscrobbler.com/2.0?' . http_build_query([
                'format' => 'json',
                'method' => 'user.getRecentTracks',
                'api_key' => $apiKey,
                'limit' => 1,
                'user' => $user->username
            ]));

        return $response->json();
    }
}
