<?php

declare(strict_types=1);

namespace Packages\Notifications\Support;

use InvalidArgumentException;

final class PushConfigResolver
{
    /**
     * Resolve a configured push channel alias into a broadcast channel name.
     *
     * @param  array<string, scalar|null>  $parameters
     * @return array{name: string, type: string}
     */
    public function resolveChannel(string $alias, array $parameters = []): array
    {
        /** @var array<string, array{name?: string, type?: string}> $channels */
        $channels = (array) config('notifications.push.channels', []);

        if (! array_key_exists($alias, $channels)) {
            throw new InvalidArgumentException("Push channel alias '{$alias}' is not configured.");
        }

        $definition = $channels[$alias];
        $name = (string) ($definition['name'] ?? '');
        $type = (string) ($definition['type'] ?? 'public');

        if ($name === '') {
            throw new InvalidArgumentException("Push channel alias '{$alias}' has no configured name.");
        }

        return [
            'name' => $this->replacePlaceholders($name, $parameters),
            'type' => $type,
        ];
    }

    /**
     * Ensure the push action is one of the configured allowed actions.
     */
    public function assertValidAction(string $action): void
    {
        $actions = array_map('strval', (array) config('notifications.push.actions', []));

        if (! in_array($action, $actions, true)) {
            throw new InvalidArgumentException("Push action '{$action}' is not allowed.");
        }
    }

    /**
     * @return array<string, array{name?: string, type?: string}>
     */
    public function privateChannels(): array
    {
        /** @var array<string, array{name?: string, type?: string}> $channels */
        $channels = (array) config('notifications.push.channels', []);

        return array_filter(
            $channels,
            static fn (array $definition): bool => (string) ($definition['type'] ?? 'public') === 'private',
        );
    }

    /**
     * @param  array<string, scalar|null>  $parameters
     */
    private function replacePlaceholders(string $value, array $parameters): string
    {
        return (string) preg_replace_callback(
            '/\{([a-zA-Z0-9_]+)\}/',
            static function (array $matches) use ($parameters): string {
                $key = (string) $matches[1];

                if (! array_key_exists($key, $parameters)) {
                    throw new InvalidArgumentException("Missing push channel parameter '{$key}'.");
                }

                return (string) $parameters[$key];
            },
            $value,
        );
    }
}
