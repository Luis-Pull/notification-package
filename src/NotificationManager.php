<?php

declare(strict_types=1);

namespace Packages\Notifications;

use Packages\Notifications\Contracts\DispatchStrategy;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\Dispatch\EventDispatch;
use Packages\Notifications\Dispatch\QueueDispatch;
use Packages\Notifications\Dispatch\SyncDispatch;
use Packages\Notifications\Support\ChannelRegistry;

final class NotificationManager
{
    /**
     * @var array<string, class-string<DispatchStrategy>>
     */
    private array $strategyMap = [
        'sync' => SyncDispatch::class,
        'queue' => QueueDispatch::class,
        'event' => EventDispatch::class,
    ];

    /**
     * Create a new notification manager.
     */
    public function __construct(
        private readonly ChannelRegistry $registry,
    ) {}

    /**
     * Send the notification using the configured dispatch strategy.
     *
     * @param array<string, mixed> $data
     */
    public function send(
        object $notifiable,
        ?BaseNotification $notification = null,
        array $data = [],
    ): void
    {
        $notification ??= new GenericNotification('Notification', $data);

        $strategyClass = $this->strategyMap[$notification->dispatchMethod()] ?? QueueDispatch::class;
        /** @var DispatchStrategy $strategy */
        $strategy = app($strategyClass);

        $strategy->dispatch($notifiable, $notification, $notification->channels());
    }

    /**
     * Register a custom channel at runtime.
     */
    public function extend(string $name, NotificationChannel $channel): void
    {
        $this->registry->register($name, $channel);
    }
}
