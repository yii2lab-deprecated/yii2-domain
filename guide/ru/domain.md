Домен
=====

Каждый домен содержит группу связанных и родственных сущностей.

Например, домен пользователя содержит сервисы

* регистрация
* восстановление пароля
* аутентификация

Например, в домене банк, есть сущности

 * карта
 * банк
 
где карта может содержать информацию о банке.

## Цель

* Обединить родственные сущности в единое пространство.
* Предоставить глобальный доступ к функционалу.
* Инкапсулировать небезопасный функционал.
* решить архитектурный вопрос

## Особенности

* Домен - это независимый пакет
* Работает как контейнер для объектов
* Репозитории и сервисы создаются тогда, когда они действительно нужны
* В пределах домена допускается высокая связность сущностей
* Домены реализуются в виде отдельных модулей и располагаются в папке `modules`
* Имеет единственный экземпляр
* домен предоставляет клиентскому коду интерфейсы своих сервисов

## Структура

Домен имеет в своем распоряжении:

* хранилища
* сервисы
* фабрики
* сущности

## Конфигурация

Объявлять домены можно в файле конфигурации:

```
common\config\services.php
```

Для своих нужд можете переобъявлять свои домены в конфиге:

```
common\config\services-local.php
```

эта конфига не включена в GIT.

Полный формат объявления домена:

```php
return [
	'components' => [
		'geo' => [
			'class' => 'common\ddd\Domain',
			'repositories' => [
				'country' => [
					'class' => 'api\v4\modules\geo\repositories\ar\CountryRepository',
				],
				// можно подставлять драйвер хранилища в отдельном параметре
				'region' => [
					'class' => 'api\v4\modules\geo\repositories\{driver}\RegionRepository',
					'driver' => 'ar',
				],
				'city' => [
					'class' => 'api\v4\modules\geo\repositories\ar\CityRepository',
				],
			],
			'services' => [
				'country' => [
					'class' => 'api\v4\modules\geo\services\СountryService',
				],
				'region' => [
					'class' => 'api\v4\modules\geo\services\RegionService',
				],
				'city' => [
					'class' => 'api\v4\modules\geo\services\СityService',
				],
			],
		],
	],
];
```

Сокращенный формат:

```php
return [
	'components' => [
		'geo' => [
			'class' => 'common\ddd\Domain',
			// указываем базовый путь к домену
			'path' => 'api\v4\modules\geo',
			'repositories' => [
				'country' => 'ar',
				'region' => 'ar',
				'city' => 'ar',
			],
			'services' => [
				'country' => null,
				'region' => null,
				// можно напрямую указать класс
				'city' => 'api\v4\modules\geo\services\СityService',
			],
		],
	],
];
```

Микро-формат:

```php
return [
	'components' => [
		'geo' => [
			'class' => 'common\ddd\Domain',
			// указываем базовый путь к домену
			'path' => 'api\v4\modules\geo',
			// указываем каким дравером хранилища пользоваться по умолчанию
			'defaultDriver' => 'ar',
			'repositories' => [
				'country',
				'region',
				// указываем конкретный драйвер
				'city' => 'tps',
			],
			'services' => [
				'country',
				'region',
				'city',
			],
		],
	],
];
```

Для хранилищ и сервисов используются общие приципы:

Для сервиса и хранилища назначается свойство id и domen.

id - это унильное имя в пределах типа (хранилище или сервис).

domen - домен, содержащий в себе сервисы, хранилища и фабрики.

## Пример кода

### Использование

Обратиться к объекту домена:

```php
Yii::$app->geo;
```

Обратиться к объекту сервиса:

```php
Yii::$app->geo->city;
```

Вызвать метод сервиса:

```php
$allCities = Yii::$app->geo->city->all();
```

Обратиться к объекту хранилища:

```php
Yii::$app->geo->repositories->city
```

Вызвать метод хранилища:

```php
$allCities = Yii::$app->geo->repositories->city->all();
```
Внутри класса сервиса и хранилища можно обращаться к ресурсам домена так:

```php
$allCities = $this->domain->service->city->all();
```

Конфиг домена имеет несколько свойств:

* class - имя класса домена
* id - имя объекта домена
* path - путь до папки домена
* defaultDriver - драйвер хранилища по умолчанию

### Создание

Так же, можно создать кастомный класс домена.
Создавать его следует в корневой папке домена.
Наследовать неоходимо от базового класса:

```php
namespace api\v4\modules\geo;

use common\ddd\Domain as DddDomain;

class Domain extends DddDomain
{
	
}
```

Можно указать дополнительные параметры:

```php
namespace api\v4\modules\geo;

use common\ddd\Domain as DddDomain;

class Domain extends DddDomain {
	public $id = 'geo';
	public $path = 'api\v4\modules\geo';
	public $defaultDriver = 'ar';
}
```

В таком случае, конфиг будет выглядеть так:

```php
return [
	'components' => [
		'geo' => [
			'class' => 'api\v4\modules\geo\Domain',
			'repositories' => [
				'region',
				'city',
				'country',
				'currency',
			],
			'services' => [
				'region',
				'city',
				'country',
				'currency',
			],
		],
	],
];
```

В кастомном классе домена, параметры path, defaultDriver и id необязательные, 
они могут быть сгененрированы автоматически.

Домен создает экземпляры сервисов и хранилищ, опираясь на конфигурацию.
Так же, назначает им атрибуты:

* id - имя сервиса или хранилища
* domain - объект текущего домена
