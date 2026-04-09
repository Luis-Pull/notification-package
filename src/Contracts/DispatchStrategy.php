<?php

declare(strict_types=1);

namespace Packages\Notifications\Contracts;

use Packages\Notifications\BaseNotification;

interface DispatchStrategy
{
    /**
     * Dispatch the notification through the selected channels.
     *
     * @param  list<string>  $channels
     */
    public function dispatch(object $notifiable, BaseNotification $notification, array $channels): void;
}
