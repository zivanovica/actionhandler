<?php

namespace Api\Handlers\Idea;

use Api\Filters\UniqueEntityFilter;
use Api\Models\Idea;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestHandler;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestValidator;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\Response;

class GetHandler implements IApplicationRequestHandler, IApplicationRequestValidator, IApplicationRequestFilter
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

        /** @var Idea $idea */
        $idea = $request->get('id');

        return $response->data([
            'idea' => $idea,
            'unique_id' => $idea->getUniqueId(),
            'unique_id2' => $idea->getUniqueId()
        ]);
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate([
            'id' => 'required|exists:unique,id'
        ]);
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

        return $filter->add('id', UniqueEntityFilter::getSharedInstance());
    }
}