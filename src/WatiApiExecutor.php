<?php
namespace mohdradzee\WatiNotification;

use mohdradzee\WatiNotification\Contracts\WatiApiStrategy;
use mohdradzee\WatiNotification\Strategies\{
    SendSingleTemplateMessageStrategy,
    AddContactStrategy
};

class WatiApiExecutor
{
    public static function make(string $type): WatiApiStrategy
    {
        return match ($type) {
            'send_template_message' => new SendSingleTemplateMessageStrategy,
            'add_contact' => new AddContactStrategy,
            default => throw new \Exception("Unsupported WATI API type: $type"),
        };
    }

    public function execute(string $type, array $data): array
    {
        $strategy = self::make($type);
        return $strategy->execute($data);
    }
}
