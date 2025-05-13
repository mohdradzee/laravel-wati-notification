<?php
namespace mohdradzee\WatiNotification\Strategies;

use Illuminate\Support\Facades\Http;
use mohdradzee\WatiNotification\Contracts\WatiApiStrategy;

class SendSingleTemplateMessageStrategy implements WatiApiStrategy
{
    public function execute(array $data): array
    {
        $token = config('wati.token');
        $base = rtrim(config('wati.base_url'), '/');
        $phone = $data['phone'];

        $url = "{$base}/sendTemplateMessage?whatsappNumber={$phone}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json-patch+json',
        ])->withBody(json_encode([
            'parameters' => $data['parameters'],
            'template_name' => $data['template_name'],
            'broadcast_name' => $data['broadcast_name'],
        ]), 'application/json')->post($url);

        return ['response'=>$response->json()];
    }
}
