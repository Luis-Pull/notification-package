<?php

declare(strict_types=1);

namespace Packages\Notifications\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Packages\Notifications\BaseNotification;

final class NotificationRequested
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @param  list<string>  $channels
     */
    public function __construct(
        public readonly object $notifiable,
        public readonly BaseNotification $notification,
        public readonly array $channels,
    ) {}
}
