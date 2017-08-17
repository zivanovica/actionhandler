<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 7/3/17
 * Time: 7:02 PM
 */

namespace Api\Handlers\Entity;

use Api\Filters\UniqueEntitiesFilter;
use Api\Middlewares\AuthenticateMiddleware;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestHandler;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestValidator;
use RequestHandler\Modules\Middleware\Middleware;
use RequestHandler\Modules\Model\Model;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\Response;

class GetHandler implements IApplicationRequestHandler, IApplicationRequestMiddleware, IApplicationRequestValidator, IApplicationRequestFilter
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

        $entities = $request->get('id');

        $data = [];

        /** @var Model $entity */
        foreach ($entities as $entity) {

            $class = get_class($entity);

            if (false === isset($data[$class])) {

                $data[$class] = [];
            }

            $data[$class][] = $entity->toArray();
        }

        return $response->data($entities);
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

        return $middleware->add(new AuthenticateMiddleware());
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate(['id' => 'required|exists:unique,id,-']);
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

        return $filter->add('id', UniqueEntitiesFilter::getSharedInstance());
    }
}