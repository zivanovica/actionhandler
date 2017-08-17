<?php

namespace Api\Handlers\Idea;

use Api\Middlewares\AuthenticateMiddleware;
use Api\Models\Idea;
use Api\Models\Unique;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestHandler;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestValidator;
use RequestHandler\Modules\Middleware\IMiddleware;
use RequestHandler\Modules\Middleware\Middleware;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\IResponseStatus;
use RequestHandler\Modules\Response\Response;

class CreateHandler implements IApplicationRequestHandler, IApplicationRequestValidator, IApplicationRequestMiddleware
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


        $idea = Idea::getNewInstance([
            'idea_category' => $request->get('idea_category'),
            'creator_id' => $request->token()->user(),
            'description' => $request->get('description'),
            'status' => Idea::STATUS_OPEN
        ]);

        if ($idea->save()) {

            return $response
                ->status(IResponseStatus::CREATED)
                ->data([
                    'message' => 'Idea successfully created.',
                    'idea' => $idea
                ]);
        }

        return $response->status(500)->addError('message', 'Failed to create idea, please try again.');
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

        return $middleware
            ->add(new AuthenticateMiddleware())
            ->add(new class implements IMiddleware
            {

                public function run(Request $request, Response $response, Middleware $middleware): void
                {
                    $idea = Idea::getSharedInstance()->findOneWhere([
                        'creator_id' => $request->token()->user()->getAttribute('id'),
                        'status' => Idea::STATUS_OPEN
                    ]);

                    if (null === $idea) {

                        $middleware->next();

                        return;
                    }

                    $response
                        ->status(IResponseStatus::CONFLICT)
                        ->addError('idea.exists', 'Open idea already pending');
                }
            });
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate([
            'description' => 'required|min:' . Idea::MIN_DESCRIPTION_LENGTH . '|max:' . Idea::MAX_DESCRIPTION_LENGTH,
            'idea_category' => 'required|exists:idea_categories,id'
        ]);
    }
}