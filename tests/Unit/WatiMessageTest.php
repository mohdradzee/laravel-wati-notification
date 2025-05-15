<?php

namespace mohdradzee\WatiNotification\Tests\Unit;

use mohdradzee\WatiNotification\WatiMessage;
use mohdradzee\WatiNotification\Tests\TestCase;

class WatiMessageTest extends TestCase
{
    public function test_message_can_be_created_with_template_and_parameters()
    {
        $message = WatiMessage::create()
            ->to('60123456789')
            ->withAction('add_contact')
            ->withBroadcastName('test')
            ->usingTemplate('welcome_message')
            ->withParameters(['name' => 'John']);
        $messageArr = $message->toArray();
        $this->assertEquals('60123456789', $messageArr['phone']);
        $this->assertEquals('welcome_message', $messageArr['template_name']);
        $this->assertEquals(['name' => 'John'], $message->parameters);
    }
}
