<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Notification' }}</title>
</head>
<body>
    <h1>{{ $subject ?? 'Notification' }}</h1>
    @foreach($data ?? [] as $key => $value)
        <p>
            <strong>{{ $key }}:</strong>
            {{ is_scalar($value) || $value === null ? $value : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}
        </p>
    @endforeach
</body>
</html>
