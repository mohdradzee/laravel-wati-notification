<?php
namespace mohdradzee\WatiNotification;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use mohdradzee\WatiNotification\Channels\WatiChannel;

class WatiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/wati.php' => config_path('wati.php'),
        ], 'config');

        $this->app->make(ChannelManager::class)->extend('wati', function () {
            return new WatiChannel;
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/wati.php', 'wati');
    }
}
