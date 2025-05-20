<?php
namespace Mohdradzee\WatiNotification\Channels;

use Illuminate\Notifications\Notification;
use mohdradzee\WatiNotification\WatiApiExecutor;
use mohdradzee\WatiNotification\WatiMessage;

class WatiChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWati')) {
            return;
        }

        $messages = $notification->toWati($notifiable);

        $messages = is_array($messages) ? $messages : [$messages];

        $executor = new WatiApiExecutor;

        $responses = [];

        foreach ($messages as $message) {
            if (!$message instanceof WatiMessage) {
                continue; // Skip invalid entries
            }

            $data = $message->toArray();
            $type = $data['type'] ?? 'send_template';

            try {
                $responses[] = $executor->execute($type, $data);
            } catch (\Exception $e) {
                \Log::error("[WATI] Strategy failure for phone {$data['phone']}: " . $e->getMessage());
            }
        }

        return $responses;
    }
}
