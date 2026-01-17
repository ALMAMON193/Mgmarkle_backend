<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ZoomService
{
    private $accountId;

    private $clientId;

    private $clientSecret;

    private $baseUrl;

    public function __construct()
    {
        $this->accountId = trim(config('services.zoom.account_id'));
        $this->clientId = trim(config('services.zoom.client_id'));
        $this->clientSecret = trim(config('services.zoom.client_secret'));
        $this->baseUrl = config('services.zoom.base_url', 'https://api.zoom.us/v2');
    }

    private function getAccessToken()
    {
        return Cache::remember('zoom_access_token', 3500, function () {
            $base64 = base64_encode($this->clientId.':'.$this->clientSecret);
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic '.$base64,
                ])
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->failed()) {
                throw new \Exception('Zoom Auth failed: '.$response->body());
            }

            return $response->json()['access_token'];
        });
    }

    public function createMeeting($data)
    {
        $response = Http::withToken($this->getAccessToken())
            ->post("{$this->baseUrl}/users/me/meetings", $data);

        return $response->json();
    }

    public function listMeetings()
    {
        $response = Http::withToken($this->getAccessToken())
            ->get("{$this->baseUrl}/users/me/meetings");

        return $response->json();
    }

    public function deleteMeeting($meetingId)
    {
        $response = Http::withToken($this->getAccessToken())
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");

        return $response->status() === 204;
    }
}
