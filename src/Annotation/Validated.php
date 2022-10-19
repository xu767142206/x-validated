<?php


namespace XValidated\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_METHOD)]
class Validated extends AbstractAnnotation
{
}