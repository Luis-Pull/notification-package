<?php

declare(strict_types=1);

namespace Packages\Notifications\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

final class GenericPushEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly string $channel,
        private readonly string $event,
        private readonly array $data = [],
    ) {}

    /**
     * Determine the broadcast channels for the push event.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel($this->channel)];
    }

    /**
     * Determine the custom event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return $this->event;
    }

    /**
     * Determine the payload that will be broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return $this->data;
    }
}
