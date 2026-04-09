<?php

declare(strict_types=1);

namespace Packages\Notifications;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Packages\Notifications\Events\NotificationRequested;
use Packages\Notifications\Listeners\ProcessNotification;
use Packages\Notifications\Support\ChannelRegistry;
use Packages\Notifications\Support\TemplateResolver;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/notifications.php', 'notifications');

        $this->app->singleton(ChannelRegistry::class, function ($app): ChannelRegistry {
            $registry = new ChannelRegistry;

            foreach ((array) config('notifications.channels', []) as $name => $channelClass) {
                $registry->register((string) $name, $app->make($channelClass));
            }

            return $registry;
        });

        $this->app->singleton(TemplateResolver::class, TemplateResolver::class);

        $this->app->singleton(NotificationManager::class, function ($app): NotificationManager {
            return new NotificationManager($app->make(ChannelRegistry::class));
        });
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'notifications');
        $this->registerPublishables();
        $this->registerEventMap();
    }

    /**
     * Register files that may be published into the host application.
     */
    public function registerPublishables(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/notifications.php' => config_path('notifications.php'),
        ], 'notifications-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/notifications'),
        ], 'notifications-views');
    }

    /**
     * Register the event listener map used by the event dispatch strategy.
     */
    public function registerEventMap(): void
    {
        Event::listen(NotificationRequested::class, ProcessNotification::class);
    }
}
