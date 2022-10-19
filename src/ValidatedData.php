<?php


namespace XValidated;


use XValidated\Annotation\Rule;
use Hyperf\Contract\Arrayable;

abstract class ValidatedData implements Arrayable
{
    /**
     * 规则
     * @var array[]
     */
    private static array $rule = [];


    /**
     * 映射关系
     * @var array
     */
    private static array $bindMap = [];


    public function __construct()
    {
        self::$rule = $this->initRule();
        self::$bindMap = $this->initBindMap();
    }

    /**
     * @return array
     */
    public function getRule(): array
    {
        return self::$rule;
    }


    private function initRule(): array
    {
        $rulus = [];
        $messages = [];
        $arrayAccess = RuleCollector::getClassPropertys(static::class) ?? [];

        foreach ($arrayAccess as $name => $access) {

            $type = match ($access['type']) {
                "string", "String" => "string",
                "int", "Integer" => "integer",
                "double", "Double", "float", "Float" => "numeric",
                "bool", "Boolean" => "boolean",
                "array", "Array" => "array",
                "Resource", => "file",
                default => ''
            };


            if (!isset($access[Rule::class])) continue;

            /** @var $rule Rule */
            $rule = $access[Rule::class];
            $ruleKey = $rule->alias ?? $name;

            /** 验证规则 */
            $rulus[$ruleKey] = $rule->values ?? [];
            !empty($type) && $rulus[$ruleKey][] = $type;

            if (empty($rulus[$ruleKey]))
                unset($rulus[$ruleKey]);

            /** 自定义错误消息 */
            foreach ($rule->messages ?? [] as $key => $message) {
                $messages[$ruleKey . '.' . $key] = $message;
            }

        }
        return [$rulus, $messages];
    }


    private function initBindMap(): array
    {
        $bindData = [];

        foreach (RuleCollector::getClassPropertys(static::class) ?? [] as $name => $access) {

            if (!isset($access[Rule::class])) continue;

            /** @var $rule Rule */
            $rule = $access[Rule::class];
            $ruleKey = $rule->alias ?? $name;

            $bindData[$ruleKey]['name'] = $name;
            $bindData[$ruleKey]['type'] = $access['type'];

        }
        return $bindData;
    }


    public function copy(array $data): static
    {
        $obj = clone $this;
        foreach ($data as $key => $val) {
            if (!isset(self::$bindMap[$key])) continue;

            /** 验证type */


            $obj->{self::$bindMap[$key]['name']} = $val;

        }

        return $obj;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}