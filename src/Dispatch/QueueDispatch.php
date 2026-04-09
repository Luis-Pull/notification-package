<?php

declare(strict_types=1);

namespace Packages\Notifications\Dispatch;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\DispatchStrategy;
use Packages\Notifications\Jobs\SendNotificationJob;

final class QueueDispatch implements DispatchStrategy
{
    /**
     * Dispatch the notification through queued jobs.
     *
     * @param list<string> $channels
     */
    public function dispatch(object $notifiable, BaseNotification $notification, array $channels): void
    {
        $queue = (string) config('notifications.queue', 'notifications');

        foreach ($channels as $channel) {
            SendNotificationJob::dispatch($notifiable, $notification, $channel)->onQueue($queue);
        }
    }
}
