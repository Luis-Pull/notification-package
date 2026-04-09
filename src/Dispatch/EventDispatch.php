<?php

declare(strict_types=1);

namespace Packages\Notifications\Dispatch;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\DispatchStrategy;
use Packages\Notifications\Events\NotificationRequested;

final class EventDispatch implements DispatchStrategy
{
    /**
     * Dispatch the notification by firing a background event.
     *
     * @param  list<string>  $channels
     */
    public function dispatch(object $notifiable, BaseNotification $notification, array $channels): void
    {
        event(new NotificationRequested($notifiable, $notification, $channels));
    }
}
