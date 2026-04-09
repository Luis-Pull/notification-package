<?php

declare(strict_types=1);

namespace Packages\Notifications;

use Illuminate\Support\Str;
use Packages\Notifications\Payloads\MailPayload;
use Packages\Notifications\Payloads\PushPayload;
use Packages\Notifications\Payloads\SmsPayload;
use Packages\Notifications\Payloads\WhatsappPayload;

class BaseNotification
{
    /**
     * @return list<string>
     */
    public function channels(): array
    {
        return [(string) config('notifications.default_channel', 'mail')];
    }

    /**
     * Dispatch strategy: 'sync' | 'queue' | 'event'.
     */
    public function dispatchMethod(): string
    {
        return (string) config('notifications.default_dispatch', 'queue');
    }

    /**
     * Data passed to notification payloads.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return [];
    }

    /**
     * Subject used by the mail channel.
     */
    public function subject(): string
    {
        return Str::headline($this->notificationName());
    }

    /**
     * Optional template name without extension for a channel.
     */
    public function template(string $channel): ?string
    {
        return null;
    }

    /**
     * Push channel used when the notification is sent through `push`.
     */
    public function pushChannel(): ?string
    {
        return null;
    }

    /**
     * Push event name used when the notification is sent through `push`.
     */
    public function pushEvent(): ?string
    {
        return null;
    }

    /**
     * Build the mail payload for the notification.
     */
    public function toMail(): ?MailPayload
    {
        if (! in_array('mail', $this->channels(), true)) {
            return null;
        }

        return new MailPayload(
            $this->subject(),
            $this->data(),
            $this->template('mail'),
        );
    }

    /**
     * Build the SMS payload for the notification.
     */
    public function toSms(): ?SmsPayload
    {
        if (! in_array('sms', $this->channels(), true)) {
            return null;
        }

        return new SmsPayload(
            $this->data(),
            $this->template('sms'),
        );
    }

    /**
     * Build the push payload for the notification.
     */
    public function toPush(): ?PushPayload
    {
        if (
            ! in_array('push', $this->channels(), true)
            || $this->pushChannel() === null
            || $this->pushEvent() === null
        ) {
            return null;
        }

        return new PushPayload(
            $this->pushChannel(),
            $this->pushEvent(),
            $this->data(),
            $this->template('push'),
        );
    }

    /**
     * Build the WhatsApp payload for the notification.
     */
    public function toWhatsapp(): ?WhatsappPayload
    {
        if (! in_array('whatsapp', $this->channels(), true)) {
            return null;
        }

        return new WhatsappPayload(
            $this->data(),
            $this->template('whatsapp'),
        );
    }

    protected function notificationName(): string
    {
        return class_basename($this);
    }
}
