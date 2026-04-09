<?php

declare(strict_types=1);

namespace Packages\Notifications\Support;

use InvalidArgumentException;
use Packages\Notifications\Contracts\NotificationChannel;

final class ChannelRegistry
{
    /**
     * @var array<string, NotificationChannel>
     */
    private array $channels = [];

    /**
     * Register a channel instance under the given name.
     */
    public function register(string $name, NotificationChannel $channel): void
    {
        $this->channels[$name] = $channel;
    }

    /**
     * Return a registered channel or fail if it does not exist.
     *
     * @throws InvalidArgumentException
     */
    public function get(string $name): NotificationChannel
    {
        if (! array_key_exists($name, $this->channels)) {
            throw new InvalidArgumentException("Notification channel '{$name}' is not registered.");
        }

        return $this->channels[$name];
    }

    /**
     * Return all registered channels.
     *
     * @return array<string, NotificationChannel>
     */
    public function all(): array
    {
        return $this->channels;
    }
}
