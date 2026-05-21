# МарМелАма

Полноценная версия сайта питомника европейской бурмы на Laravel, Filament и MySQL.

Astro/TypeScript-версия удалена. Laravel-приложение находится в корне репозитория.

## Стек

- Laravel
- Filament
- MySQL
- Blade

## Локальный запуск

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user
php artisan serve
```

Админка:

```text
http://127.0.0.1:8000/admin
```

## База данных

Для локальной разработки можно использовать SQLite из `.env`.

Для сервера настрой MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marmelama
DB_USERNAME=marmelama
DB_PASSWORD=
```

Пароли и реальные доступы хранятся только в `.env`. Этот файл не коммитится.

## Админка

В Filament уже подготовлены разделы:

- котята;
- пометы;
- производители;
- отзывы;
- галерея;
- страницы;
- настройки сайта.

## Старый сайт

`old_site/` используется только как локальный архив старого PHP-сайта и не попадает в git.

Для полного переноса контента нужен SQL-дамп старой базы. Старые таблицы, которые нужно импортировать:

- `kittens`;
- `pomet`;
- `cats`;
- `reviews`;
- `content`;
- `gallery`;
- `news`;
- `settings`.

## Деплой

На сервере web root должен смотреть на:

```text
public
```

После деплоя:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
