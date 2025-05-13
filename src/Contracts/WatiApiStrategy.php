<?php
namespace mohdradzee\WatiNotification\Contracts;

interface WatiApiStrategy
{
    public function execute(array $data): array;
}
?>