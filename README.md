# Laravel Notification Package

Simple multi-channel notification package for Laravel.

Supports:

- `mail`
- `sms`
- `push`
- `whatsapp`

## Requirements

- PHP 8.3+
- Laravel 12+

## Install

### Local path repository

In the host project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../notification-package",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "batchnav/notification-package": "*@dev"
    }
}
```

### Composer repository

```bash
composer require batchnav/notification-package
php artisan optimize:clear
```

## Publish

```bash
php artisan vendor:publish --tag=notifications-config
php artisan vendor:publish --tag=notifications-views
```

If you use queued notifications:

```bash
php artisan queue:work --queue=notifications,default
```

## Basic Usage

Generic notification:

```php
use Packages\Notifications\Facades\Notify;

Notify::send($user);
```

Generic notification with data:

```php
Notify::send($user, null, [
    'name' => $user->name,
    'email' => $user->email,
]);
```

Custom notification:

```php
Notify::send($user, new WelcomeNotification($user->name));
```

## Create a Notification

`BaseNotification` already provides defaults for:

- channel
- dispatch
- subject
- template
- payload builders

Example:

```php
<?php

namespace App\Notifications;

use Packages\Notifications\BaseNotification;

class WelcomeNotification extends BaseNotification
{
    public function __construct(
        private readonly string $name,
    ) {}

    public function channels(): array
    {
        return ['mail', 'sms'];
    }

    public function dispatchMethod(): string
    {
        return 'queue';
    }

    public function data(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function subject(): string
    {
        return 'Welcome';
    }

    public function template(string $channel): ?string
    {
        return 'welcome';
    }
}
```

## Push

Push channels and allowed actions are defined in `config/notifications.php`.

Example:

```php
'push' => [
    'channels' => [
        'public' => [
            'type' => 'public',
            'name' => 'batchnav.notifications.public',
        ],
        'private' => [
            'type' => 'private',
            'name' => 'batchnav.notifications.private.{id}',
        ],
    ],
    'actions' => [
        'created',
        'updated',
        'deleted',
    ],
    'defaults' => [
        'channel' => 'public',
    ],
],
```

Private broadcast channels are registered automatically by the package. No manual `routes/channels.php` entry is required for package-defined push channels.


## Providers

If you use `sms` or `whatsapp`, bind your providers in the host app:

```php
use App\Services\TwilioSmsProvider;
use App\Services\WhatsappCloudProvider;
use Packages\Notifications\Contracts\SmsProvider;
use Packages\Notifications\Contracts\WhatsappProvider;

$this->app->bind(SmsProvider::class, TwilioSmsProvider::class);
$this->app->bind(WhatsappProvider::class, WhatsappCloudProvider::class);
```

## Templates

Custom templates:

```text
resources/views/notifications/mail/{template}.blade.php
resources/views/notifications/sms/{template}.blade.php
resources/views/notifications/push/{template}.blade.php
resources/views/notifications/whatsapp/{template}.blade.php
```

If a custom template does not exist, the package uses its default view.

## Development

```bash
composer install
composer format
composer lint
```

If you use VS Code, this repo already includes format-on-save settings for Laravel Pint.
