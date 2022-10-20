# x-validated
简易版注解验证库 for Hyperf， 基于 hyperf/validation
>起因：一直眼馋springboot的validation库,正好php8也有了原生的注解,就想到了套娃的方式简易实现 后面有闲情了再想办法自己实现吧


## 安装

~~~shell script
composer require xu767142206/x-validated
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

dto模型的定义
> dto模型必须继承 XValidated\ValidatedData

模型的定义

```php
use XValidated\Annotation\Rule;
use XValidated\ValidatedData;

class LoginDto extends ValidatedData
{
    #[Rule(values: ["required"])]
    public int $phone;

    #[Rule(values: ["required"], alias: "pass_word", messages: ['required' => "密码不能为空"])]
    public string $password;
}
```

> rule规则可以查看 hyperf/validation库的验证规则 [验证规则](https://hyperf.wiki/3.0/#/zh-cn/validation?id=%e9%aa%8c%e8%af%81%e8%a7%84%e5%88%99)

然后创建控制器 `IndexController`：

```php
use XValidated\Annotation\Validated;
use App\Model\LoginDto;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

#[AutoController]
class IndexController extends AbstractController
{
    #[RequestMapping(path: "index", methods: "get,post")]
    #[Validated]
    public function index(RequestInterface $request, LoginDto $loginDto)
    {
        var_dump($loginDto->toArray());
        return [
            'param' => $request->getServerParams(),
        ];
    }
}
```
接着可以快乐的写代码了
## License

MIT

