<?php

declare(strict_types=1);

namespace Packages\Notifications\Support;

use Packages\Notifications\Contracts\HasNotificationPayload;

final class TemplateResolver
{
    /**
     * Resolve the blade view name for the given channel payload.
     */
    public function resolve(string $channel, HasNotificationPayload $payload): string
    {
        $custom = $payload->template();
        $basePath = rtrim((string) config('notifications.views_path', resource_path('views/notifications')), DIRECTORY_SEPARATOR);

        if (is_string($custom) && $custom !== '') {
            $channelPath = $basePath.DIRECTORY_SEPARATOR.$channel;
            $fullPath = $channelPath.DIRECTORY_SEPARATOR.$custom.'.blade.php';

            if (is_file($fullPath)) {
                view()->addNamespace($this->customNamespace($channel), $channelPath);

                return $this->customNamespace($channel).'.'.$custom;
            }
        }

        return "notifications::{$channel}.default";
    }

    /**
     * Build the temporary namespace used for application-level notification views.
     */
    private function customNamespace(string $channel): string
    {
        return 'notifications-custom-'.$channel;
    }
}
