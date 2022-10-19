<?php


namespace XValidated\Annotation;

use XValidated\RuleCollector;
use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Rule extends AbstractAnnotation
{
    /**
     * 验证器的属性
     * @var array
     */
    public array $values;

    /**
     * 别名
     * @var string
     */
    public string $alias;


    /**
     * 消息
     * @var array
     */
    public array $messages;


    /**
     * collectProperty
     * @param string $className
     * @param string|null $target
     * date:2022/10/18
     * time:19:34
     * auth：xyc
     */
    public function collectProperty(string $className, ?string $target): void
    {
        RuleCollector::collectProperty($className, $target, static::class, $this);
    }


}