<?php

declare(strict_types=1);

namespace Packages\Notifications\Channels;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\Events\GenericPushEvent;
use Packages\Notifications\Support\TemplateResolver;

final class PushChannel implements NotificationChannel
{
    /**
     * Channel identifier: 'push'.
     */
    public function name(): string
    {
        return 'push';
    }

    /**
     * Send the notification through the push channel.
     */
    public function send(object $notifiable, BaseNotification $notification): void
    {
        $payload = $notification->toPush();

        if ($payload === null) {
            return;
        }

        $view = app(TemplateResolver::class)->resolve('push', $payload);
        $content = view($view, ['data' => $payload->data()] + $payload->data())->render();
        $data = $payload->data();
        $data['content'] = $content;

        broadcast(new GenericPushEvent(
            channel: $payload->pusherChannel(),
            event: $payload->event(),
            data: $data,
            private: $payload->isPrivate(),
        ));
    }
}
