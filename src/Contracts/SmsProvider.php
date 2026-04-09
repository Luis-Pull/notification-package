<?php

declare(strict_types=1);

namespace Packages\Notifications\Contracts;

interface SmsProvider
{
    /**
     * Send a rendered SMS message to the given phone number.
     */
    public function send(string $phone, string $message): void;
}
