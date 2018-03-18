Схема
===

## Описание

Классы схем нужны в случае, когда у нас много драйверов одного и того же хранилища, и нам требуется централизовано хранить их конфиг схемы.

На текущий момент поддерживается конфигурация:

* связи с другими хранилищами
* список уникальных полей

В будущем настроек будет больше.

## Пример

Указываем в хранилище параметр `schemaClass`:

```php
class CityRepository extends ActiveArRepository {
	
	protected $schemaClass = true;
	
	public function tableName()
	{
		return 'geo_city';
	}
	
}
```

Если параметр `schemaClass` равен `true`, то класс схемы берется по пути:
`domain/repositories/schema/{id}Schema`, где `{id}` - это `id` репозитория.
Заметьте, папка `schema` лежит в той же папке `repositories`, что и папки хранилищ.

Если в параметре `schemaClass` указанно имя класса схемы, то берется указанный класс.

Если параметр `schemaClass` пустой, то схема берется из класса хранилища.

Пример класса схемы:

```php
class CitySchema extends BaseSchema {
	
	public function uniqueFields() {
		return [
			['name'],
		];
	}
	
	public function relations() {
		return [
			'country' => [
				'type' => RelationEnum::ONE,
				'field' => 'country_id',
				'foreign' => [
					'id' => 'geo.country',
					'field' => 'id',
				],
			],
			'region' => [
				'type' => RelationEnum::ONE,
				'field' => 'region_id',
				'foreign' => [
					'id' => 'geo.region',
					'field' => 'id',
				],
			],
		];
	}
	
}
```

Методы:

* uniqueFields - список уникальных полей
* relations - связи
