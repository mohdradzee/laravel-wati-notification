<?php
namespace mohdradzee\WatiNotification\Strategies;

use Illuminate\Support\Facades\Http;
use mohdradzee\WatiNotification\Contracts\WatiApiStrategy;

class AddContactStrategy implements WatiApiStrategy
{
    public function execute(array $data): array
    {
        $token = config('wati.token');
        $base = rtrim(config('wati.base_url'), '/');
        $phone = $data['phone'];

        $url = "{$base}/addContact/{$phone}";

        $payload = [
            'fullName' => $data['meta']['fullName'] ?? 'Unknown',
            'customParams' => $this->formatCustomParams($data['meta']['customParams'] ?? []),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if (!$response->successful()) {
            \Log::error("[WATI] Failed to add contact: " . $response->body());
            throw new \Exception('WATI add_contact request failed');
        }

        return ['response' => $response->json()];
    }

    protected function formatCustomParams(array $params): array
    {
        return collect($params)->map(function ($value, $key) {
            return ['name' => $key, 'value' => $value];
        })->values()->toArray();
    }
}
