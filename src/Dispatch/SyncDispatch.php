<?php

declare(strict_types=1);

namespace Packages\Notifications\Dispatch;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\DispatchStrategy;
use Packages\Notifications\Support\ChannelRegistry;

final class SyncDispatch implements DispatchStrategy
{
    /**
     * Create a new synchronous dispatch strategy.
     */
    public function __construct(
        private readonly ChannelRegistry $registry,
    ) {}

    /**
     * Dispatch the notification immediately in the current process.
     *
     * @param  list<string>  $channels
     */
    public function dispatch(object $notifiable, BaseNotification $notification, array $channels): void
    {
        foreach ($channels as $channel) {
            $this->registry->get($channel)->send($notifiable, $notification);
        }
    }
}
