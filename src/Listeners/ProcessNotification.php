<?php

declare(strict_types=1);

namespace Packages\Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Packages\Notifications\Events\NotificationRequested;
use Packages\Notifications\Support\ChannelRegistry;

final class ProcessNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Queue name used when this listener is queued.
     */
    public string $queue;

    /**
     * Create a new queued notification listener.
     */
    public function __construct(
        private readonly ChannelRegistry $registry,
    ) {
        $this->queue = (string) config('notifications.queue', 'notifications');
    }

    /**
     * Process the notification event in the background.
     */
    public function handle(NotificationRequested $event): void
    {
        foreach ($event->channels as $channel) {
            $this->registry->get($channel)->send($event->notifiable, $event->notification);
        }
    }
}
