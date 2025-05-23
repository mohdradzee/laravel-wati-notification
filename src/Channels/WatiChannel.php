<?php
namespace mohdradzee\WatiNotification\Channels;

use Illuminate\Notifications\Notification;
use mohdradzee\WatiNotification\WatiApiExecutor;
use mohdradzee\WatiNotification\WatiMessage;

class WatiChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWati')) {
            return null;
        }

        $messages = $notification->toWati($notifiable);
        $messages = is_array($messages) ? $messages : [$messages];

        $executor = new WatiApiExecutor;
        $responses = [];

        foreach ($messages as $message) {
            if (!$message instanceof WatiMessage) {
                continue;
            }

            $data = $message->toArray();
            $type = $data['type'] ?? 'send_template_message';
            $callback = $message->getCallback();

            try {
                $response = $executor->execute($type, $data);
                $responses[] = $response;

                if (is_callable($callback)) {
                    $callback($response, null);
                }

            } catch (\Throwable $e) {
                if (is_callable($callback)) {
                    $callback(null, $e);
                }
                throw $e; // Triggers NotificationFailed
            }
        }

        // Return structured response to trigger NotificationSent
        return count($responses) === 1 ? $responses[0] : $responses;
    }
}

?>