<?php

declare(strict_types=1);

namespace Packages\Notifications;

final class GenericNotification extends BaseNotification
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly string $name = 'Notification',
        private readonly array $data = [],
    ) {}

    /**
     * @return list<string>
     */
    public function channels(): array
    {
        return ['mail', 'push'];
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    protected function notificationName(): string
    {
        return $this->name;
    }

    /**
     * Push action used to validate the final event action suffix.
     */
    public function pushAction(): ?string
    {
        return 'default';
    }

    /**
     * Push event name used when the notification is sent through `push`.
     */
    public function pushEvent(): ?string
    {
        return $this->pushAction();
    }
}
