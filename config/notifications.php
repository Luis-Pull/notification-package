<?php

declare(strict_types=1);
use Packages\Notifications\Channels\MailChannel;
use Packages\Notifications\Channels\PushChannel;
use Packages\Notifications\Channels\SmsChannel;
use Packages\Notifications\Channels\WhatsappChannel;
use Packages\Notifications\Dispatch\EventDispatch;
use Packages\Notifications\Dispatch\QueueDispatch;
use Packages\Notifications\Dispatch\SyncDispatch;

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
     | Available dispatch strategies.
     | The selected strategy is resolved from `default_dispatch`
     | or from the notification's `dispatchMethod()`.
    */
    'dispatchers' => [
        'sync' => SyncDispatch::class,
        'queue' => QueueDispatch::class,
        'event' => EventDispatch::class,
    ],

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
        'mail' => MailChannel::class,
        'sms' => SmsChannel::class,
        'push' => PushChannel::class,
        'whatsapp' => WhatsappChannel::class,
    ],
];
