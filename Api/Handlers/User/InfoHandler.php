<?php

namespace Api\Handlers\User;

use Api\Models\Decorators\UserPublicInfoDecorator;
use Api\Models\User;
use Bulletproof\Handlers\Forum\Post\Create;
use RequestHandler\Modules\Application\Application;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestHandler;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestValidator;
use RequestHandler\Modules\Middleware\IMiddleware;
use RequestHandler\Modules\Middleware\Middleware;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Response\Response;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\DataFilter\Filters\ModelFilter;
use RequestHandler\Utils\DataFilter\Filters\UIntFilter;
use RequestHandler\Utils\DataFilter\Filters\WaterfallFilter;
use RequestHandler\Utils\InputValidator\InputValidator;

class InfoHandler implements IApplicationRequestHandler, IApplicationRequestFilter, IApplicationRequestValidator, IApplicationRequestMiddleware
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handle(Request $request, Response $response): Response
    {
        /** @var array $userInfo */
        $userInfo = $request
            ->get('user_id')
            ->decorate(UserPublicInfoDecorator::class)
            ->getInfo();

        return $response->addData('user', $userInfo);
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate(['user_id' => 'required|exists:users,id']);
    }

    /**
     *
     * Request filter used to transform given fields to specified types
     *
     * @param IRequestFilter $filter
     * @return IRequestFilter
     */
    public function filter(IRequestFilter $filter): IRequestFilter
    {

        $application = Application::getSharedInstance();

        var_dump($application->getAttribute('min_title_length', Create::DEFAULT_TITLE_MIN_LENGTH, UIntFilter::getSharedInstance()));

        $userFilter = new WaterfallFilter([IntFilter::getSharedInstance(), ModelFilter::getNewInstance(User::class)]);

        return $filter->add('user_id', $userFilter);
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware
    {

        return $middleware->add(new class implements IMiddleware {

            /**
             *
             * Execute method for current middleware
             *
             * @param Request $request
             * @param Response $response
             * @param Middleware $middleware Used to call "next" method
             */
            public function run(Request $request, Response $response, Middleware $middleware): void
            {

                Application::getSharedInstance()->setAttribute('min_title_length', '-2');
                $middleware->next();

            }
        });
    }
}