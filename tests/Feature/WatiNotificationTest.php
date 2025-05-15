<?php

namespace mohdradzee\WatiNotification\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as BaseNotification;
use mohdradzee\WatiNotification\WatiMessage;
use mohdradzee\WatiNotification\Tests\TestCase;

class WatiNotificationTest extends TestCase
{
    public function test_notification_is_sent_via_wati_channel()
    {
        Http::fake();

        $user = new class {
            public $phone = '60123456789';
            public $name = 'John Doe';

            public function routeNotificationForWati()
            {
                return $this->phone;
            }
        };

        $notification = new class extends BaseNotification {
            public function via($notifiable)
            {
                return ['wati'];
            }

            public function toWati($notifiable)
            {
                       return WatiMessage::create()
            ->to('023423423423')
            ->withAction('send_template_message')
            ->withParameters([
                ['name' => 'name', 'value' => 'asdfasdf'],
                ['name' => 'whatsappConfirmCode', 'value' =>  '34534543gdfgdf'],
            ])
            ->usingTemplate('confirm_whatsappnumber')//@todo use approved WATI welcome message template
            ->withBroadcastName('newUserWelcomeMessage');
            }
        };

        Notification::send($user, $notification);

        Http::assertSent(function ($request) {
    
            return 1;
        });
    }
}
