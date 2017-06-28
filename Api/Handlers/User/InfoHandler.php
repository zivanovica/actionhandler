<?php

namespace Api\Handlers\User;

use Api\Models\User;
use Core\CoreUtils\DataFilter\Filters\ModelFilter;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestFilter;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request\IRequestFilter;
use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

class InfoHandler implements IApplicationRequestHandler, IApplicationRequestFilter, IApplicationRequestValidator
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

        // $request->get('id') will return instance of User model
        // as filter for that field is defined in "filter" method of this class
        return $response->addData('user', $request->get('id'));
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

        return $validator->validate(['id' => 'required|exists:users,id']);
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

        return $filter->add('id', ModelFilter::getNewInstance(User::class));
    }
}