<?php

use RequestHandler\Modules\{
    Application\Application, Application\IApplication, Database\Database, Database\IDatabase,
    Middleware\IMiddlewareContainer, Middleware\MiddlewareContainer, Request\IRequest, Request\Request,
    Request\RequestFilter\IRequestFilter, Request\RequestFilter\RequestFilter, Response\IResponse, Response\Response,
    Router\IRouter, Router\Router, Event\IDispatcher, Event\Dispatcher
};
use RequestHandler\Utils\{
    DataFilter\Filters\BoolFilter, DataFilter\Filters\EmailFilter, DataFilter\Filters\FloatFilter, DataFilter\Filters\IntFilter, DataFilter\Filters\EntityModelFilter, DataFilter\Filters\StringFilter, DataFilter\Filters\UIntFilter, DataFilter\Filters\WaterfallFilter, InputValidator\IInputValidator, InputValidator\InputValidator
};
use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use RequestHandler\Utils\InputValidator\Rules\{
    RuleEmail, RuleEntityExists, RuleEnum, RuleEqual, RuleFieldSameAsOther, RuleMaximumLength, RuleMayNotExists, RuleMinimumLength, RuleRequired, RuleUniqueEntity
};

use RequestHandler\Modules\Event\{Event, IEvent};

use RequestHandler\Modules\Model\{Repository, IRepository};


/**
 * TODO:
 *   - Implement Query Builder
 *   - Implement PSR-7 Http Messaging
 *   - Implement database transactions
 */

$interfaceMap = [
    IApplication::class => Application::class,
    IDatabase::class => Database::class,
    IRequest::class => Request::class,
    IRequestFilter::class => RequestFilter::class,
    IResponse::class => Response::class,
    IRouter::class => Router::class,
    IDispatcher::class => Dispatcher::class,
    IEvent::class => Event::class,
    IMiddlewareContainer::class => MiddlewareContainer::class,
    IRepository::class => Repository::class,
    IInputValidator::class => InputValidator::class,
    BoolFilter::class => BoolFilter::class,
    EmailFilter::class => EmailFilter::class,
    IntFilter::class => IntFilter::class,
    FloatFilter::class => FloatFilter::class,
    EntityModelFilter::class => EntityModelFilter::class,
    StringFilter::class => StringFilter::class,
    UIntFilter::class => UIntFilter::class,
    WaterfallFilter::class => WaterfallFilter::class,
];

foreach ($interfaceMap as $interface => $class) {

    ObjectFactory::register($interface, $class);
}

ObjectFactory::create(IInputValidator::class)->addRules([
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