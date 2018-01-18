Запрос
===

## Цель

* абстрагировать параметры выборки
* прозрачно задавать параметры запроса для всех драйверов хранилищ

## Особенности

* используется для передачи параметров запроса между слоями
* инкапсулирует параметры запроса
* предоставляет методы для чтения и записи параметров
* список методов, которые идентичны [[yii\db\QueryTrait]]:
	* limit
	* offset
	* orderBy
	* addOrderBy
	* with
	* where

## Пример кода

Назначить параметры запроса и передать хранилищу:

```php
$query = new Query;
$query->select(['id', 'name', 'country']);
$query->where('country_id', 2);
$query->limit(20);
$query->offset(40);
$query->orderBy([ 'name' => SORT_ASC]);
$query->with('country');
$all = Yii::$app->geo->repository->city->all($query);
```

## Набор полей

```php
$query->select(['id', 'name']);
```

```php
$query->select('id']);
$query->select('name');
```

## Ограничение выборки

```php
$query->limit(15);
```

```php
$query->offset(30);
```

```php
$query->page(2);
```

```php
$query->perPage(15);
```

## Сортировка

Метод `orderBy` записывает новое значение, затирая старое.

```php
$query->orderBy('name');
```

```php
$query->orderBy('id ASC, name DESC');
```

```php
$query->orderBy(['id' => SORT_ASC, 'name' => SORT_DESC]);
```

Аналогичным образом работает метод `addOrderBy`, только старые значения он не затирает:

```php
$query->addOrderBy('name');
```

```php
$query->addOrderBy('id ASC, name DESC');
```

```php
$query->addOrderBy(['id' => SORT_ASC, 'name' => SORT_DESC]);
```

## Связи

```php
$query->with('country');
$query->with('region');
```

```php
$query->with(['country', 'region']);
```

## Условия

### Формат

#### Краткий

передаем 2 параметра: имя поля и значение.

```php
$query->where('country_id', 2);
```

```php
$query->where('country_id', [2,3,4]);
```

#### Полный

передаем 1 параметр: массив полей и их значений.

```php
$query->where(['country_id' => 2]);
```

```php
$query->where(['country_id' => [2,3,4]]);
```

### Равенство

```php
$query->where('country_id', 2);
```

### Сравнения

```php
// WHERE `subtotal` > 200
$query->where(['>', 'subtotal', 200]);
```

## Методы

### toArray

Преобразовать объект запроса в массив.

### hasParam


### getParam

Получить параметр:

```php
$countryId = $query->getParam('country_id');
```

Получить параметр и привести к типу:

```php
$countryId = $query->getParam('country_id', 'integer');
```

### removeParam

### cloneForCount


### getRest


### forge

Существует метод `Query::forge()`. 
Он отдает объект запроса.
Если в параметр передан объект запроса, то возвращает этот объект обратно.
Если ничего не передано, то создает новый.
Этот метод служит для гарантированного получения объекта запроса в любом случае.
