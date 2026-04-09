<?php

declare(strict_types=1);

namespace Packages\Notifications\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Packages\Notifications\BaseNotification;
use Packages\Notifications\Support\ChannelRegistry;

final class SendNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new notification job.
     */
    public function __construct(
        public readonly object $notifiable,
        public readonly BaseNotification $notification,
        public readonly string $channel,
    ) {
        $this->queue = (string) config('notifications.queue', 'notifications');
    }

    /**
     * Handle the queued notification.
     */
    public function handle(ChannelRegistry $registry): void
    {
        $registry->get($this->channel)->send($this->notifiable, $this->notification);
    }
}
