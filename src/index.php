<?php

use \RequestHandler\Utils\SingletonFactory\SingletonFactory;

SingletonFactory::setMap([
    \RequestHandler\Modules\Application\IApplication::class => \RequestHandler\Modules\Application\Application::class,
    \RequestHandler\Modules\Database\IDatabase::class => \RequestHandler\Modules\Database\Database::class,
    \RequestHandler\Modules\Request\IRequest::class => \RequestHandler\Modules\Request\Request::class,
    \RequestHandler\Modules\Response\IResponse::class => \RequestHandler\Modules\Response\Response::class,
    \RequestHandler\Modules\Router\IRouter::class => \RequestHandler\Modules\Router\Router::class,
    \RequestHandler\Utils\InputValidator\IInputValidator::class => \RequestHandler\Utils\InputValidator\InputValidator::class,
    \RequestHandler\Modules\Middleware\IMiddlewareContainer::class => \RequestHandler\Modules\Middleware\MiddlewareContainer::class,
    \RequestHandler\Modules\Request\RequestFilter\IRequestFilter::class => \RequestHandler\Modules\Request\RequestFilter\RequestFilter::class,

    \RequestHandler\Utils\DataFilter\Filters\BoolFilter::class => \RequestHandler\Utils\DataFilter\Filters\BoolFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\EmailFilter::class => \RequestHandler\Utils\DataFilter\Filters\EmailFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\IntFilter::class => \RequestHandler\Utils\DataFilter\Filters\IntFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\FloatFilter::class => \RequestHandler\Utils\DataFilter\Filters\FloatFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\ModelFilter::class => \RequestHandler\Utils\DataFilter\Filters\ModelFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\StringFilter::class => \RequestHandler\Utils\DataFilter\Filters\StringFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\UIntFilter::class => \RequestHandler\Utils\DataFilter\Filters\UIntFilter::class,
    \RequestHandler\Utils\DataFilter\Filters\WaterfallFilter::class => \RequestHandler\Utils\DataFilter\Filters\WaterfallFilter::class
]);

/** @var \RequestHandler\Utils\InputValidator\IInputValidator $inputValidator */
$inputValidator = SingletonFactory::getSharedInstance(\RequestHandler\Utils\InputValidator\IInputValidator::class);
$inputValidator->addRules([
    new \RequestHandler\Utils\InputValidator\Rules\RuleEmail($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleEntityExists($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleEnum($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleEqual($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleFieldSameAsOther($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleMaximumLength($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleMinimumLength($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleMayNotExists($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleRequired($inputValidator),
    new \RequestHandler\Utils\InputValidator\Rules\RuleUniqueEntity($inputValidator)
]);