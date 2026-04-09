<?php

declare(strict_types=1);

namespace Packages\Notifications\Channels;

use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\Contracts\WhatsappProvider;
use Packages\Notifications\Support\TemplateResolver;

final class WhatsappChannel implements NotificationChannel
{
    /**
     * Channel identifier: 'whatsapp'.
     */
    public function name(): string
    {
        return 'whatsapp';
    }

    /**
     * Send the notification through the WhatsApp channel.
     */
    public function send(object $notifiable, BaseNotification $notification): void
    {
        $payload = $notification->toWhatsapp();

        if ($payload === null) {
            return;
        }

        $view = app(TemplateResolver::class)->resolve('whatsapp', $payload);
        $message = view($view, ['data' => $payload->data()] + $payload->data())->render();

        app(WhatsappProvider::class)->send((string) $notifiable->phone, $message);
    }
}
