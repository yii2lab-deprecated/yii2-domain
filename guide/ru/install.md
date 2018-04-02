Установка
===

Устанавливаем зависимость:

```
composer require yii2lab/yii2-domain
```

Переопределяем класс Yii в конфиге `common/config/env-local.php`:

```php
return [
	...
	'yii' => [
		'class' => VENDOR_DIR . DS . 'yii2lab/yii2-domain/src/yii2' . DS . 'Yii.php',
	],
	...
];
```

Переопределение нужно для объявления статической переменной `$domain`.

Еще надо добавить фильтры:

```php
return [
	...
	'config' => [
		'filters' => [
			...
			[
				'class' => LoadDomainConfig::class,
				'app' => COMMON,
				'name' => 'domains',
				'withLocal' => true,
			],
			'yii2lab\domain\filters\SetDomainTranslationConfig',
			'yii2lab\domain\filters\DefineDomainLocator',
			...
		],
	],
];
```
