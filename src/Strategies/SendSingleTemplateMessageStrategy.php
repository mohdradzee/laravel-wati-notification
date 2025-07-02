<?php

namespace mohdradzee\WatiNotification\Strategies;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use mohdradzee\WatiNotification\Contracts\WatiApiStrategy;
use Exception;
class SendSingleTemplateMessageStrategy implements WatiApiStrategy
{
    public function execute(array $data): array
    {
        $token = config('wati.token');
        $base = rtrim(config('wati.base_url'), '/');
        $phone = $data['phone'];
        $timeout = (int) config('wati.timeout', 10);

        $url = "{$base}/sendTemplateMessage?whatsappNumber={$phone}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json-patch+json',
            ])
                ->timeout($timeout) // recommended to always set timeout
                ->withBody(json_encode([
                    'parameters' => $data['parameters'],
                    'template_name' => $data['template_name'],
                    'broadcast_name' => $data['broadcast_name'],
                ]), 'application/json')
                ->post($url);

                if (!$response->successful()) {
                    $additionalMessage = '';

                    if ($response->status() === 401) {
                        $additionalMessage = ' - Unauthorized, check your WATI token settings.';
                    }

                    $errorMessage = "Wati API returned unsuccessful {$response->status()} response{$additionalMessage}";

                    throw new Exception($errorMessage);
                }
            return ['response' => $response->json()];
        } catch (ConnectionException $e) {
            throw new Exception('Wati API connection failed: ' . $e->getMessage());
        } catch (RequestException $e) {
            throw new Exception('Wati API request exception: ' . $e->getMessage());
        } catch (Exception $e) {
            throw $e;
        }
    }
}