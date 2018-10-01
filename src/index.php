<?php

use RequestHandler\Modules\Application\Application;
use RequestHandler\Modules\Application\IApplication;
use RequestHandler\Modules\Database\Database;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Modules\Event\Dispatcher;
use RequestHandler\Modules\Event\IDispatcher;
use RequestHandler\Modules\Middleware\IMiddlewareContainer;
use RequestHandler\Modules\Middleware\MiddlewareContainer;
use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Request\RequestFilter\RequestFilter;
use RequestHandler\Modules\Response\IResponse;
use RequestHandler\Modules\Response\Response;
use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Modules\Router\Router;
use RequestHandler\Modules\Event\Event;
use RequestHandler\Modules\Event\IEvent;
use RequestHandler\Modules\Entity\IRepository;
use RequestHandler\Modules\Entity\Repository;
use RequestHandler\Utils\DataFilter\Filters\BoolFilter;
use RequestHandler\Utils\DataFilter\Filters\EmailFilter;
use RequestHandler\Utils\DataFilter\Filters\EntityModelFilter;
use RequestHandler\Utils\DataFilter\Filters\FloatFilter;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\DataFilter\Filters\StringFilter;
use RequestHandler\Utils\DataFilter\Filters\UIntFilter;
use RequestHandler\Utils\DataFilter\Filters\WaterfallFilter;
use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Utils\InputValidator\Rules\RuleEmail;
use RequestHandler\Utils\InputValidator\Rules\RuleEntityExists;
use RequestHandler\Utils\InputValidator\Rules\RuleEnum;
use RequestHandler\Utils\InputValidator\Rules\RuleEqual;
use RequestHandler\Utils\InputValidator\Rules\RuleFieldSameAsOther;
use RequestHandler\Utils\InputValidator\Rules\RuleMaximumLength;
use RequestHandler\Utils\InputValidator\Rules\RuleMayNotExists;
use RequestHandler\Utils\InputValidator\Rules\RuleMinimumLength;
use RequestHandler\Utils\InputValidator\Rules\RuleRequired;
use RequestHandler\Utils\InputValidator\Rules\RuleUniqueEntity;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

use RequestHandler\Utils\QueryBuilder\Builder;
use RequestHandler\Utils\QueryBuilder\IBuilder;

/**
 * TODO:
 *   - Implement Query Builder
 *   - Implement PSR-7 Http Messaging
 *   - Implement database transactions
 */

ObjectFactory::set([
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
    IBuilder::class => Builder::class,
    IInputValidator::class => InputValidator::class,
    BoolFilter::class => BoolFilter::class,
    EmailFilter::class => EmailFilter::class,
    IntFilter::class => IntFilter::class,
    FloatFilter::class => FloatFilter::class,
    EntityModelFilter::class => EntityModelFilter::class,
    StringFilter::class => StringFilter::class,
    UIntFilter::class => UIntFilter::class,
    WaterfallFilter::class => WaterfallFilter::class,
]);

/** @var IInputValidator $inputValidator */
$inputValidator = ObjectFactory::create(IInputValidator::class);

$inputValidator->addRules([
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