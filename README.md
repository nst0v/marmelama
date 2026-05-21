# МарМелАма

Сайт питомника европейской бурмы «МарМелАма» на Laravel, Blade, Filament и MySQL.

Проект заменяет старый PHP-сайт современной структурой: публичная часть сайта работает на Blade-шаблонах, админка работает на Filament, данные импортируются из старого SQL-дампа отдельной artisan-командой.

## Что внутри

- Laravel 13
- Filament 5 для админки
- Blade для публичных страниц
- MySQL для боевого сервера
- SQLite или MySQL для локальной разработки
- Импорт старой базы через `php artisan legacy:import`
- Без Node, Vite, Astro и TypeScript

## Основные разделы

- `/` - главная
- `/pets` - котята
- `/pets/{slug}` - отдельный котенок
- `/pomet` - пометы
- `/pomet/{slug}` - отдельный помет
- `/parents/1` - коты
- `/parents/0` - кошки
- `/reviews` - отзывы
- `/dostavka` - доставка
- `/contacts` - контакты
- `/gallery` - галерея
- `/news` - новости
- `/archive` - архив котят
- `/admin` - админка Filament

Старые URL вида `/pets/1`, `/pomet/26`, `/parents/0/74`, `/news/12` поддерживаются, если такие записи есть в импортированных данных.

## Требования

Для локального запуска нужны:

- PHP 8.3 или новее
- Composer 2
- Расширения PHP: `pdo`, `pdo_sqlite` или `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `json`, `tokenizer`, `xml`
- SQLite для простого локального запуска или MySQL для запуска как на сервере

Проверить версии:

```bash
php -v
composer -V
```

## Быстрый запуск дома на SQLite

Это самый простой вариант для разработки на своем компьютере.

```bash
git clone git@github.com:nst0v/marmelama.git
cd marmelama

composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

Открой `.env` и выставь SQLite:

```env
APP_NAME="МарМелАма"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=/полный/путь/до/marmelama/database/database.sqlite
```

Пример для этого компьютера:

```env
DB_DATABASE=/home/nikita/projects/marmelama/database/database.sqlite
```

После настройки `.env` выполни миграции:

```bash
php artisan migrate
php artisan storage:link
```

Создай пользователя админки:

```bash
php artisan make:filament-user
```

Запусти сайт:

```bash
php artisan serve
```

Открой:

```text
http://127.0.0.1:8000
http://127.0.0.1:8000/admin
```

## Запуск дома на MySQL

Если хочешь локально работать так же, как на будущем сервере, создай базу MySQL:

```sql
CREATE DATABASE marmelama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

В `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marmelama
DB_USERNAME=твой_пользователь
DB_PASSWORD=твой_пароль
```

Дальше:

```bash
php artisan migrate
php artisan storage:link
php artisan make:filament-user
php artisan serve
```

## Импорт старого сайта из SQL-дампа

Старый дамп не хранится в git. Его нужно держать локально, например:

```text
/home/nikita/Загрузки/Telegram Desktop/marmelama_rf.sql
```

Сначала можно проверить, что команда видит данные:

```bash
php artisan legacy:import "/путь/до/marmelama_rf.sql" --dry-run
```

Полный импорт с очисткой уже импортированных таблиц:

```bash
php artisan legacy:import "/путь/до/marmelama_rf.sql" --fresh
```

Команда переносит в новую структуру:

- производителей из `cats`
- пометы из `pomet`
- котят из `kittens`
- отзывы из `reviews`
- страницы из `content`
- галерею из `gallery`
- новости из `news`
- настройки сайта из `settings`

## Импорт старых изображений

По умолчанию импорт ищет старые изображения в:

```text
old_site/file
```

Папка `old_site/` нужна только локально и не коммитится. Если старый сайт скачан в другое место, укажи путь явно:

```bash
php artisan legacy:import "/путь/до/marmelama_rf.sql" --fresh --image-source="/путь/до/старого_сайта/file"
```

Скопированные файлы складываются в:

```text
storage/app/public/legacy
```

Чтобы картинки открывались в браузере, должна существовать ссылка:

```bash
php artisan storage:link
```

Если картинки на сайте дают 404, почти всегда нужно заново выполнить `php artisan storage:link` или проверить права на папку `storage`.

## Админка

Админка доступна по адресу:

```text
/admin
```

Создание администратора:

```bash
php artisan make:filament-user
```

В админке есть разделы:

- котята
- пометы
- производители
- отзывы
- галерея
- новости
- страницы
- настройки сайта

## Настройки сайта

Часть контактов берется из таблицы `site_settings`.

Если настройки еще не заполнены, сайт использует резервные значения из кода:

- название: `МарМелАма`
- город: `Омск`
- телефон: `+7 (913) 645-31-18`
- email: `balovatskaya@mail.ru`

После импорта старой базы проверь настройки в админке.

## Проверка перед коммитом

Перед коммитом желательно прогонять:

```bash
composer validate --no-check-publish
php artisan test
php artisan route:list
```

Дополнительно можно проверить синтаксис PHP:

```bash
find app routes config database tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Что не коммитить

В git не должны попадать:

- `.env`
- пароли и доступы
- SQL-дампы
- `vendor/`
- `node_modules/`
- `old_site/`
- `database/*.sqlite`
- `public/storage`
- файлы из `storage/app/public`

Это уже учтено в `.gitignore`, но перед коммитом все равно проверяй:

```bash
git status --short
```

## Деплой на обычный сервер

На сервере домен должен смотреть в папку:

```text
public
```

Не в корень репозитория, а именно в `public`.

Базовый порядок деплоя:

```bash
git clone git@github.com:nst0v/marmelama.git
cd marmelama

composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

В `.env` на сервере:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://мармелама.рф

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=имя_боевой_базы
DB_USERNAME=пользователь_боевой_базы
DB_PASSWORD=пароль_боевой_базы

MAIL_MAILER=log
```

Затем:

```bash
php artisan migrate --force
php artisan storage:link
php artisan make:filament-user
```

Если нужно перенести старый контент на сервер:

```bash
php artisan legacy:import "/путь/до/marmelama_rf.sql" --fresh --image-source="/путь/до/старого_сайта/file"
```

После этого включи кеши:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Обновление сайта на сервере

Когда в репозитории появились новые изменения:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Права на сервере

Laravel должен иметь права на запись в:

```text
storage
bootstrap/cache
```

Типичный вариант:

```bash
chmod -R ug+rw storage bootstrap/cache
```

На shared-хостинге права и владелец зависят от панели хостинга. Главное правило: PHP-процесс должен уметь писать в эти папки.

## Частые проблемы

Если сайт показывает 500:

```bash
php artisan optimize:clear
tail -n 100 storage/logs/laravel.log
```

Если не открываются картинки:

```bash
php artisan storage:link
```

Если админка не пускает:

```bash
php artisan make:filament-user
```

Если после изменения `.env` сайт ведет себя по-старому:

```bash
php artisan config:clear
php artisan config:cache
```

Если локально нет данных после миграций, это нормально: миграции создают пустые таблицы. Для наполнения нужно выполнить импорт старого SQL-дампа.

## Полезные команды

Запуск локального сервера:

```bash
php artisan serve
```

Очистка кешей:

```bash
php artisan optimize:clear
```

Список маршрутов:

```bash
php artisan route:list
```

Проверка тестов:

```bash
php artisan test
```

Повторный импорт старой базы:

```bash
php artisan legacy:import "/путь/до/marmelama_rf.sql" --fresh
```
