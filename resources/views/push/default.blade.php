Notification
@foreach($data ?? [] as $key => $value)
{{ $key }}: {{ is_scalar($value) || $value === null ? $value : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}
@endforeach
