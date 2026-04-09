<?php

declare(strict_types=1);

namespace Packages\Notifications\Contracts;

interface HasNotificationPayload
{
    /**
     * Blade view name without extension. Null = use the channel default view.
     */
    public function template(): ?string;

    /**
     * Data passed to the blade view.
     *
     * @return array<string, mixed>
     */
    public function data(): array;
}
