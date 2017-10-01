<?php

use RequestHandler\Modules\{
    Application\Application, Application\IApplication, Database\Database, Database\IDatabase, Middleware\IMiddlewareContainer, Middleware\MiddlewareContainer, Request\IRequest, Request\Request, Request\RequestFilter\IRequestFilter, Request\RequestFilter\RequestFilter, Response\IResponse, Response\Response, Router\IRouter, Router\Router
};
use RequestHandler\Utils\{
    DataFilter\Filters\BoolFilter, DataFilter\Filters\EmailFilter, DataFilter\Filters\FloatFilter, DataFilter\Filters\IntFilter, DataFilter\Filters\ModelFilter, DataFilter\Filters\StringFilter, DataFilter\Filters\UIntFilter, DataFilter\Filters\WaterfallFilter, InputValidator\IInputValidator, InputValidator\InputValidator
};
use RequestHandler\Utils\InputValidator\Rules\{
    RuleEmail, RuleEntityExists, RuleEnum, RuleEqual, RuleFieldSameAsOther, RuleMaximumLength, RuleMayNotExists, RuleMinimumLength, RuleRequired, RuleUniqueEntity
};
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

ObjectFactory::setMap([
    IApplication::class => Application::class,
    IDatabase::class => Database::class,
    IRequest::class => Request::class,
    IRequestFilter::class => RequestFilter::class,
    IResponse::class => Response::class,
    IRouter::class => Router::class,
    IMiddlewareContainer::class => MiddlewareContainer::class,
    IInputValidator::class => InputValidator::class,
    BoolFilter::class => BoolFilter::class,
    EmailFilter::class => EmailFilter::class,
    IntFilter::class => IntFilter::class,
    FloatFilter::class => FloatFilter::class,
    ModelFilter::class => ModelFilter::class,
    StringFilter::class => StringFilter::class,
    UIntFilter::class => UIntFilter::class,
    WaterfallFilter::class => WaterfallFilter::class
]);

ObjectFactory::create(\RequestHandler\Utils\InputValidator\IInputValidator::class)
    ->addRules([
        new RuleEmail(),
        new RuleEntityExists(),
        new RuleEnum(),
        new RuleEqual(),
        new RuleFieldSameAsOther(),
        new RuleMaximumLength(),
        new RuleMinimumLength(),
        new RuleMayNotExists(),
        new RuleRequired(),
        new RuleUniqueEntity()
    ]);