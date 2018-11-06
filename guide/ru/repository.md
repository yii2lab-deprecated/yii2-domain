Хранилище (Репозиторий)
=======================

Хранилище - это инфраструктурный слой, 
который скрывает в себе реализацию хранения и чтения данных.
Хранилище одной сущности может иметь несколько реализаций (драйверов). 
Это необходимо для того, чтобы прозрачно манипулировать данными, 
абстрагируясь от реализации.

## Цель

* Абстрагировать работу с данными и сделать ее прозрачной
* убрать зависимость от способа хранения данных
* обеспечить удобный интерфейс для манипуляций с данными
* Поддержка разных способов хранения данных
* Решить проблемы, связанные с деталями хранения

## Особенности

* Хранилище - это набор адаптеров с одинаковым интерфейсом
* Хранилище не имеет состояния
* Хранилище принимает и отдает сущности
* К хранилищу может обратиться только сервис или хранилище из того же домена

## Пример кода

Пример обращения к методу хранилища:

```php
Yii::$app->account->repositories->auth->logout();
```

Где:

* `account` - имя домена
* `repositories` - контейнер всех хранилищ домена
* `auth` - хранилище аутентификации
* `logout` - метод выхода из аккаунта

## Базовые классы

Есть несколько базовых классов:

* BaseRepository
* ArRepository
* ActiveArRepository
* DiscRepository
* ActiveDiscRepository
* TpsRepository
* FileRepository

`BaseRepository` - самый базовый класс для репозиториев, 
он наследуется всеми остальными базовыми репозиториями.

### CRUD-операции

Приставка `Active` означает, что репозиторий поддерживает CRUD-функционал.

Вот список CRUD-методов:

* oneById($id, Query $query = null);
* one(Query $query = null);
* all(Query $query = null);
* count(Query $query = null);
* insert(BaseEntity $entity);
* update(BaseEntity $entity);
* delete(BaseEntity $entity);

## Драйвера

Вот список используемых драйверов для репозиториев:

* ar - умеет делать выборки с помощью Active Record.
* disc - умеет делать выборки из файлового хранилища.
* tps - для работы с TPS.
* file - для работы с загружаемыми ресурсами.

CRUD-функционал поддерживают только 2 типа драйвера:

* ar
* disc

Для драйвера disc пока что доступны только операции чтения.

По мере потребностей, типы драйверов репозиториев будут добавляться по ходу разработки.

Например, хранилище пользователя может быть реализовано в виде драйверов: 

* БД - классика
* JSON - хранение данных в файлах
* TPS
* Test - mock для тестирования
* и прочие...

Реализации драйверов хранилища должны предоставлять единый интерфейс.
Используемый драйвер хранилища указывается в конфинурации приложения.

## Порядок работы

Чтение сущности:

* передаем параметры выборки хранилищу
* получаем объект сущности или коллекцию сущностей

Создание сущности:

* создаем объект сущности
* назначаем атрибуты сущности
* передаем объект сущности хранилищу для сохранения

Удаление сущности:

* делаем выборку экземпляра сущности из хранилища
* передаем объект сущности хранилищу для удаления

Изменение сущности:

* делаем выборку экземпляра сущности из хранилища
* изменяем атрибуты объекта сущности
* передаем объект сущности хранилищу для сохранения

## Алиасы имен полей сущности

Если имена полей сущности и БД отличаются, то можно объявить алиасы:

```php
class LoginRepository extends ActiveArRepository {
	...
	public function fieldAlias() {
		return [
			'name' => 'username',
			'token' => 'auth_key',
			'creation_date' => 'created_at',
		];
	}
	...
}
```

Теперь чтение и модификация полей будет работать прозрачно.

## Связи

### Общие моменты

Хранилище поддерживает установку всязей между другими хранилищами.

Есть 3 типа связи:

* к одному
* ко многим
* многие ко многим

Связи работают прозрачно даже между хранилищами с разными драйверами.

> Note: Для хранения данных загруженной связи, объявляйте поле сущности.

### Конфигурация

Для работы со связями, хранилище должно реализовывать интерфейс `ReadInterface` 
или должно быть унаследовано от базового класса хранилища с префиксом `Active`.

> Note: Связи можно хранить в классе [Схема](https://github.com/yii2lab/yii2-domain/blob/master/guide/ru/schema.md).

Пример конфигурации:

```php
class RegionRepository extends ActiveArRepository {
	
	...
	
	public function relations() {
		return [
			'cities' => [
				'type' => RelationEnum::MANY,
				'field' => 'id',
				'foreign' => [
					'id' => 'geo.city',
					'field' => 'region_id',
				],
			],
			'country' => [
				'type' => RelationEnum::ONE,
				'field' => 'country_id',
				'foreign' => [
					'id' => 'geo.country',
					'field' => 'id',
				],
			],
		];
	}
	
	...
	
}
```

Параметры:

* `type` - тип связи (ко многим или к одному)
* `field` - имя поля в сущности текущего репозитория
* `foreign` - параметры связи с другим хранилищем
	* `id` - идентификатор (формат: `домен.хранилище`)
	* `field` - имя поля в сущности подтягиваемого репозитория

Если не указан параметр `foreign.field`, то по умолчанию он будет равен 'id'.

Можно сделать связь многие ко многим:

```php
class ArticleRepository extends ActiveArRepository {
	
	public function tableName()
	{
		return 'article';
	}
	
	public function relations() {
		return [
			'categories' => [
				'type' => RelationEnum::MANY_TO_MANY,
				'via' => [
					'id' => 'article.categories',
					'this' => 'article',
					'foreign' => 'category',
				],
			],
		];
	}
	
}
```

Параметры:

* `type` - тип связи (ко многим или к одному)
* `via` - параметры связи с промежуточным хранилищем
	* `id` - идентификатор промежуточного хранилища (формат: `домен.хранилище`)
	* `this` - промежуточное имя реляции для связи с текущим репозиторием
	* `foreign` - промежуточное имя реляции для связи целевым репозиторием

>Note: Обратите внимание: в параметрах `this` и `foreign` указывается не имя поля, а имя реляции, которое описано в промежуточном репозитории

При этом, в промежуточном репозитории должны быть объявлены связи текущий и целевой репозиторий:

```php
class CategoriesRepository extends ActiveArRepository {

	public function tableName()
	{
		return 'article_categories';
	}
	
	public function relations() {
		return [
			'article' => [
				'type' => RelationEnum::ONE,
				'field' => 'article_id',
				'foreign' => [
					'id' => 'article.article',
					'field' => 'id',
				],
			],
			'category' => [
				'type' => RelationEnum::ONE,
				'field' => 'category_id',
				'foreign' => [
					'id' => 'article.category',
					'field' => 'id',
				],
			],
		];
	}
}
```

### Пример кода

```php
$query = Query::forge();
$query->with('country');
$query->with('region');
$cityEntity = Yii::$app->geo->city->oneById(2000, $query);
```

получаем:

```php
[
	'id' => '2000',
	'country_id' => '1894',
	'region_id' => '1994',
	'name' => 'Караганда',
	'country' => [
		'id' => '1894',
		'name' => 'Казахстан',
		'currency' => null,
	],
	'region' => [
		'id' => '1994',
		'country_id' => '1894',
		'name' => 'Карагандинская обл.',
		'country' => null,
		'cities' => null,
	],
]
```

### Вложенные связи

Для работы вложенных связей, необходимо объявить конфигурацию связей во всех хранилищах, через которые будут тянуться вложенные связи.

Связи могут быть любой глубины.

Вложенные связи можно получить таким образом:

```php
$query = Query::forge();
$query->with('country.currency');
$query->with('region');
$cityEntity = Yii::$app->geo->city->oneById(2000, $query);
```

на этот запрос получаем такой ответ:

```php
[
	'id' => '2000',
	'country_id' => '1894',
	'region_id' => '1994',
	'name' => 'Караганда',
	'country' => [
		'id' => '1894',
		'name' => 'Казахстан',
		'currency' => [
			'id' => '1',
			'country_id' => '1894',
			'code' => 'KZT',
			'name' => 'Казахский тенге',
			'description' => null,
			'country' => null,
		],
	],
	'region' => [
		'id' => '1994',
		'country_id' => '1894',
		'name' => 'Карагандинская обл.',
		'country' => null,
		'cities' => null,
	],
]
```

Ответ приходит в виде вложенных объектов сущностей.

### Кастомизация

Если надо написать кастомный метод с поддержкой связей, мы вставляем куски кода.

До выборки вставляем:

```php
$with = RelationHelper::cleanWith($this->relations(), $query);
```

после выбокри:

```php
$entity = $this->forgeEntity($model);
if(!empty($with)) {
	$relations = $this->relations();
	$entity = RelationHelper::load($relations, $with, $entity);
}
return $entity;
```

Общий код должен получится таким:

```php
public function one(Query $query = null) {
	$query = Query::forge($query);
	$with = RelationHelper::cleanWith($this->relations(), $query);
	$model = $this->oneModel($query);
	if(empty($model)) {
		throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
	}
	$entity = $this->forgeEntity($model);
	if(!empty($with)) {
		$relations = $this->relations();
		$entity = RelationHelper::load($relations, $with, $entity);
	}
	return $entity;
}
```

Суть этих манипуляций в том, что надо отсечь параметр `with` до вызова выборки.

Так, мы не тянем связанные данные через модель, а делаем это на уровне репозитория.

На уровне CRUD-хранилищ, связи реализованы в методах `one` и `all`.

### REST API

Связи в АПИ-запросе указываются GET-параметром `expand`.

Например:

```
http://example.com/v1/city?expand=country
```

Поддерживаются вложенные связи.
Например, на такой запрос:

```
http://api.qr.yii/v4/city/2000?expand=country.currency,region
```

сервер ответит таким телом:

```json
{
    "id": 2000,
    "country_id": 1894,
    "region_id": 1994,
    "name": "Караганда",
    "country": {
        "id": 1894,
        "name": "Казахстан",
        "currency": {
            "id": 1,
            "country_id": 1894,
            "code": "KZT",
            "name": "Казахский тенге",
            "description": null,
            "country": null
        }
    },
    "region": {
        "id": 1994,
        "country_id": 1894,
        "name": "Карагандинская обл.",
        "country": null,
        "cities": null
    }
}
```
