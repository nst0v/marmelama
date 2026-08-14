# МарМелАма

Сайт питомника европейской бурмы «МарМелАма» на Laravel, Blade, Filament и MySQL.

Проект заменяет старый PHP-сайт современной структурой: публичная часть работает на Blade-шаблонах, а компактная Filament-админка предназначена для котят, помётов, слайдов, отзывов, производителей и контактов. Данные из старого сайта импортируются отдельной artisan-командой.

## Что внутри

- Laravel 13
- Filament 5 для админки
- Blade для публичных страниц
- MySQL или MariaDB для базы данных
- Импорт старой базы через `php artisan site:import-dump`
- Без Node, Vite, Astro и TypeScript

## Требования

Перед запуском нужны:

- PHP 8.3 или новее
- Composer 2
- MySQL 8 или MariaDB
- Расширения PHP: `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `json`, `tokenizer`, `xml`

Проверить версии:

```bash
php -v
composer -V
mysql --version
```

## Быстрый запуск на MySQL

Команды ниже рассчитаны на Linux, macOS или WSL. Если проект уже скачан, начни с команды `cd marmelama`.

1. Скачай проект и установи PHP-зависимости:

```bash
git clone git@github.com:nst0v/marmelama.git
cd marmelama
composer install
```

2. Создай базу данных MySQL и отдельного пользователя для проекта:

```bash
mysql -u root -p <<'SQL'
CREATE DATABASE IF NOT EXISTS marmelama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'marmelama'@'localhost' IDENTIFIED BY 'marmelama_password';
GRANT ALL PRIVILEGES ON marmelama.* TO 'marmelama'@'localhost';
FLUSH PRIVILEGES;
SQL
```

Если хочешь другой пароль, замени `marmelama_password` в SQL-команде и в `.env`.

3. Создай `.env` и пропиши подключение к MySQL:

```bash
[ -f .env ] || cp .env.example .env
php -r '$env = file_get_contents(".env"); $values = ["APP_URL" => "http://127.0.0.1:8000", "DB_CONNECTION" => "mysql", "DB_HOST" => "127.0.0.1", "DB_PORT" => "3306", "DB_DATABASE" => "marmelama", "DB_USERNAME" => "marmelama", "DB_PASSWORD" => "marmelama_password"]; foreach ($values as $key => $value) { $env = preg_replace("/^".$key."=.*/m", $key."=".$value, $env); } file_put_contents(".env", $env);'
```

После этой команды в `.env` должны быть такие настройки:

```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marmelama
DB_USERNAME=marmelama
DB_PASSWORD=marmelama_password
```

4. Подготовь Laravel:

```bash
php artisan key:generate
php artisan optimize:clear
php artisan migrate
php artisan storage:link
```

5. Создай пользователя админки:

```bash
php artisan make:filament-user
```

Команда спросит имя, email и пароль. Эти данные потом используются для входа в `/admin`.

6. Запусти сайт:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

После запуска открой:

```text
http://127.0.0.1:8000
http://127.0.0.1:8000/admin
```

Остановить локальный сервер можно через `Ctrl+C` в терминале.

## Импорт старого сайта

Старый SQL-дамп не хранится в git. Его нужно положить локально, например в папку загрузок.

Сначала проверь, что команда видит данные:

```bash
php artisan site:import-dump "/полный/путь/до/marmelama_rf.sql" --dry-run
```

Если проверка прошла нормально, запусти импорт в MySQL-базу:

```bash
php artisan site:import-dump "/полный/путь/до/marmelama_rf.sql" --fresh
```

Если рядом есть скачанные файлы старого сайта, укажи папку со старыми изображениями:

```bash
php artisan site:import-dump "/полный/путь/до/marmelama_rf.sql" --fresh --image-source="/полный/путь/до/old_site/file"
```

После импорта убедись, что публичная ссылка на `storage` создана:

```bash
php artisan storage:link
```

Если старые картинки уже лежали в `public/images`, можно перенести их в правильное хранилище:

```bash
php artisan media:sync-storage
```

## Основные страницы

- `/` - главная
- `/kittens` - котята
- `/kittens/{slug}` - отдельный котёнок
- `/litters` - помёты
- `/litters/{slug}` - отдельный помёт
- `/parents/1` - коты
- `/parents/0` - кошки
- `/reviews` - отзывы
- `/delivery` - доставка
- `/contacts` - контакты
- `/gallery` - галерея
- `/news` - новости
- `/archive` - постоянное перенаправление на проданных котят
- `/admin` - админка Filament

Старые `/pets`, `/pomet` и `/dostavka` постоянно перенаправляются на новые адреса. URL записей вида `/pets/1`, `/pomet/26`, `/parents/0/74`, `/news/12` поддерживаются, если такие записи есть в импортированных данных.

## Настройки сайта

Из таблицы `site_settings` берутся только понятные владельцу контакты: телефон, email и ссылка MAX. Технические ключи, типы и группы в админке не редактируются.

Если настройки еще не заполнены, сайт использует резервные значения из кода:

- название: `МарМелАма`
- город: `Омск`
- телефон: `+7 (913) 645-31-18`
- email: `balovatskaya@mail.ru`

После импорта старой базы проверь настройки в админке.

## Админка

Основные рабочие разделы: котята, помёты, слайды и отзывы. Производители нужны для выбора родителей помёта, а раздел «Контакты» — для телефона, email и ссылки MAX. ЧПУ, legacy ID, SEO-поля и числовой приоритет пользователю не показываются: адреса создаются автоматически, а слайды сортируются перетаскиванием.

## Полезные команды

Запустить сайт:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Очистить кеши Laravel:

```bash
php artisan optimize:clear
```

Пересоздать MySQL-базу с нуля, удалив все текущие таблицы:

```bash
php artisan migrate:fresh
```

Пересоздать базу и сразу импортировать старый дамп:

```bash
php artisan migrate:fresh
php artisan site:import-dump "/полный/путь/до/marmelama_rf.sql" --fresh
```

Показать список маршрутов:

```bash
php artisan route:list
```

## Деплой на сервер

На сервере домен должен смотреть в папку `public`, а не в корень репозитория.

Первый деплой:

```bash
git clone git@github.com:nst0v/marmelama.git
cd marmelama
composer install --no-dev --optimize-autoloader
[ -f .env ] || cp .env.example .env
php artisan key:generate
```

В `.env` на сервере укажи боевые настройки MySQL:

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
```

Затем выполни:

```bash
php artisan migrate --force
php artisan storage:link
php artisan make:filament-user
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Если нужно импортировать старый контент на сервере:

```bash
php artisan site:import-dump "/полный/путь/до/marmelama_rf.sql" --fresh --image-source="/полный/путь/до/old_site/file"
php artisan storage:link
```

Для обновления уже установленного сайта:

```bash
# Сначала сделай резервную копию базы штатными средствами хостинга или mysqldump.
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Резервная копия обязательна для обновления, которое удаляет старые таблицы статей, комментариев и вопросов, а также неиспользуемые legacy-настройки и контентные блоки.

Laravel должен иметь права на запись в `storage` и `bootstrap/cache`. Типичная команда:

```bash
chmod -R ug+rw storage bootstrap/cache
```

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

## Частые проблемы

Если ошибка `could not find driver`, установи PHP-расширение `pdo_mysql`.

Если Laravel не может подключиться к базе, проверь значения `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` и `DB_PASSWORD` в `.env`, затем выполни:

```bash
php artisan optimize:clear
```

Если ошибка `No application encryption key has been specified`, выполни:

```bash
php artisan key:generate
```

Если картинки открываются с 404, выполни:

```bash
php artisan storage:link
```

## Что не коммитить

В git не должны попадать:

- `.env`
- пароли и доступы
- SQL-дампы
- `vendor/`
- `node_modules/`
- `old_site/`
- `public/storage`
- файлы из `storage/app/public`

Перед коммитом проверь статус:

```bash
git status --short
```
