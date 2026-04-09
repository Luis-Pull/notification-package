<?php

declare(strict_types=1);

namespace Packages\Notifications\Contracts;

use Packages\Notifications\BaseNotification;

interface NotificationChannel
{
    /**
     * Channel identifier: 'mail' | 'sms' | 'push' | 'whatsapp'.
     */
    public function name(): string;

    /**
     * Send the notification through this channel.
     */
    public function send(object $notifiable, BaseNotification $notification): void;
}
