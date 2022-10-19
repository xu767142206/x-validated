<?php


namespace XValidated\Aspect;

use XValidated\Annotation\Validated;
use XValidated\ValidatedData;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\ValidationException;

#[Aspect]
class ValidatedAspect extends AbstractAspect
{
    // 要切入的类或 Trait，可以多个，亦可通过 :: 标识到具体的某个方法，通过 * 可以模糊匹配
    public array $classes = [

    ];

    // 要切入的注解，具体切入的还是使用了这些注解的类，仅可切入类注解和类方法注解
    public array $annotations = [
        Validated::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        foreach ($proceedingJoinPoint->arguments['keys'] as $key => $argument) {
            if ($argument instanceof ValidatedData) {

                [$rules, $messages] = $argument->getRule();

                $container = \Hyperf\Utils\ApplicationContext::getContainer();

                $request = $container->get(RequestInterface::class);
                $validationFactory = $container->get(ValidatorFactoryInterface::class);

                $validator = $validationFactory->make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }

                //填充数据
                $newObjet = $argument->copy($request->all());
                $proceedingJoinPoint->arguments['keys'][$key] = $newObjet;
            }
        }

        $result = $proceedingJoinPoint->process();
        // 在调用后进行某些处理
        return $result;
    }
}