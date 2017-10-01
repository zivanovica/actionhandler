<?php

use \RequestHandler\Utils\ObjectFactory\ObjectFactory;

use \RequestHandler\Modules\{
    Application\IApplication, Application\Application,
    Database\IDatabase, Database\Database,
    Request\IRequest, Request\Request,
    Request\RequestFilter\IRequestFilter, Request\RequestFilter\RequestFilter,
    Response\IResponse, Response\Response,
    Router\IRouter, Router\Router,
    Middleware\IMiddlewareContainer, Middleware\MiddlewareContainer
};

use \RequestHandler\Utils\{
    InputValidator\IInputValidator, InputValidator\InputValidator,
    DataFilter\Filters\FloatFilter, DataFilter\Filters\ModelFilter,
    DataFilter\Filters\BoolFilter, DataFilter\Filters\EmailFilter,
    DataFilter\Filters\IntFilter, DataFilter\Filters\StringFilter,
    DataFilter\Filters\UIntFilter, DataFilter\Filters\WaterfallFilter
};

use RequestHandler\Utils\InputValidator\Rules\{
    RuleEmail, RuleEntityExists, RuleEnum, RuleEqual,
    RuleFieldSameAsOther, RuleMaximumLength, RuleMinimumLength,
    RuleMayNotExists, RuleRequired, RuleUniqueEntity
};

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