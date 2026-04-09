<?php

declare(strict_types=1);

namespace Packages\Notifications\Payloads;

use Packages\Notifications\Contracts\HasNotificationPayload;

final class PushPayload implements HasNotificationPayload
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly string $pusherChannel,
        private readonly string $event,
        private readonly array $data = [],
        private readonly ?string $template = null,
        private readonly bool $private = false,
    ) {}

    /**
     * Blade view name without extension. Null = use the channel default view.
     */
    public function template(): ?string
    {
        return $this->template;
    }

    /**
     * Data passed to the blade view.
     *
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Broadcast channel name used by the push event.
     */
    public function pusherChannel(): string
    {
        return $this->pusherChannel;
    }

    /**
     * Broadcast event name used by the push event.
     */
    public function event(): string
    {
        return $this->event;
    }

    /**
     * Determine whether the resolved broadcast channel is private.
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }
}
