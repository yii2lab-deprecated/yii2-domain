Сущность
========

Сущность - это структура данных в виде объекта со свойствами и методами.
Сущность можно выбрать из хранилища, изменить ее свойства 
и передать хранилищу для сохранения или удаления.
По умолчанию, в сущности можно читать и изменять любой атрибут.
Так же можно сделать атрибут только для чтения или только для записи, 
есть возможность генерировать виртуальный атрибут.

## Цель

* Обеспечить структуру данных в виде объекта.
* Предоставить методы для манипуляции с атрибутами.
* Абстрагироваться от формата хранения.
* скрыть некоторые данные
* валидировать данные

## Особенности

* Хранилище - это контейнер для данных.
* Сущность может содержать вложенную сущность или коллекцию сущностей

## Объявление класса

```php
class LoginEntity extends BaseEntity implements IdentityInterface {

	protected $id;
	protected $login;
	protected $email;
	protected $subject_type = 3000;
	protected $token;
	protected $parent_id;
	protected $iin_fixed = false;
	protected $creation_date;
	protected $password_hash;
	protected $roles;
	protected $profile;
	protected $balance;
	protected $address;
	
	public function getUsername() {
		return LoginHelper::format($this->login);
	}

	public function fieldType() {
		return [
			'id' => 'integer',
			'parent_id' => 'integer',
			'subject_type' => 'integer',
			'balance' => [
				'type' => BalanceEntity::class,
			],
			'address' => [
				'type' => AddressEntity::class,
			],
			'profile' => [
				'type' => ProfileEntity::class,
			],
		];
	}
	
}
```

В методе fieldType указываем типы полей.

При указании типа, поле будет принудительно приведено к типу при записи.

При указании класса сущности, по итогу, 
получаем в поле созданный объект вложенной сущности (или коллекцию сущностей).

Можем создавать свои геттеры и сеттеры.
В данной сущности, есть магическое свойство username, 
которое возвращает отформатированный вид логина.

## Создание экземпляра

Создание сущности из любой точки приложения:

```php
$entity = Yii::$app->account->factory->entity->create('login', $data);
```

Создание сущности в любом классе домена:

```php
$entity = $this->domain->factory->entity->create('login', $data);
```

Создание сущности внутри репозитория:

```php
$entity = $this->forgeEntity($data);
```

вторым праметром можем передать имя класса сущности.

## Валидация

Правила валидации описывается в методе:

```php
public function rules() {
    return [
        [['title', 'content', 'name'], 'trim'],
        [['title', 'content', 'name'], 'required'],
    ];
}
```

Вызов валидации:

```php
$entity->validate();
```

При неудачной валидации вываливается исключение UnprocessableEntityHttpException.
В этом исключении содержится массив ошибок валидации.

Поймать список ошибок можно так:

```php
try {
    $entity->validate();
} catch(UnprocessableEntityHttpException $e) {
    $errors = $e->getErrors();
}
```

## Атрибуты

### Обычные

Для атрибутов можно создавать свои геттеры и сеттеры.

```php
public function getId() {
	return intval($this->id);
}

public function setId($value) {
	$this->id = intval($value);
}
```

или объявить тип аттрибута:

```php
public function fieldType() {
    return [
        'id' => 'integer',
        'parent_id' => 'integer',
        'subject_type' => 'integer',
        'balance' => [
            'type' => BalanceEntity::class,
        ],
        'address' => [
            'type' => AddressEntity::class,
        ],
        'profile' => [
            'type' => ProfileEntity::class,
        ],
    ];
}
```

При объявлении типа или класса атрибута, этот атрибут сам конвертируется в нужный тип.

Если надо объявить коллекцию сущностей, то добавляем параметр `isCollection`

```php
public function fieldType() {
    return [
        'fields' => [
            'type' => FieldEntity::class,
            'isCollection' => true,
        ],
    ];
}
```

Если надо скрывать поле, когда в нем пустое значение, добавляем параметр `isHideIfNull`:

```php
public function fieldType() {
    return [
        'fields' => [
            'type' => FieldEntity::class,
            'isHideIfNull' => true,
        ],
    ];
}
```

### Виртуальные

Если требуется создать атрибут только для чтения и проведения дополнительных вычислений, 
то можно объявить его как виртуальный.

Виртуальным считается атрибут, если в полях класса сущности его нет, но есть геттер.

Виртуальный атрибут берет данные из существующих атрибутов, производит вычисления и дает результат.

Например:

```php
class ServiceEntity extends BaseEntity {

	protected $id;
	protected $name;
	protected $parent_id;
	protected $title;
	protected $description;
	protected $picture;
	protected $synonyms;
	protected $fields;
	protected $categories;
	protected $merchant;
	protected $is_simple = true;
	
	...
	
	public function getPictureUrl() {
		if(empty($this->picture)) {
			return null;
		}
		/** todo: make mock for summary */
		return Yii::$app->summary->summary->url['service_pictures'] . '/' . $this->getPicture();
	}

	public function fields() {
		$fields = parent::fields();
		$fields['picture_url'] = 'picture_url';
		return $fields;
	}

}
```

Обращение к виртуальному атрибуту происходит также, как и к обычному:

```php
$serviceEntity->pictureUrl;
```

или

```php
$serviceEntity->getPictureUrl();
```

## Фиксированный список значений

Если нужен фиксированный список значений, то можно сделать так

```php
class ConnectionEntity extends BaseEntity {

	const DRIVER_MYSQL = 'mysql';
	const DRIVER_PGSQL = 'pgsql';

	protected $driver;
	protected $host;
	protected $username;
	protected $password;
	protected $dbname;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$drivers = $this->getConstantEnum('driver');
		return [
			[['driver', 'host', 'username', 'dbname'], 'required'],
			['driver', 'in', 'range' => $drivers],
		];
	}
}
```

* Указываем список констант с префиксом
* добавляем валидацию по списку

Теперь можно в клиентском коде использовать эти константы

```php
<?= $form->field($model, 'driver')->dropDownList([
	ConnectionEntity::DRIVER_MYSQL => Yii::t('app/connection', 'driver_mysql'),
	ConnectionEntity::DRIVER_PGSQL => Yii::t('app/connection', 'driver_pgsql'),
]); ?>
```

Можете использовать [Enum-классы](https://github.com/yii2lab/yii2-misc/blob/master/guide/ru/enum-base.md)

```php
class DbDriverEnum extends BaseEnum {

	const MYSQL = 'mysql';
	const PGSQL = 'pgsql';

}
```

```php
class ConnectionEntity extends BaseEntity {

	protected $driver;
	protected $host;
	protected $username;
	protected $password;
	protected $dbname;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$drivers = DbDriverEnum::values();
		return [
			[['driver', 'host', 'username', 'dbname'], 'required'],
			['driver', 'in', 'range' => $drivers],
		];
	}
}
```

## Методы

* fieldType
* extraFields
* rules
* hideIfNullFields
* primaryKey
* fields
* validate
* getConstantEnum
* getPrimaryKey
* toArray
* load
* attributes


