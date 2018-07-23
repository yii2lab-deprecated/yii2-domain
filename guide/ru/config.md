Конфигурация
===

Для конфигурирования используется `Driver Enum`.
В нем собраны основные драйвера хранилищ.

Например:

```php
use yii2lab\domain\enums\Driver;

return [
	'components' => [
		// ...
		'lang' => [
            'class' => 'yii2lab\domain\Domain',
            'path' => 'yii2module\lang\domain',
            'repositories' => [
                'language' => Driver::DISC,
                'store' => APP == API ? Driver::HEADER : Driver::COOKIE,
            ],
            'services' => [
                'language',
            ],
        ],
		// ...
	],
];
```

Можем так же использовать метод `Driver::remote()`.
Он нужен для централизованной смены драйвера.

```php
use yii2lab\domain\enums\Driver;

return [
	'components' => [
		// ...
		'account' => [
			'class' => 'yii2lab\domain\Domain',
			'path' => 'yii2module\account\domain\v2',
			'repositories' => [
				'auth' => Driver::remote(),
				'login' => Driver::remote(),
				'registration' => Driver::remote(),
			],
			'services' => [
				'auth',
				'login',
				'registration',
			],
		],
		// ...
	],
];
```

Метод `remote` читает драйвер из конфига `common/config/env`.

```php
return [
	...
	'remote' => [
		'driver' => 'core',
	],
    ...
];
```

Например, у нас работа с пользователями может производиться на централизованном сервере.
Но может использовать БД для работы с пользователями,
тогда в конфиге `common/config/env` меняем remote-драйвер на `ar`.

Для обеспечения работы файлового драйвера, объявите в `components`:

```php
return [
	...
		'filedb' => [
			'class' => 'yii2tech\filedb\Connection',
			'path' => '@common/data',
		],
    ...
];
```
