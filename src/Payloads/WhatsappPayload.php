<?php

declare(strict_types=1);

namespace Packages\Notifications\Payloads;

use Packages\Notifications\Contracts\HasNotificationPayload;

final class WhatsappPayload implements HasNotificationPayload
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(
        private readonly array $data = [],
        private readonly ?string $template = null,
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
}
