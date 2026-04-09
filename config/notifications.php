<?php

declare(strict_types=1);

return [
    /*
     | Directory where the app stores custom notification views.
     | Each channel has its own subfolder:
     |   {views_path}/mail/{template}.blade.php
     |   {views_path}/sms/{template}.blade.php
     |   {views_path}/push/{template}.blade.php
     |   {views_path}/whatsapp/{template}.blade.php
    */
    'views_path' => resource_path('views/notifications'),

    /*
     | Default dispatch strategy: 'sync' | 'queue' | 'event'
    */
    'default_dispatch' => 'queue',

    /*
     | Default channel used by GenericNotification when no channel is provided.
    */
    'default_channel' => 'mail',

    /*
     | Queue name used by QueueDispatch and SendNotificationJob
    */
    'queue' => 'notifications',

    /*
     | Registered channels. Add custom channels here.
    */
    'channels' => [
        'mail' => \Packages\Notifications\Channels\MailChannel::class,
        'sms' => \Packages\Notifications\Channels\SmsChannel::class,
        'push' => \Packages\Notifications\Channels\PushChannel::class,
        'whatsapp' => \Packages\Notifications\Channels\WhatsappChannel::class,
    ],
];
