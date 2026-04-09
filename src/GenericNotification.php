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
        return ['push'];
    }

    /**
     * Dispatch strategy: 'sync' | 'queue' | 'event'.
     */
    public function dispatchMethod(): string
    {
        return 'sync';
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
}
