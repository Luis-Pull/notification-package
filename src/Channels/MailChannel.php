<?php

declare(strict_types=1);

namespace Packages\Notifications\Channels;

use Illuminate\Support\Facades\Mail;
use Packages\Notifications\BaseNotification;
use Packages\Notifications\Contracts\NotificationChannel;
use Packages\Notifications\Mail\GenericMailable;
use Packages\Notifications\Support\TemplateResolver;

final class MailChannel implements NotificationChannel
{
    /**
     * Channel identifier: 'mail'.
     */
    public function name(): string
    {
        return 'mail';
    }

    /**
     * Send the notification through the mail channel.
     */
    public function send(object $notifiable, BaseNotification $notification): void
    {
        $payload = $notification->toMail();

        if ($payload === null) {
            return;
        }

        $view = app(TemplateResolver::class)->resolve('mail', $payload);

        Mail::to($notifiable->email)->send(
            new GenericMailable($payload->subject(), $view, $payload->data())
        );
    }
}
