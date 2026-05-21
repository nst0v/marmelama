<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'МарМелАма') }}</title>
    <style>
        :root {
            --color-bg: #FAF7F2;
            --color-surface: #FFFFFF;
            --color-text: #2B211D;
            --color-muted: #7A6A61;
            --color-primary: #8B5E3C;
            --color-border: #E8DDD3;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: var(--color-bg);
            color: var(--color-text);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        main {
            width: min(720px, calc(100% - 32px));
            padding: 40px;
            border: 1px solid var(--color-border);
            border-radius: 24px;
            background: var(--color-surface);
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(32px, 5vw, 56px);
            line-height: 1;
        }

        p {
            margin: 0 0 24px;
            color: var(--color-muted);
            font-size: 18px;
            line-height: 1.6;
        }

        a {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            background: var(--color-primary);
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main>
        <h1>МарМелАма</h1>
        <p>Laravel-версия сайта питомника подготовлена. Публичный интерфейс будет перенесен из дизайн-прототипа, админка Filament уже доступна для настройки данных.</p>
        <a href="{{ url('/admin') }}">Открыть админку</a>
    </main>
</body>
</html>
