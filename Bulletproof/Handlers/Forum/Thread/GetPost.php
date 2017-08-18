<?php

namespace Bulletproof\Handlers\Forum\Post;


use Bulletproof\Models\PostModel;
use RequestHandler\Modules\Application\ApplicationRequest\IFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use RequestHandler\Modules\Application\ApplicationRequest\IMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IValidate;
use RequestHandler\Modules\Middleware\IMiddlewareContainer;
use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Response\IResponse;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\DataFilter\Filters\ModelFilter;
use RequestHandler\Utils\DataFilter\Filters\WaterfallFilter;

use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

class GetPost implements IHandle, IValidate, IFilter, IMiddleware
{

    /**
     *
     * Request filter used to transform given fields to specified types
     *
     * @param IRequestFilter $filter
     * @return IRequestFilter
     */
    public function filter(IRequestFilter $filter): IRequestFilter
    {

        $postFilter = new WaterfallFilter([
            SingletonFactory::getSharedInstance(IntFilter::class),
            SingletonFactory::getSharedInstanceArgs(ModelFilter::class, [PostModel::class, 'id'])
        ]);

        return $filter->add('post_id', $postFilter);
    }

    /**
     *
     * Executes when related action is requested
     *
     * @param IRequest $request
     * @param IResponse $response
     * @return IResponse
     */
    public function handle(IRequest $request, IResponse $response): IResponse
    {
        $request->get('post_id');

        return $response;
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param IMiddlewareContainer $middleware
     * @return IMiddlewareContainer
     */
    public function middleware(IMiddlewareContainer $middleware): IMiddlewareContainer
    {
        return $middleware;
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param IInputValidator $validator
     * @return IInputValidator
     */
    public function validate(IInputValidator $validator): IInputValidator
    {
        return $validator->validate([
            'scope' => 'required|enum:public,private,contact'
        ]);
    }
}