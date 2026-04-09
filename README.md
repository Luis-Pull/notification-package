# Laravel Notification Package

Standalone notification package for Laravel with support for `mail`, `sms`, `push`, and `whatsapp`.

The package is built around a simple default:

- `Notify::send($notifiable)` sends a generic notification through the configured default channel
- `Notify::send($notifiable, new CustomNotification())` sends a custom notification class
- `BaseNotification` already provides default channel, dispatch, subject, template resolution, and payload builders

## Requirements

- PHP 8.3+
- Laravel 11 or 12

## Features

- Sensible defaults in `BaseNotification`
- Generic notification out of the box
- Multiple channels per notification
- Dispatch modes: `sync`, `queue`, `event`
- Custom Blade templates per channel
- Runtime extension for custom channels

## Installation

### Option 1: Local path repository

If you want to use this package from another project on the same machine, add this to the host project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../notification-package-v0.2",
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

Then run:

```bash
composer update batchnav/notification-package -W
```

### Option 2: Packagist or private Composer repository

```bash
composer require batchnav/notification-package
```

Laravel package discovery will automatically register the service provider and facade.

After installing in the host app, it is usually a good idea to clear cached metadata:

```bash
php artisan optimize:clear
```

## Publish package files

Publish config:

```bash
php artisan vendor:publish --tag=notifications-config
```

Publish views:

```bash
php artisan vendor:publish --tag=notifications-views
```

## Configuration

The package publishes `config/notifications.php` with these main options:

- `views_path`: application path for custom templates
- `default_dispatch`: `sync`, `queue`, or `event`
- `dispatchers`: dispatch strategy map used by `NotificationManager`
- `default_channel`: default channel used by `BaseNotification` and `GenericNotification`
- `queue`: queue name for background processing
- `channels`: registered notification channels

If you use the default dispatch mode `queue`, run a worker:

```bash
php artisan queue:work --queue=notifications
```

## Register SMS and WhatsApp providers

The package includes the channel contracts, but your application must bind the real provider implementations.

Example in `AppServiceProvider`:

```php
<?php

namespace App\Providers;

use App\Services\TwilioSmsProvider;
use App\Services\WhatsappCloudProvider;
use Illuminate\Support\ServiceProvider;
use Packages\Notifications\Contracts\SmsProvider;
use Packages\Notifications\Contracts\WhatsappProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsProvider::class, TwilioSmsProvider::class);
        $this->app->bind(WhatsappProvider::class, WhatsappCloudProvider::class);
    }
}
```

If you use the `push` channel, make sure Laravel broadcasting is configured in the host application.

## How It Works

`BaseNotification` already contains the default behavior for:

- `channels()`
- `dispatchMethod()`
- `data()`
- `subject()`
- `template($channel)`
- `toMail()`, `toSms()`, `toPush()`, `toWhatsapp()`

That means child classes only need to override what they actually want to change.

## Create a Notification

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

If you do not override `template($channel)`, the package uses the default template for that channel.

## Send Notifications

```php
use Packages\Notifications\Facades\Notify;

Notify::send($user, new WelcomeNotification($user->name));
```

You can also send a minimal generic notification with only the notifiable:

```php
use Packages\Notifications\Facades\Notify;

Notify::send($user);
```

In that case the package:

- Uses `GenericNotification`
- Uses the channel from `notifications.default_channel`
- Defaults the subject to `Notification`
- Uses the default channel template for `mail`, `sms`, `push`, or `whatsapp` unless a child class overrides `template($channel)`

You can also pass data to the generic notification:

```php
use Packages\Notifications\Facades\Notify;

Notify::send($user, null, [
    'name' => $user->name,
    'email' => $user->email,
]);
```

This third argument is only used when the second argument is omitted.

## Notifiable object requirements

The package reads properties directly from the notifiable object:

- `mail` expects `$notifiable->email`
- `sms` expects `$notifiable->phone`
- `whatsapp` expects `$notifiable->phone`

Any model or DTO can be used as long as those properties exist.

## Custom templates

Custom templates are resolved from:

```text
resources/views/notifications/mail/{template}.blade.php
resources/views/notifications/sms/{template}.blade.php
resources/views/notifications/push/{template}.blade.php
resources/views/notifications/whatsapp/{template}.blade.php
```

If no custom template is found, the package falls back to the default package views.

`BaseNotification` already handles default templates per channel. Child classes only need to override `template(string $channel)` when they want a custom one.

Default package templates live here:

```text
resources/views/mail/default.blade.php
resources/views/sms/default.blade.php
resources/views/push/default.blade.php
resources/views/whatsapp/default.blade.php
```

## Custom channels

You can register custom channels from the host application:

```php
use Packages\Notifications\Facades\Notify;

Notify::extend('slack', app(SlackChannel::class));
```

You can also add channels through the published config file.

## Publishing this package

Before publishing to a public repository or Packagist, review these items:

1. Keep `batchnav/notification-package` if consuming projects already depend on that name.
2. Add package metadata such as `homepage`, `support`, and repository URL if needed.
3. Replace the placeholder owner in `LICENSE`.
4. Create semantic version tags such as `v0.1.0` or `v1.0.0`.

## Suggested Git workflow

```bash
git add .
git commit -m "Initial package release"
git branch -M main
git remote add origin <your-repository-url>
git push -u origin main
```

If `git push -u origin main` fails with `src refspec main does not match any`, it means you still have no commit. Run `git add .` and `git commit` first.

If you publish to Packagist, connect the repository there after the first push.
