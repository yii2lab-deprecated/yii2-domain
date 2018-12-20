Поведение
===

## Цель

* переиспользование кода
* выразительность кода

## Особенности

* можно вынести часть логики в отдельные классы
* можно подключать поведения динамически

## Применение

На данный момент, можно применять поведения для модификации запросов (query) и для модификации сущностей (entity).

## Пример модификации запроса

Например, для посетителей надо выдавать категории, у которых статус равен `1`.

Мы могли бы модифицировать каждый метод сервиса и добавлять условие.

Но более правильным, будет создать класс поведения для модификации запроса:

```php
use yii2lab\domain\behaviors\query\BaseQueryFilter;
use yii2lab\domain\data\Query;

class StatusBehavior extends BaseQueryFilter {

    public $value = null;

    public function prepareQuery(Query $query) {
        $query->where(['status' => $this->value]);
    }

}
```

а в сервисе подключаем класс поведения:

```php
class CategoryService extends BaseActiveService implements CategoryInterface {

    public function behaviors()
    {
        return [
            [
                'class' => StatusBehavior::class,
                'value' => StatusEnum::ENABLE,
            ],
        ];
    }

}
```

## Пример модификации сущности

Допустим, нам необходимо ограничить набор полей в отдаваемой сущности.

Создаем поведение:

```php
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\events\ReadEvent;

class SelectAttributeFilter extends BaseEntityFilter {
	
	public function prepareContent(BaseEntity $entity, ReadEvent $event) {
		$attributes = $event->query->getParam(Query::SELECT);
		if(empty($attributes)) {
			return;
		}
		$hideAttributes = array_diff($entity->attributes(), $attributes);
		$entity->hideAttributes($hideAttributes);
	}
	
}
```

а в репозитории подключаем класс поведения:

```php
class TransactionRepository extends BaseTpsRepository implements TransactionInterface {
	
	use ReadEventTrait;
	
	protected $schemaClass = true;
	
	public function behaviors() {
		return [
			SelectAttributeFilter::class,
		];
	}
	
}
```
