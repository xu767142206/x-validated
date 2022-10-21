<?php


namespace XValidated;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'collectors' => [
                        RuleCollector::class,
                    ]
                ],
            ],
        ];
    }
}