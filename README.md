# Laravel 10, VueJS 3, websocket pusher 
Форк чата https://github.com/RobertGoodman08/websocket_chat_laravel
### Описание:
Пет проект - чат
Развернут: https://f-0.ru
Регистрируемся и переходим во вкладку Чат

## Для разработчиков

### Чтобы развернуть проект

1. В корне проекта выполнить:

```composer install```

2. Переименовать .env.example в файл .env
3. В .env меняем
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ваша_база_данных
DB_USERNAME=ваш_пользователь
DB_PASSWORD=пароль

PUSHER_APP_ID=app_id_из_настроек_ключей_pusher.com
PUSHER_APP_KEY=key_из_настроек_ключей_pusher.com
PUSHER_APP_SECRET=secret_из_настроек_ключей_pusher.com
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=cluster_из_настроек_ключей_pusher.com
```
5. В корне проекта выполнить:

```php artisan key:generate```

```php artisan migrate```

```npm install```

6. В корне cтарт сервера разработки 

```npm run dev```

```php artisan serve```

