<?php

declare(strict_types=1);

namespace Packages\Notifications\Channels;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\Contracts\SmsProvider;
use Packages\Notifications\Support\TemplateResolver;

final class SmsChannel implements NotificationChannel
{
    /**
     * Channel identifier: 'sms'.
     */
    public function name(): string
    {
        return 'sms';
    }

    /**
     * Send the notification through the SMS channel.
     */
    public function send(object $notifiable, BaseNotification $notification): void
    {
        $payload = $notification->toSms();

        if ($payload === null) {
            return;
        }

        $view = app(TemplateResolver::class)->resolve('sms', $payload);
        $message = view($view, ['data' => $payload->data()] + $payload->data())->render();

        app(SmsProvider::class)->send((string) $notifiable->phone, $message);
    }
}
