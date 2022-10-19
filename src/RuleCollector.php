<?php


namespace XValidated;


use Hyperf\Di\MetadataCollector;

class RuleCollector extends MetadataCollector
{
    protected static array $container = [];

    public static function collectProperty(string $class, string $property, string $annotation, $value): void
    {
        static::$container[$class]['_p'][$property][$annotation] = $value;
        //增加类型
        if (!isset(static::$container[$class]['_p'][$property]['type'])) {
            static::setValueType($class);
        }
    }

    public static function clear(?string $key = null): void
    {
        if ($key) {
            unset(static::$container[$key]);
        } else {
            static::$container = [];
        }
    }

    public static function getPropertiesByAnnotation(string $annotation): array
    {
        $properties = [];
        foreach (static::$container as $class => $metadata) {
            foreach ($metadata['_p'] ?? [] as $property => $_metadata) {
                if ($value = $_metadata[$annotation] ?? null) {
                    $properties[] = ['class' => $class, 'property' => $property, 'annotation' => $value];
                }
            }
        }
        return $properties;
    }


    /**
     * 设置类型规则
     * setRule
     * @param string $class
     * @throws \ReflectionException
     * date:2022/10/19
     * time:9:20
     * auth：xyc
     */
    private static function setValueType(string $class): void
    {
        $reflection = new \ReflectionClass($class);
        foreach ($reflection->getProperties() as $propertyObjet) {
            $valueType = $propertyObjet->getType()->getName();
            static::$container[$class]['_p'][$propertyObjet->name]["type"] = $valueType;
        }
    }


    public static function getClassPropertyAnnotation(string $class, string $property)
    {
        return static::get($class . '._p.' . $property);
    }

    public static function getClassPropertys(string $class)
    {
        return static::get($class . '._p');
    }


    public static function getContainer(): array
    {
        return static::$container;
    }
}