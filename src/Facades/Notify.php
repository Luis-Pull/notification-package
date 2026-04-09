<?php

declare(strict_types=1);

namespace Packages\Notifications\Facades;

use Illuminate\Support\Facades\Facade;
use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\NotificationManager;

/**
 * @method static void send(object $notifiable, ?BaseNotification $notification = null, array $data = [])
 * @method static void extend(string $name, NotificationChannel $channel)
 *
 * @see NotificationManager
 */
class Notify extends Facade
{
    /**
     * Get the service container binding key.
     */
    protected static function getFacadeAccessor(): string
    {
        return NotificationManager::class;
    }
}
