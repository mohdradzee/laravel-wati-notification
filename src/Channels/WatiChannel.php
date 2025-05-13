<?php
namespace Mohdradzee\WatiNotification\Channels;

use Illuminate\Notifications\Notification;
use mohdradzee\WatiNotification\WatiApiExecutor;

class WatiChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWati')) {
            return;
        }

        $message = $notification->toWati($notifiable);
        $data = $message->toArray();
        $type = $data['type'] ?? 'send_template';

        try {
            $executor = new WatiApiExecutor;
            return $executor->execute($type, $data);
        } catch (\Exception $e) {
            \Log::error("[WATI] Strategy failure: " . $e->getMessage());
            return [];
        }
    }
}
