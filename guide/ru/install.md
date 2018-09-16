Установка
===

Устанавливаем зависимость:

```
composer require yii2lab/yii2-domain
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
