<?php

declare(strict_types=1);

namespace Packages\Notifications\Contracts;

interface WhatsappProvider
{
    /**
     * Send a rendered WhatsApp message to the given phone number.
     */
    public function send(string $phone, string $message): void;
}
