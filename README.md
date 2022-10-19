# x-validated

简易版注解验证库 for Hyperf， 基于 hyperf/validation

## 安装

~~~shell script
composer require xu767142206/hyperf-easywechat
~~~

## 配置

1. config/autoload/annotations.php

```php
return [
    'scan' => [
        //...
        'collectors' => [
            XValidated\RuleCollector::class,
        ],
    ]

];
```

2. config/autoload/aspects.php
```php
return [
    //....
    \XValidated\Aspect\ValidatedAspect::class
    //....
];
```
3.config/autoload/exceptions.php
```php
return [
    'handler' => [
        'http' => [
            //....
            Hyperf\Validation\ValidationExceptionHandler::class,
            //....
        ],
    ],
];
```
## 使用

待完善

## License

MIT

