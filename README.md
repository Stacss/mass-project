
# Инструкция по установке и использованию приложения Mass Project

## Установка приложения на другом сервере

1. Склонируйте репозиторий с GitHub на сервере:

```shell
git clone https://github.com/Stacss/mass-project.git
```

2. Перейдите в директорию проекта:

```shell
cd mass-project
```

3. Установите все зависимости, выполнив команду:

```shell
composer install
```

4. Скопируйте файл `.env.example` в `.env`:

```shell
cp .env.example .env
```

5. В файле `.env` настройте параметры подключения к базе данных и другие конфигурационные параметры.

6. Сгенерируйте ключ приложения:

```shell
php artisan key:generate
```

7. Выполните миграции для создания структуры базы данных:

```shell
php artisan migrate
```

8. Запустите встроенный сервер Laravel:

```shell
php artisan serve
```

Приложение будет доступно по адресу [http://localhost:8000](http://localhost:8000).

## Добавление пользователей через php artisan tinker

1. Запустите командную оболочку Tinker:

```shell
php artisan tinker
```

2. Добавьте нового пользователя, заменив значения по вашему выбору:

```shell
$user = new User;
$user->name = 'Masha';
$user->email = 'Masha@mail.ru';
$user->password = bcrypt('123456789'); 
$user->save();
```

3. Выйдите из Tinker:

```shell
exit
```

## Генерация токена входа в API

1. Для генерации токена API, необходимо перейти на :
```shell
you-domain/create-token
```
Ввести id пользователя и название Токена, после отправки формы будет показан Токен для работы с API. Внимание, Токен будет показан только один раз, необходимо его сохранить в безопасном месте.

## Работа с API

### Создание новой заявки

Чтобы создать новую заявку, выполните POST-запрос на следующем адресе:

```shell
POST you-domain/api/requests
```
В заголовке необходимо передать
```shell
Accept:application/json
Authorization:Bearer ТокенAPI
```

В теле запроса укажите следующие параметры:

- `name`: имя пользователя (строка, обязательный);
- `email`: адрес электронной почты (строка, обязательный);
- `message`: сообщение (строка, обязательный).

Пример тела запроса
```shell
{
  "name": "Имя",
  "email": "Name@example.com",
  "message": "Текст сообщения"
}
```
При удачном запросе, вернется код ответа 201 и тело
```shell
{
    "message": "Заявка успешно создана",
    "request": {
        "name": "Имя",
        "email": "name@example.com",
        "status": "Active",
        "message": "Текст запроса",
        "updated_at": "2023-09-18T17:23:00.000000Z",
        "created_at": "2023-09-18T17:23:00.000000Z",
        "id": 8
    }
}
```

Коды ошибок:
- 400 - ошибка валидации;
- 401 - ошибка аутентификации

### Получение списка заявок с фильтрацией

Чтобы получить список заявок с возможностью фильтрации по статусу и дате создания, выполните GET-запрос на следующем адресе:

```shell
GET you-domain/api/requests
```

Вы можете использовать следующие параметры запроса:

- `status`: статус заявки для фильтрации (строка, необязательный). Принимает параметры Active, Resolved;
- `start_date`: начальная дата диапазона (строка, необязательный). Принимает дату в формате 2023-09-19;
- `end_date`: конечная дата диапазона (строка, необязательный). Принимает дату в формате 2023-09-19.

Пример запроса
```shell
http://you-domain/api/requests?start_date=2023-09-01&end_date=2023-09-19&status=Active
```
Пример ответа, код ответа 200
```shell
{
    "requests": [
        {
            "id": 2,
            "name": "Василий",
            "email": "vasya@example.com",
            "status": "Active",
            "message": "Hello!",
            "comment": null,
            "created_at": "2023-09-18T12:16:39.000000Z",
            "updated_at": "2023-09-18T12:16:39.000000Z"
        },
        {
            "id": 4,
            "name": "Nikolos",
            "email": "Nikolos@example.com",
            "status": "Active",
            "message": "Текст заявки!",
            "comment": null,
            "created_at": "2023-09-18T13:03:05.000000Z",
            "updated_at": "2023-09-18T13:03:05.000000Z"
        }
    ]
}
```


### Обновление заявки

Чтобы обновить заявку, установить ей статус "Завершено" и добавить комментарий, выполните PUT-запрос на следующем адресе, где `{id}` - идентификатор заявки:

```shell
PUT domain.com/api/requests/{id}
```

В заголовке необходимо передать
```shell
Accept:application/json
Authorization:Bearer ТокенAPI
```

В теле запроса укажите следующие параметры:

- `comment`: комментарий (строка, обязательный).

Пример тела запроса
```shell
{
	"comment": "заявка рассмотрена!"
}
```
Пример ответа, код 200
```shell
{
    "message": "Заявка успешно обновлена"
}
```
Коды ошибок:
- 404 - заявка не найдена
- 400 - ошибка валидации запроса
- 401 - ошибка аутентификации
